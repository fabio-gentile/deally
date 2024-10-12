<?php

namespace App\Http\Controllers;

use App\Models\CategoryDeal;
use App\Models\Deal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function searchDeal(Request $request): \Inertia\Response
    {
        $user = auth()->user();
        $deals = Deal::query();

        $deals->where('expiration_date', '>', now()); // Do not show expired deals

        if ($request->filled('period')) {
            $period = $request->input('period');
            $date = new \DateTime();
            if ($period === 'today') {
                $deals->where('created_at', '>=', $date->modify('-1 day'));
            } elseif ($period === 'week') {
                $deals->where('created_at', '>=', $date->modify('-1 week'));
            } elseif ($period === 'month') {
                $deals->where('created_at', '>=', $date->modify('-1 month'));
            }
        }

        if ($request->filled('price_min')) {
            $deals->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $deals->where('price', '<=', $request->input('price_max'));
        }

        if ($request->filled('category')) {
            // Find category by name
            $category = CategoryDeal::where('name', $request->input('category'))->first();
            if ($category) {
                $deals->where('category_deal_id', $category->id);
            }
        }

        if ($request->filled('votes')) {
            $votes = $request->input('votes');

            if ($votes === 'all') {
                $deals->where('votes', '>=', 0);
            } elseif ($votes == 20) {
                $deals->where('votes', '>=', 20);
            } elseif ($votes == 50) {
                $deals->where('votes', '>=', 50);
            } elseif ($votes == 100) {
                $deals->where('votes', '>=', 100);
            } elseif ($votes == 200) {
                $deals->where('votes', '>=', 200);
            } elseif ($votes == 500) {
                $deals->where('votes', '>=', 500);
            }
        }


        if ($request->filled('filter_by')) {
            if ($request->input('filter_by') === 'popular') {
                $deals->orderBy('votes', 'desc');
            } elseif ($request->input('filter_by') === 'newest') {
                $deals->orderBy('created_at', 'desc');
            }
        }

        if (!$request->filled('filter_by')) {
            $deals->orderBy('created_at', 'desc');
        }

        $deals->with(['voteDetails' => function ($query) use ($user) {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }, 'images' => function ($query) {
            // Limit the number of images to 1
            $query->limit(1);
        }]);
        $deals = $deals->paginate(10);


        if ($user) {
            $deals->each(function ($deal) use ($user) {
                $deal->user_vote = $deal->voteDetails->first();
                $deal->is_expired = $deal->isExpired();
            });
        }

        $pagination = [
            'current_page' => $deals->currentPage(),
            'last_page' => $deals->lastPage(),
            'per_page' => $deals->perPage(),
            'total' => $deals->total(),
        ];
//        dd($deals, $pagination);

        return Inertia::render('Search/Deal', [
            'categories' => CategoryDeal::all(),
            'deals' => $deals->items(),
            'filters' => $request->all(),
            'pagination' => $pagination
        ]);
    }
}
