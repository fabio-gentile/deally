<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CommentBlog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $posts = Blog::query();
        $first = $posts->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->first();

        $posts = $posts->where('is_published', true)
            ->whereNotIn('id', [$first->id]) // Exclude the first post, needed for pagination to work
            ->orderBy('published_at', 'desc')
            ->paginate(9);

//        dd($posts->items());
        return Inertia::render('Blog/List', [
            'firstPost' => $first,
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
            'filters' => $request->all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $allComments = $blog->comments()
            ->with([
                'replies' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'user',
                'replies.user',
                'replies.answerToUser'
            ])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($allComments as $comment) {
            $this->loadAllReplies($comment);
        }

        return Inertia::render('Blog/Show', [
            'blog' => $blog,
            'allComments' => $allComments,
            'allCommentsCount' => CommentBlog::where('blog_id', $blog->id)->count(),
        ]);
    }

    /**
     *  Load all replies for a comment.
     * @param $comment
     * @return void
     */
    protected function loadAllReplies($comment): void
    {
        $comment->replies->each(function ($reply) {
            $reply->load(['replies' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }, 'answerToUser', 'user']);

            $this->loadAllReplies($reply);
        });
    }
}
