<?php

namespace App\Http\Controllers;

use App\Models\CategoryDiscussion;
use App\Models\CommentDiscussion;
use App\Models\Discussion;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\UpdateDiscussionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DiscussionController extends Controller
{
    use \App\Traits\UpdateDiscussion;
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
    public function create(): \Inertia\Response
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
    public function show(string $slug): \Inertia\Response
    {
        $discussion = Discussion::where('slug', $slug)->with('user')->firstOrFail();

        $user = auth()->user();
        if ($user) {
            // associate the voteDeals to the deal
            $discussion->user_favorite = !!$discussion->favorites->first();
        }

        $similarDiscussions = Discussion::where('category_discussion_id', $discussion->category_discussion_id)
            ->with('user:id,name,avatar')->withCount('comments')
            ->where('id', '!=', $discussion->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $allComments = $discussion->comments()
            ->with([
                'replies' => function ($query) {
                    $query->orderBy('created_at', 'desc'); // Sort direct replies by created_at
                },
                'user', // Load the user for each comment
                'replies.user', // Load the user for each reply,
                'replies.answerToUser' // Load the 'answer_to' user for each reply
            ])
            ->whereNull('parent_id') // Only get top-level comments
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($allComments as $comment) {
            $this->loadAllReplies($comment); // Recursively load replies
        }

        return Inertia::render('Discussion/Show', [
            'discussion' => $discussion,
            'category' => CategoryDiscussion::where('id', $discussion->category_discussion_id)->first()->name,
            'similarDiscussions' => $similarDiscussions ?? [],
            'allComments' => $allComments,
            'allCommentsCount' => CommentDiscussion::where('discussion_id', $discussion->id)->count(),
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
    public function update(UpdateDiscussionRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $discussion = Discussion::where('id', $id)->firstOrFail();
        Gate::authorize('update', $discussion);

        $this->handleUpdateDiscussion($request, $discussion);

        return redirect()->route('home.index')->with('success', 'Discussion modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion, int $id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $discussion = Discussion::where('id', $id)->firstOrFail();
        Gate::authorize('delete', $discussion);

        if ($discussion->thumbnail) {
            try {
                Storage::delete($discussion->path . $discussion->thumbnail);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
            }
        }

        $discussion->delete();

        $request->session()->flash('success', 'Discussion supprimée avec succès.');
        return redirect()->route('home.index');
    }

    /**
     *  Load all replies for a comment.
     * @param $comment
     * @return void
     */
    protected function loadAllReplies($comment): void
    {
        $comment->replies->each(function ($reply) {
            // Load the user corresponding to the 'answer_to' field
            $reply->load(['replies' => function ($query) {
                $query->orderBy('created_at', 'desc'); // Sort replies by created_at
            }, 'answerToUser', 'user']); // Load the user referenced by 'answer_to'

            $this->loadAllReplies($reply); // Recursively load sub-replies
        });
    }
}
