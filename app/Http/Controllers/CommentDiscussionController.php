<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\CommentDiscussion;
use App\Models\Discussion;

class CommentDiscussionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, string $slug): \Illuminate\Http\RedirectResponse
    {
        $discussion = Discussion::where('slug', $slug)->firstOrFail();

        if ($request->get('parent_id')) {
            $comment = CommentDiscussion::where('id', $request->get('parent_id'))->firstOrFail();
        } else {
            $comment = null;
        }

        CommentDiscussion::create([
            'content' => $request->get('content'),
            'discussion_id' => $discussion->id,
            'user_id' => auth()->id(),
            'answer_to' => $request->get('answer_to') ?? null,
            'parent_id' => $comment ? $comment->id : null,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $comment = CommentDiscussion::with('user')->where('id', $id)->firstOrFail();

        if ($comment->user->id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        // Méthode pour supprimer le commentaire et tous ses sous-commentaires récursivement
        $this->deleteCommentAndReplies($comment);

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Supprimer le commentaire et tous ses sous-commentaires récursivement.
     * @param CommentDiscussion $comment
     * @return void
     */
    protected function deleteCommentAndReplies(CommentDiscussion $comment): void
    {
        $comment->replies()->each(function ($reply) {
            $this->deleteCommentAndReplies($reply);
        });

        $comment->delete();
    }
}
