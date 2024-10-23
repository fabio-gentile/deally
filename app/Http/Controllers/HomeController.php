<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserForYouHomeRequest;
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

        return $this->renderDeals($request, $deals, $user);
    }

    /**
     * Display the newests deals.
     */
    public function new(Request $request): \Inertia\Response
    {
        $user = auth()->user();
        $deals = Deal::query();

        $deals->orderBy('created_at', 'desc');

        return $this->renderDeals($request, $deals, $user);
    }

    /**
     * Display the deals based on user's preference.
     */
    public function forYou(Request $request): \Inertia\Response
    {
        $user = auth()->user();
        $preferencesCategories = $user->preferences['home_foryou']['category_id'];

        $deals = Deal::query();
        // Only show deals that are in the user's preferences
        $deals->whereIn('category_deal_id', $preferencesCategories);

        list($deals, $pagination) = $this->getDeals($request, $deals, $user);

        return Inertia::render('Home/ForYou', [
            'categories' => CategoryDeal::orderBy('name', 'asc')->get(),
            'preferencesCategories' => $preferencesCategories,
            'deals' => $deals->items(),
            'filters' => $request->all(),
            'pagination' => $pagination,
            'discussions' => Discussion::orderBy('created_at', 'desc')->with('user')->withCount('comments')->limit(5)->get(),
            'blogs' => Blog::orderBy('published_at', 'desc')->where('is_published', true)->withCount('comments')->limit(5)->get(),
        ]);
    }

    /**
     * Update the user's preferences for the For You page.
     */
    public function updateForYou(UserForYouHomeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();

        // Cannot be directly modified because preferences is casts
        $preferences = $user->preferences;
        $preferences['home_foryou']['category_id'] = $request->categories;
        $user->preferences = $preferences;

        $user->save();

        return redirect()->route('home.for-you')->with('success', 'Vos préférences ont été mises à jour.');
    }

    /**
     * Render the deals.
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Builder $deals
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return \Inertia\Response
     */
    private function renderDeals(Request $request, \Illuminate\Database\Eloquent\Builder $deals, ?\Illuminate\Contracts\Auth\Authenticatable $user): \Inertia\Response
    {
        list($deals, $pagination) = $this->getDeals($request, $deals, $user);

        return Inertia::render('Home/Home', [
            'categories' => CategoryDeal::orderBy('name', 'asc')->get(),
            'deals' => $deals->items(),
            'filters' => $request->all(),
            'pagination' => $pagination,
            'discussions' => Discussion::orderBy('created_at', 'desc')->with('user')->withCount('comments')->limit(5)->get(),
            'blogs' => Blog::orderBy('published_at', 'desc')->where('is_published', true)->withCount('comments')->limit(5)->get(),
        ]);
    }

    /**
     * Get the deals.
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Builder $deals
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return array
     */
    private function getDeals(Request $request, \Illuminate\Database\Eloquent\Builder $deals, ?\Illuminate\Contracts\Auth\Authenticatable $user): array
    {
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
        return array($deals, $pagination);
    }
}
