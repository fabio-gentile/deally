<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\VoteDeals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VoteController extends Controller
{
    /**
     * Display a listing of the vote.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new vote.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created vote in storage.
     */
    public function store(Request $request, int $id)
    {
        $deal = Deal::findOrFail($id);

        if ($deal->isExpired()) {
            return back()->with('error', 'Cette offre a expiré');
        }

        $type = $request->type;
        // Check if the user has already voted for this deal
        $vote = VoteDeals::where('deal_id', $deal->id)
            ->where('user_id', auth()->id())
            ->first();

        // If the user has already voted for this deal, return an error message
        if ($vote) {
            return back()->with('error', 'Vous avez déjà voté pour cette offre');
        }

        // If the user has not voted for this deal, create a new vote

//        return response()->json(['error' => $type])->setStatusCode(403);
        VoteDeals::create([
            'deal_id' => $deal->id,
            'user_id' => auth()->id(),
            'type' => $type == 'down' ? 'down' : 'up',
            'created_at' => now(),
        ]);

        if ($type === 'up') {
            $deal->increment('votes');
        } else {
            $deal->decrement('votes');
        }

//        dd('here');
        return back()->with('success', 'Votre vote a été enregistré');
    }

    /**
     * Display the specified vote.
     */
    public function show(VoteDeals $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified vote.
     */
    public function edit(VoteDeals $vote)
    {
        //
    }

    /**
     * Update the specified vote in storage.
     */
    public function update(Request $request, VoteDeals $vote)
    {
        //
    }

    /**
     * Remove the specified vote from storage.
     */
    public function destroy(VoteDeals $vote)
    {
        //
    }
}
