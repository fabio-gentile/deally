<?php

namespace App\Http\Controllers;

use App\Models\CategoryDiscussion;
use App\Models\Discussion;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\UpdateDiscussionRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
    public function show(string $slug)
    {
        $discussion = Discussion::where('slug', $slug)->with('user')->firstOrFail();
        $similarDeals = Discussion::where('category_discussion_id', $discussion->category_discussion_id)
            ->where('id', '!=', $discussion->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return Inertia::render('Discussion/Show', [
            'discussion' => $discussion,
            'category' => CategoryDiscussion::where('id', $discussion->category_discussion_id)->first()->name,
            'similarDiscussions' => $similarDeals ?? [],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug): \Inertia\Response
    {
        $discussion = Discussion::where('slug', $slug)->firstOrFail();
        Gate::authorize('update', $discussion);

        if ($discussion->thumbnail) {
            $currentThumbnail = [
                'path' => $discussion->path,
                'filename' => $discussion->thumbnail,
                'originalName' => $discussion->original_filename,
            ];
        } else {
            $currentThumbnail = null;
        }

        return Inertia::render('Discussion/Edit', [
            'discussion' => $discussion,
            'currentCategory' => CategoryDiscussion::where('id', $discussion->category_discussion_id)->first()->name,
            'categories' => CategoryDiscussion::orderBy('name', 'asc')->get(),
            'currentThumbnail' => $currentThumbnail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscussionRequest $request, int $id)
    {
        $discussion = Discussion::where('id', $id)->firstOrFail();
//        dd($request->all());
        Gate::authorize('update', $discussion);

        if ($request->get('isThumbnailRemoved') === true) {
            try {
                Storage::delete($discussion->path . $discussion->thumbnail);
                $discussion->update([
                    'thumbnail' => null,
                    'original_filename' => null,
                    'path' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
            }
        }

        if ($request->hasFile('thumbnail')) {
//            dd('thumbnail');
            $thumbnail = $request->file('thumbnail');
            $extension = $thumbnail->extension();
            $originalName = $thumbnail->getClientOriginalName();
            $filename = uniqid('discussion-', true) . '.' . $extension;
            $path = 'uploads/discussions/';

            $thumbnail->storeAs($path, $filename, 'public');
        }

        $discussion->update([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'category_discussion_id' => CategoryDiscussion::where('name', $request->get('category'))->first()->id,
            'thumbnail' => $filename ?? $discussion->thumbnail,
            'original_filename' => $originalName ?? $discussion->original_filename,
            'path' => $path ?? $discussion->path,
        ]);

        return redirect()->route('home.index')->with('success', 'Discussion modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion)
    {
        //
    }
}
