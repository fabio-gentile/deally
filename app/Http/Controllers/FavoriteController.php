<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Discussion;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
//        dd($request->all());
        $request->validate([
            'type' => ['required', 'in:deal,discussion'],
            'id' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        // Find the favoritable model instance (Deal or Discussions)
        if ($request->input('type') === 'deal') {
            $favoritable = Deal::findOrFail($request->input('id'));
        } else if ($request->input('type') === 'discussion') {
            $favoritable = Discussion::findOrFail($request->input('id'));
        }

        // Check if the user has already favorited the model
        $existingFavorite = $user->favorites()->where('favoritable_id', $favoritable->id)
            ->where('favoritable_type', get_class($favoritable))
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            return back()->with('success', 'Favoris supprimé avec succès !');
        } else {
            $user->favorites()->create([
                'favoritable_id' => $favoritable->id,
                'favoritable_type' => get_class($favoritable),
            ]);
            return back()->with('success', 'Favoris ajouté avec succès !');
        }
    }
}
