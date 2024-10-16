<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDiscussionController
{
    public function index(Request $request): \Inertia\Response
    {
        $discussions = Discussion::query()->withCount('comments')->with('userName');;

        if ($request->has('search') && $request->search !== null) {
            $discussions->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('title') && $request->title !== null) {
            $discussions->orderBy('title', $request->title === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('comments') && $request->comments !== null) {
            $discussions->orderBy('comments_count', $request->comments === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $discussions->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $discussions = $discussions->paginate($request->per_page ?? 10);
//        dd($discussions->items());
        return Inertia::render('Admin/Discussions/List', [
            'discussions' => $discussions->getCollection()->map(function ($discussion) {
                return [
                    'id' => $discussion->id,
                    'title' => $discussion->title,
                    'comments_count' => $discussion->comments_count,
                    'user' => [
                        'name' => $discussion->userName->name,
                        'id' => $discussion->userName->id,
                    ],
                    'created_at' => $discussion->created_at,
                    // Add other fields as needed
                ];
            }),
            'pagination' => [
                'current_page' => $discussions->currentPage(),
                'last_page' => $discussions->lastPage(),
                'per_page' => $discussions->perPage(),
                'total' => $discussions->total(),
            ],
            'filters' => $request->all(),
        ]);
    }

    public function edit()
    {
        return view('admin.discussions.edit');
    }

    public function update()
    {
        return redirect()->route('admin.discussions.list');
    }
}
