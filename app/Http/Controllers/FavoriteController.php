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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'type' => ['required', 'in:deal,discussion'],
            'id' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        // Find the favoritable model instance (Deal or Discussion)
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
            return back()->with('success', 'Bookmark removed successfully!');
        } else {
            $user->favorites()->create([
                'favoritable_id' => $favoritable->id,
                'favoritable_type' => get_class($favoritable),
            ]);
            return back()->with('success', 'Bookmarked successfully!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
