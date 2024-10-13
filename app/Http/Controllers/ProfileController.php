<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the user's favorite deals and discussions
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $user = auth()->user();

        $favorites = $user->favorites()->whereIn('favoritable_type', [Deal::class, Discussion::class])->get();

        // Separate deal and discussion favorites
        $favoriteDealIds = $favorites->where('favoritable_type', Deal::class)->pluck('favoritable_id');
        $favoriteDiscussionIds = $favorites->where('favoritable_type', Discussion::class)->pluck('favoritable_id');

        // Load deal and discussion details
        $deals = Deal::with(['voteDetails' => function ($query) use ($user) {
            $query->when($user, fn($q) => $q->where('user_id', $user->id));
        }, 'images' => function ($query) {
            $query->limit(1);
        }])
            ->whereIn('id', $favoriteDealIds)
            ->withCount('comments')
            ->get()
            ->map(function ($deal) use ($user) {
                $deal->user_vote = $deal->voteDetails->first();
                $deal->is_expired = $deal->isExpired();
                $deal->user_favorite = $deal->favorites->isNotEmpty();

                return $deal;
            });

        $discussions = Discussion::with(['favorites' => function ($query) use ($user) {
            $query->when($user, fn($q) => $q->where('user_id', $user->id));
        }])
            ->whereIn('id', $favoriteDiscussionIds)
            ->withCount('comments')
            ->get()
            ->map(function ($discussion) {
                $discussion->user_favorite = $discussion->favorites->isNotEmpty();

                return $discussion;
            });

        $latestFavorites = $deals->map(function ($deal) {
            return ['type' => 'deal', 'item' => $deal];
        })->merge($discussions->map(function ($discussion) {
            return ['type' => 'discussion', 'item' => $discussion];
        }))->sortByDesc('item.created_at')->values();

        return Inertia::render('Profile/Favorite', [
            'latestFavorites' => $latestFavorites,
            'user' => [
                'name' => $user->name,
//                'avatar' => $user->avatar,
            ],
            'dealsCount' => Deal::where('user_id', $user->id)->count(),
            'discussionsCount' => Discussion::where('user_id', $user->id)->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
        ]);
    }
}
