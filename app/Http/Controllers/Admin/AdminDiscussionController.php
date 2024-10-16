<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateDiscussionRequest;
use App\Models\CategoryDiscussion;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AdminDiscussionController
{
    use \App\Traits\UpdateDiscussion;
    /**
     * Display a listing of discussions.
     */
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
        return Inertia::render('Admin/Discussion/List', [
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
                    'slug' => $discussion->slug,
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


    /**
     * Show the form for editing the user
     */
    public function edit(string $discussion): \Inertia\Response
    {
        $discussion = Discussion::where('id', $discussion)->firstOrFail();

        if ($discussion->thumbnail) {
            $currentThumbnail = [
                'path' => $discussion->path,
                'filename' => $discussion->thumbnail,
                'originalName' => $discussion->original_filename,
            ];
        } else {
            $currentThumbnail = null;
        }

        return Inertia::render('Admin/Discussion/Edit', [
            'discussion' => $discussion,
            'currentCategory' => CategoryDiscussion::where('id', $discussion->category_discussion_id)->first()->name,
            'categories' => CategoryDiscussion::orderBy('name', 'asc')->get(),
            'currentThumbnail' => $currentThumbnail,
        ]);
    }

    /**
     * Update the discussion
     * @throws Exception
     */
    public function update(UpdateDiscussionRequest $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $discussion = Discussion::where('id', $id)->firstOrFail();

        $this->handleUpdateDiscussion($request, $discussion);

        return redirect()->route('admin.discussions.list')->with('success', 'La discussion a été modifiéé avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion, int $id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $discussion = Discussion::where('id', $id)->firstOrFail();

        $discussion->delete();

        return redirect()->route('admin.discussions.list')->with('success', 'La discussion a été supprimée avec succès');
    }
}
