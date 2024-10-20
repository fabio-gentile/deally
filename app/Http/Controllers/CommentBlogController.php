<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Blog;
use App\Models\CommentBlog;

class CommentBlogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, string $slug): \Illuminate\Http\RedirectResponse
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        if ($request->get('parent_id')) {
            $comment = CommentBlog::where('id', $request->get('parent_id'))->firstOrFail();
        } else {
            $comment = null;
        }

        CommentBlog::create([
            'content' => $request->get('content'),
            'blog_id' => $blog->id,
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
        $comment = CommentBlog::with('user')->where('id', $id)->firstOrFail();

        if ($comment->user->id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        $this->deleteCommentAndReplies($comment);

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Delete the comment and all its replies recursively.
     * @param CommentBlog $comment
     * @return void
     */
    protected function deleteCommentAndReplies(CommentBlog $comment): void
    {
        $comment->replies()->each(function ($reply) {
            $this->deleteCommentAndReplies($reply);
        });

        $comment->delete();
    }
}
