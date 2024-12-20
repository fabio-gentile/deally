<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Deal;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's favorite deals and discussions
     *
     * @param User $user
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
            ->get()
            ->map(function ($deal) use ($currentUser) {
                $deal->user_vote = $deal->voteDetails->first() ?? false;
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
                // Load the user's favorite if the user is authenticated
                $query->withExists(['favorites' => function ($subQuery) use ($currentUser) {
                    $subQuery->where('user_id', $currentUser->id);
                }]);
            })
            ->get()
            ->map(function ($discussion) use ($currentUser) {
                $discussion->user_favorite = $currentUser ? $discussion->favorites_exists : false;

                return $discussion;
            });

//        dd($deals, $discussions);

        $latestFavorites = collect();

        if ($deals->isNotEmpty()) {
            $latestFavorites = $latestFavorites->merge($deals->map(function ($deal) {
                return ['type' => 'deal', 'item' => $deal];
            }));
        }

        if ($discussions->isNotEmpty()) {
            $latestFavorites = $latestFavorites->merge($discussions->map(function ($discussion) {
                return ['type' => 'discussion', 'item' => $discussion];
            }));
        }

        $latestFavorites = $latestFavorites->sortByDesc('item.created_at')->values();

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
     * @param User $user
     * @return Response
     */
    public function deals(Request $request, User $user): Response
    {
        $currentUser = auth()->user();

        $deals = Deal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with([
                'voteDetails' => function ($query) use ($currentUser) {
                    // Load the user's vote details if the user is authenticated
                    if ($currentUser) {
                        $query->where('user_id', $currentUser->id);
                    }
                },
                'images' => function ($query) {
                    $query->limit(1);
                },
                'favorites' => function ($query) use ($currentUser) {
                    if ($currentUser) {
                        $query->where('user_id', $currentUser->id);
                    }
                }
            ])
            ->withCount('comments')
            ->paginate(10);

        // Associate the user's vote and favorite with each deal
        $deals->each(function ($deal) use ($currentUser) {
            $deal->is_expired = $deal->isExpired();
            if ($currentUser) {
                $deal->user_vote = $deal->voteDetails->first();
                $deal->user_favorite = $deal->favorites->isNotEmpty();
            }
        });

        $pagination = [
            'current_page' => $deals->currentPage(),
            'last_page' => $deals->lastPage(),
            'per_page' => $deals->perPage(),
            'total' => $deals->total(),
        ];

//dd($deals, $pagination);
        return Inertia::render('Profile/Deals', [
            'user' => $user,
            'filters' => $request->all(),
            'pagination' => $pagination,
            'deals' => $deals->items(),
            'dealsCount' => Deal::where('user_id', $user->id)->count(),
            'discussionsCount' => Discussion::where('user_id', $user->id)->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
            'isCurrentUser' => $currentUser && $currentUser->id === $user->id,
        ]);
    }

    /**
     * Display the user's discussions
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function discussions(Request $request, User $user): Response
    {
        $currentUser = auth()->user();

        // Créer la requête de base pour les discussions de l'utilisateur dont on visite le profil
        $discussions = Discussion::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with([
                'favorites' => function ($query) use ($currentUser) {
                    // Charger les favoris seulement si un utilisateur est connecté
                    if ($currentUser) {
                        $query->where('user_id', $currentUser->id);
                    }
                }
            ])
            ->withCount('comments')
            ->paginate(10);

        // Associer le statut de favori pour l'utilisateur connecté, si applicable
        $discussions->each(function ($discussion) use ($currentUser) {
            if ($currentUser) {
                $discussion->user_favorite = $discussion->favorites->isNotEmpty();
            }
        });

        $pagination = [
            'current_page' => $discussions->currentPage(),
            'last_page' => $discussions->lastPage(),
            'per_page' => $discussions->perPage(),
            'total' => $discussions->total(),
        ];

        return Inertia::render('Profile/Discussions', [
            'user' => $user,
            'filters' => $request->all(),
            'pagination' => $pagination,
            'discussions' => $discussions->items(),
            'dealsCount' => Deal::where('user_id', $user->id)->count(),
            'discussionsCount' => Discussion::where('user_id', $user->id)->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
            'isCurrentUser' => $currentUser && $currentUser->id === $user->id,
        ]);
    }

    /**
     * Display the user's statistics
     *
     * @param User $user
     * @return Response
     */
    public function statistics(User $user): Response
    {
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
            'user' => $user,
            'mostUpvotedDealCount' => $mostUpvotedDeal ? $mostUpvotedDeal->votes : 0,
            'averageVotesPerDeal' => $averageVotesPerDeal,
            'commentsDealsCount' => $commentsDealsCount,
            'dealsCount' => $dealCount,
            'averageCommentsPerDiscussion' => $averageCommentsPerDiscussion,
            'commentsDiscussionsCount' => $commentsDiscussionsCount,
            'discussionsCount' => $discussionCount,
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
            'isCurrentUser' => auth()->id() === $user->id,
        ]);
    }

    /**
     * Display the user's settings
     *
     * @param User $user
     * @return Response|RedirectResponse
     */
    public function settings(User $user): Response | \Illuminate\Http\RedirectResponse
    {
        if (auth()->id() !== $user->id) {
            return redirect()->route('home.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return Inertia::render('Profile/Settings', [
            'user' => $user,
            'isCurrentUser' => true,
            'dealsCount' => Deal::where('user_id', auth()->id())->count(),
            'discussionsCount' => Discussion::where('user_id', auth()->id())->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
        ]);
    }

    /**
     * Update the user's profile information.
     * @param ProfileUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function updateProfileInformations(ProfileUpdateRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if (auth()->id() !== $user->id) {
            return redirect()->route('home.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        $request->validated();

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
        $user->save();

        if ($user->wasChanged('name')) {
            return redirect()->route('profile.settings', $user->name)->with('success', 'Le profil a été mis à jour avec succès. Vous ne pourrez pas modifier votre nom avant 30 jours.');
        }

        return back()->with('success', 'Le profil a été mis à jour avec succès.');
    }

    /**
     * Delete the user's avatar
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // delete the current avatar
        try {
            Storage::delete('uploads/avatar/' . $user->avatar);
            $user->update([
                'avatar' => null,
            ]);
            $user->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
        }

        return redirect()->back()->with('success', 'Votre avatar a été supprimé avec succès.');
    }

    /**
     * Destroy the user's account
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm' => ['required', 'string'],
            'textConfirmDeleteAccount' => ['required', 'string'],
        ]);

        if (strtolower($request->confirm) !== strtolower($request->textConfirmDeleteAccount)) {
            return back()->with('error', 'Erreur de confirmation. Veuillez saisir le texte correct pour supprimer votre compte.');
        }

        $user = $request->user();

        // if user has the role admin, prevent account deletion
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Vous ne pouvez pas supprimer un compte administrateur.');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Votre compte a été supprimé avec succès. Vous pouvez vous réinscrire à tout moment.');
    }

    /**
     * Update the user's notification preferences
     *
     * @param User $user
     * @return Response|RedirectResponse
     */
    public function notifications(User $user): Response|RedirectResponse
    {
        if (auth()->id() !== $user->id) {
            return redirect()->route('home.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        return Inertia::render('Profile/Notifications', [
            'user' => $user,
            'isCurrentUser' => auth()->id() === $user->id,
            'dealsCount' => Deal::where('user_id', auth()->id())->count(),
            'discussionsCount' => Discussion::where('user_id', auth()->id())->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
            'newsletter' => $user->preferences,
        ]);
    }
}
