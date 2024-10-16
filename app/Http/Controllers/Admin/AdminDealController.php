<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $deals = Deal::query()->with(['userName', 'categoryDeal']);

        if ($request->has('search') && $request->search !== null) {
            $deals->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('votes') && $request->votes !== null) {
            $deals->orderBy('votes', $request->votes === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('expiration_date') && $request->expiration_date !== null) {
            $deals->orderBy('expiration_date', $request->expiration_date === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('title') && $request->title !== null) {
            $deals->orderBy('title', $request->title === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $deals->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $deals = $deals->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Deal/List', [
            'deals' => $deals->getCollection()->map(function ($deal) {
                return [
                    'id' => $deal->id,
                    'title' => $deal->title,
                    'price' => $deal->price,
                    'expiration_date' => $deal->expiration_date,
                    'created_at' => $deal->created_at,
                    'slug' => $deal->slug,
                    'category' => $deal->categoryDeal->name,
                    'votes' => $deal->votes,
                ];
            }),
            'pagination' => [
                'current_page' => $deals->currentPage(),
                'last_page' => $deals->lastPage(),
                'per_page' => $deals->perPage(),
                'total' => $deals->total(),
            ],
            'filters' => $request->all(),
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
