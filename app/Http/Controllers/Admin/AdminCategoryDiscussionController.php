<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryDiscussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCategoryDiscussionController {

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
}
