<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CategoryDeal;
use App\Models\Deal;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $user = auth()->user();
        $deals = Deal::query();

        $deals->orderBy('votes', 'desc');

        // by default, only show deals created in the last 30 days
        if (!$request->filled('period')) {
            $deals->where('created_at', '>', now()->subDays(30));
        }

        if ($request->filled('period')) {
            $period = $request->input('period');
            $date = new \DateTime();
            if ($period === 'day') {
                $deals->where('created_at', '>=', $date->modify('-1 day'));
            } elseif ($period === 'week') {
                $deals->where('created_at', '>=', $date->modify('-1 week'));
            } elseif ($period === 'month') {
                $deals->where('created_at', '>=', $date->modify('-1 month'));
            }
        }

        $deals->with(['voteDetails' => function ($query) use ($user) {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }, 'images' => function ($query) {
            // Limit the number of images to 1
            $query->limit(1);
        }, 'favorites' => function ($query) use ($user) {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }
        ]);

        $deals->withCount('comments');
        $deals = $deals->paginate(10);

        if ($user) {
            $deals->each(function ($deal) use ($user) {
                $deal->user_vote = $deal->voteDetails->first();
                $deal->is_expired = $deal->isExpired();
                $deal->user_favorite = $deal->favorites->isNotEmpty();
            });
        }

        $pagination = [
            'current_page' => $deals->currentPage(),
            'last_page' => $deals->lastPage(),
            'per_page' => $deals->perPage(),
            'total' => $deals->total(),
        ];

        return Inertia::render('Home', [
            'categories' => CategoryDeal::all(),
            'deals' => $deals->items(),
            'filters' => $request->all(),
            'pagination' => $pagination,
            'discussions' => Discussion::orderBy('created_at', 'desc')->with('user')->withCount('comments')->limit(5)->get(),
            'blogs' => Blog::orderBy('published_at', 'desc')->where('is_published', true)->withCount('comments')->limit(5)->get(),
        ]);
    }
}
