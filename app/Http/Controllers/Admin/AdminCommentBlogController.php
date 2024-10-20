<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CommentBlog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCommentBlogController extends Controller
{
    /*
     * List all comments
     * @param string $id
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(string $id, Request $request): \Inertia\Response
    {
        $comments = CommentBlog::query();
        $comments->where('blog_id', $id);

        if (!$request->has('created_at')) {
            $comments->orderBy('created_at', 'desc');
        }

        if ($request->has('search') && $request->search !== null) {
            $comments->where('content', 'like', '%' . $request->search . '%')->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $comments->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $comments = $comments->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Blog/Comment/List', [
            'blog' => Blog::where('id', $id)->first(),
            'comments' => $comments->getCollection()->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at,
                    'user' => [
                        'name' => $comment->user->name,
                        'id' => $comment->user->id,
                    ],
                ];
            }),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
            'filters' => $request->all(),
        ]);
    }

    /*
     * Show a comment
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id): \Inertia\Response
    {
        $comment = CommentBlog::where('id', $id)->with('user')->firstOrFail();

        return Inertia::render('Admin/Blog/Comment/Show', [
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'user' => [
                    'name' => $comment->user->name,
                    'id' => $comment->user->id,
                ],
            ],
            'blog' => Blog::where('id', $comment->blog_id)->first(),
        ]);
    }

    /*
     * Delete a comment
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $comment = CommentBlog::where('id', $id)->firstOrFail();
        $blog = Blog::where('id', $comment->blog_id)->firstOrFail();
        $comment->delete();

        return redirect()->route('admin.blog.comments.list', ['id' => $blog->id])->with('success', 'Commentaire supprimé avec succès');
    }
}
