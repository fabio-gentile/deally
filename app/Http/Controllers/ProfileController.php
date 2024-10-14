<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Deal;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's favorite deals and discussions
     *
     * @return Response
     */
    public function index(User $user): Response
    {
//        dd($user);
        $currentUser = auth()->user();

        $favorites = $user->favorites()->whereIn('favoritable_type', [Deal::class, Discussion::class])->get();
        $favoriteDealIds = $favorites->where('favoritable_type', Deal::class)->pluck('favoritable_id');
        $favoriteDiscussionIds = $favorites->where('favoritable_type', Discussion::class)->pluck('favoritable_id');

        $deals = Deal::with([
            'voteDetails' => function ($query) use ($currentUser) {
                // Load the user's vote details if the user is authenticated
                if ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                }
            },
            'images' => function ($query) {
                $query->limit(1);
            }
        ])
            ->whereIn('id', $favoriteDealIds)
            ->withCount('comments')
            ->when($currentUser, function ($query) use ($currentUser) {
                // Load the user's favorite if the user is authenticated
                $query->withExists(['favorites' => function ($subQuery) use ($currentUser) {
                    $subQuery->where('user_id', $currentUser->id);
                }]);
            })
            ->withExists(['voteDetails' => function ($query) use ($currentUser) {
                if ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                }
            }])
            ->get()
            ->map(function ($deal) use ($currentUser) {
                $deal->user_vote = $currentUser ? $deal->vote_details_exists : false;
                $deal->is_expired = $deal->isExpired();
                $deal->user_favorite = $currentUser ? $deal->favorites_exists : false;

                return $deal;
            });

        $discussions = Discussion::with(['favorites' => function ($query) use ($currentUser) {
            if ($currentUser) {
                $query->where('user_id', $currentUser->id);
            }
        }])
            ->whereIn('id', $favoriteDiscussionIds)
            ->withCount('comments')
            ->when($currentUser, function ($query) use ($currentUser) {
                $query->withExists(['favorites' => function ($subQuery) use ($currentUser) {
                    $subQuery->where('user_id', $currentUser->id);
                }]);
            })
            ->get()
            ->map(function ($discussion) use ($currentUser) {
                $discussion->user_favorite = $currentUser ? $discussion->favorites_exists : false;

                return $discussion;
            });

        $latestFavorites = $deals->map(function ($deal) {
            return ['type' => 'deal', 'item' => $deal];
        })->merge($discussions->map(function ($discussion) {
            return ['type' => 'discussion', 'item' => $discussion];
        }))->sortByDesc('item.created_at')->values();

        return Inertia::render('Profile/Favorite', [
            'latestFavorites' => $latestFavorites,
            'user' => $user,
            'dealsCount' => Deal::where('user_id', $user->id)->count(),
            'discussionsCount' => Discussion::where('user_id', $user->id)->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
            'isCurrentUser' => $currentUser && $currentUser->id === $user->id,
        ]);
    }

    /**
     * Display the user's deals
     *
     * @param Request $request
     * @return Response
     */
    public function deals(Request $request): Response
    {
        $user = auth()->user();
        $deals = Deal::query();

        $deals->where('user_id', $user->id);
        $deals->orderBy('created_at', 'desc');

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
            // Associate user_vote and favorite in one pass
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
//dd($deals, $pagination);
        return Inertia::render('Profile/Deals', [
            'user' => [
                'name' => auth()->user()->name,
                'avatar' => auth()->user()->avatar ?? null,
            ],
            'filters' => $request->all(),
            'pagination' => $pagination,
            'deals' => $deals->items(),
            'dealsCount' => Deal::where('user_id', auth()->id())->count(),
            'discussionsCount' => Discussion::where('user_id', auth()->id())->count(),
            'commentsCount' => auth()->user()->dealComments()->count() + auth()->user()->discussionComments()->count(),
        ]);
    }

    /**
     * Display the user's discussions
     *
     * @param Request $request
     * @return Response
     */
    public function discussions(Request $request): Response
    {
        $user = auth()->user();
        $discussions = Discussion::query();

        $discussions->where('user_id', $user->id);
        $discussions->orderBy('created_at', 'desc');

        $discussions->with(['favorites' => function ($query) use ($user) {
            if ($user) {
                $query->where('user_id', $user->id);
            }}
        ]);

        $discussions->withCount('comments');

        $discussions = $discussions->paginate(10);

        if ($user) {
            $discussions->each(function ($discussion) use ($user) {
                $discussion->user_favorite = $discussion->favorites->isNotEmpty();
            });
        }

        $pagination = [
            'current_page' => $discussions->currentPage(),
            'last_page' => $discussions->lastPage(),
            'per_page' => $discussions->perPage(),
            'total' => $discussions->total(),
        ];

        return Inertia::render('Profile/Discussions', [
            'user' => [
                'name' => auth()->user()->name,
                'avatar' => auth()->user()->avatar ?? null,
            ],
            'filters' => $request->all(),
            'pagination' => $pagination,
            'discussions' => $discussions->items(),
            'dealsCount' => Deal::where('user_id', auth()->id())->count(),
            'discussionsCount' => Discussion::where('user_id', auth()->id())->count(),
            'commentsCount' => auth()->user()->dealComments()->count() + auth()->user()->discussionComments()->count(),
        ]);
    }

    /**
     * Display the user's statistics
     *
     * @return Response
     */
    public function statistics(): Response
    {
        $user = auth()->user();
        $deals = Deal::where('user_id', $user->id)
            ->withCount('comments')
            ->get();
        // Get the total number of comments on all the user's deals
        $commentsDealsCount = $deals->sum('comments_count');
        // Get the total number of deals
        $dealCount = $deals->count();
        // Calculate the average votes per deal
        $averageVotesPerDeal = $dealCount > 0
            ? number_format($deals->avg('votes'), 2)
            : 0;
        // Get the most voted deal
        $mostUpvotedDeal = Deal::where('user_id', $user->id)
            ->orderBy('votes', 'desc')
            ->first();


        $discussions = Discussion::where('user_id', $user->id)->withCount('comments')->get();
        // Get the total number of comments on all the user's discussions
        $commentsDiscussionsCount = $discussions->sum('comments_count') > 0
            ? $discussions->sum('comments_count')
            : 0;
        // Get the total number of discussions
        $discussionCount = $discussions->count() > 0
            ? $discussions->count()
            : 0;
        // Calculate the average comments per discussion
        $averageCommentsPerDiscussion = $discussionCount > 0
            ? number_format($discussions->avg('comments_count'), 2)
            : 0;

        return Inertia::render('Profile/Statistics', [
            'user' => [
                'name' => auth()->user()->name,
                'avatar' => auth()->user()->avatar ?? null,
            ],
            'mostUpvotedDealCount' => $mostUpvotedDeal ? $mostUpvotedDeal->votes : 0,
            'averageVotesPerDeal' => $averageVotesPerDeal,
            'commentsDealsCount' => $commentsDealsCount,
            'dealsCount' => $dealCount,
            'averageCommentsPerDiscussion' => $averageCommentsPerDiscussion,
            'commentsDiscussionsCount' => $commentsDiscussionsCount,
            'discussionsCount' => $discussionCount,
            'commentsCount' => auth()->user()->dealComments()->count() + auth()->user()->discussionComments()->count(),
        ]);
    }

    /**
     * Display the user's settings
     *
     * @return Response
     */
    public function settings(): Response
    {
        return Inertia::render('Profile/Settings', [
            'user' => [
                'name' => auth()->user()->name,
                'avatar' => auth()->user()->avatar ?? null,
            ],
            'dealsCount' => Deal::where('user_id', auth()->id())->count(),
            'discussionsCount' => Discussion::where('user_id', auth()->id())->count(),
            'commentsCount' => auth()->user()->dealComments()->count() + auth()->user()->discussionComments()->count(),
        ]);
    }

    /**
     * Update the user's profile information.
     * @param ProfileUpdateRequest $request
     */
    public function updateProfileInformations(ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        $user = auth()->user();

        // Check if the user can change their name (once every 30 days)
        $isNameChangeable = $user->name_updated_at === null || $user->name_updated_at->diffInDays(now()) >= 30;
        if (!$isNameChangeable && $user->name !== $request->name) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre nom plus d\'une fois par mois.');
        }

        $currentAvatar = $user->avatar;
        if ($request->hasFile('avatar')) {
            // delete the current avatar
            try {
                Storage::delete('uploads/avatar/' . $currentAvatar);
                $user->update([
                    'avatar' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
            }

            // store the new avatar
            $avatar = $request->file('avatar');
            $extension = $avatar->extension();
            $avatarName = uniqid('avatar-', true) . '.' . $extension;

            $avatar->storeAs('uploads/avatar/', $avatarName, 'public');
            $user->avatar = $avatarName;
        }


        $user->update([
            'name' => $request->name,
            'biography' => $request->biography,
            'avatar' => $avatarName ?? $currentAvatar,
            'name_updated_at' => $request->name !== $user->name ? now() : $user->name_updated_at,
        ]);

        return back()->with('success', 'Le profil a été mis à jour avec succès.');
    }
}
