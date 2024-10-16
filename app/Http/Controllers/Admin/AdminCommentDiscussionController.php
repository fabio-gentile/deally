<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommentDiscussion;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCommentDiscussionController extends Controller
{
    public function index(Request $request ,string $id): \Inertia\Response
    {
        $comments = CommentDiscussion::query();
        $comments->where('discussion_id', $id);

        if ($request->has('search') && $request->search !== null) {
            $comments->where('content', 'like', '%' . $request->search . '%')->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('created_at') && $request->created_at !== null) {
            $comments->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        }

        $comments = $comments->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Discussion/Comment/List', [
            'discussion' => Discussion::where('id', $id)->first(),
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
}
