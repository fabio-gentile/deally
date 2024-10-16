<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryDeal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCategoryDealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $categories = CategoryDeal::query();

        if ($request->has('search') && $request->search !== null) {
            $categories->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $categories->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Deal/Category/List', [
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
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Admin/Deal/Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CategoryDeal::create($request->all());

        return redirect()->route('admin.categories-deals.list')->with('success', $request->name . ' a été créée avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Inertia\Response
    {
        $category = CategoryDeal::findOrFail($id);

        return Inertia::render('Admin/Deal/Category/Edit', [
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

        $category = CategoryDeal::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories-deals.list')->with('success', $request->name . ' a été modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $category = CategoryDeal::findOrFail($id);
        $deals = $category->deals;

        foreach ($deals as $deal) {
            $deal->delete();
        }

        $category->delete();

        return redirect()->route('admin.categories-deals.list')->with('success', $category->name . ' a été supprimée avec succès');
    }
}
