<?php

namespace App\Http\Controllers;

use App\Models\CategoryDiscussion;
use App\Models\Discussion;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\UpdateDiscussionRequest;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DiscussionController extends Controller
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
        return Inertia::render('Discussion/Create', [
            'categories' => CategoryDiscussion::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscussionRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $extension = $thumbnail->extension();
            $originalName = $thumbnail->getClientOriginalName();
            $filename = uniqid('discussion-', true) . '.' . $extension;
            $path = 'uploads/discussions/';

            $thumbnail->storeAs($path, $filename, 'public');
        }

        Discussion::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'category_discussion_id' => CategoryDiscussion::where('name', $request->get('category'))->first()->id,
            'thumbnail' => $filename ?? null,
            'original_filename' => $originalName ?? null,
            'path' => $path ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home.index')->with('success', 'Discussion créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discussion $discussion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discussion $discussion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscussionRequest $request, Discussion $discussion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion)
    {
        //
    }
}
