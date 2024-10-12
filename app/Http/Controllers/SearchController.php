<?php

namespace App\Http\Controllers;

use App\Models\CategoryDeal;
use App\Models\CategoryDiscussion;
use App\Models\Deal;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    /**
     * Search for deals
     *
     * @param Request $request
     * @return Response
     */
    public function searchDeal(Request $request): Response
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

        $deals->withCount('comments');

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

    /**
     * Search for discussions
     *
     * @param Request $request
     * @return Response
     */
    public function searchDiscussion(Request $request): Response
    {
        $discussions = Discussion::query();

        if ($request->filled('period')) {
            $period = $request->input('period');
            $date = new \DateTime();
            if ($period === 'today') {
                $discussions->where('created_at', '>=', $date->modify('-1 day'));
            } elseif ($period === 'week') {
                $discussions->where('created_at', '>=', $date->modify('-1 week'));
            } elseif ($period === 'month') {
                $discussions->where('created_at', '>=', $date->modify('-1 month'));
            }
        }

        if ($request->filled('category')) {
            $category = CategoryDiscussion::where('name', $request->input('category'))->first();
            if ($category) {
                $discussions->where('category_discussion_id', $category->id);
            }
        }

        if ($request->filled('comments')) {
            $comments = $request->input('comments');
            if ($comments === 'all') {
                $discussions->having('comments_count', '>=', 0);
            } elseif ($comments == 5) {
                $discussions->having('comments_count', '>=', 5);
            } elseif ($comments == 10) {
                $discussions->having('comments_count', '>=', 10);
            } elseif ($comments == 20) {
                $discussions->having('comments_count', '>=', 20);
            } elseif ($comments == 50) {
                $discussions->having('comments_count', '>=', 50);
            }
        }

        if ($request->filled('filter_by')) {
            if ($request->input('filter_by') === 'popular') {
                $discussions->orderBy('comments_count', 'desc');
            } elseif ($request->input('filter_by') === 'newest') {
                $discussions->orderBy('created_at', 'desc');
            }
        }

        if (!$request->filled('filter_by')) {
            $discussions->orderBy('created_at', 'desc');
        }

        $discussions->withCount('comments');
        $discussions = $discussions->paginate(10);

        $pagination = [
            'current_page' => $discussions->currentPage(),
            'last_page' => $discussions->lastPage(),
            'per_page' => $discussions->perPage(),
            'total' => $discussions->total(),
        ];

        return Inertia::render('Search/Discussion', [
            'categories' => CategoryDiscussion::all(),
            'discussions' => $discussions->items(),
            'filters' => $request->all(),
            'pagination' => $pagination
        ]);
    }

    /**
     * Search for users
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('q');

        $deals = Deal::where('title', 'like', '%' . $query . '%')->limit(3)->get();
        $users = User::where('name', 'like', '%' . $query . '%')->limit(3)->get();
        $discussions = Discussion::where('title', 'like', '%' . $query . '%')->limit(3)->get();

        return response()->json([
                'deals' => $deals,
                'users' => $users,
                'discussions' => $discussions
        ]);
    }
}
