<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryDiscussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCategoryDiscussionController {

    /**
     * Display a listing of discussions.
     */
    public function index(Request $request): \Inertia\Response
    {
        $categories = CategoryDiscussion::query();

        if ($request->has('search') && $request->search !== null) {
            $categories->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $categories->paginate($request->per_page ?? 10);
//dd($categories->items());
        return Inertia::render('Admin/Discussion/Category/List', [
            'categories' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/Discussion/Category/Create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CategoryDiscussion::create($request->all());

        return redirect()->route('admin.categories-discussions.list')->with('success', $request->name . ' a été créée avec succès');
    }
}
