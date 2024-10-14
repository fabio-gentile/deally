<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Deal;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                'avatar' => $user->avatar ?? null,
            ],
            'dealsCount' => Deal::where('user_id', $user->id)->count(),
            'discussionsCount' => Discussion::where('user_id', $user->id)->count(),
            'commentsCount' => $user->dealComments()->count() + $user->discussionComments()->count(),
        ]);
    }

    public function settings(): \Inertia\Response
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
