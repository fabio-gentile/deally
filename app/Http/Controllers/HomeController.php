<?php

namespace App\Http\Controllers;

use App\Models\CategoryDeal;
use App\Models\Deal;
use App\Models\VoteDeals;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $user = auth()->user();
//        $user->assignRole('admin');
        $deals = Deal::with(['voteDetails' => function ($query) use ($user) {
            // filter the vote of the user
            if ($user) {
                $query->where('user_id', $user->id);
            };
        }, 'images' => function ($query) {
            $query->limit(1);
        }, 'favorites' => function ($query) use ($user) {
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }])->limit(10)->get();

        // add the user vote to the deal
        if ($user) {
            $deals->each(function ($deal) use ($user) {
                // associate the voteDeals to the deal
                $deal->user_vote = $deal->voteDetails->first();
                $deal->is_expired = $deal->isExpired();
                $deal->user_favorite = $deal->favorites->isNotEmpty();
            });
        }
//dd($deals);
        return Inertia::render('Home', [
            'categories' => CategoryDeal::all(),
            'deals' => $deals,
        ]);
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
