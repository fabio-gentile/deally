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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CategoryDiscussion::create($request->all());

        return redirect()->route('admin.categories-discussions.list')->with('success', $request->name . ' a été créée avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Inertia\Response
    {
        $category = CategoryDiscussion::findOrFail($id);
        return Inertia::render('Admin/Discussion/Category/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = CategoryDiscussion::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories-discussions.list')->with('success', $request->name . ' a été modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $category = CategoryDiscussion::findOrFail($id);
        $discussions = $category->discussions;
        foreach ($discussions as $discussion) {
            $discussion->delete();
        }

        $category->delete();

        return redirect()->route('admin.categories-discussions.list')->with('success', 'La catégorie a été supprimée avec succès');
    }
}
