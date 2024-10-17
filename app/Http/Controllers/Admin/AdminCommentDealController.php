<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommentDeal;
use App\Models\Deal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCommentDealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request ,string $id): \Inertia\Response
    {
        $comments = CommentDeal::query();
        $comments->where('deal_id', $id);

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

        return Inertia::render('Admin/Deal/Comment/List', [
            'deal' => Deal::where('id', $id)->first(),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Inertia\Response
    {
        $comment = CommentDeal::where('id', $id)->with('user')->firstOrFail();

        return Inertia::render('Admin/Deal/Comment/Show', [
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'user' => [
                    'name' => $comment->user->name,
                    'id' => $comment->user->id,
                ],
            ],
            'deal' => Deal::where('id', $comment->deal_id)->first(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $comment = CommentDeal::where('id', $id)->firstOrFail();
        $deal = Deal::where('id', $comment->deal_id)->firstOrFail();
        $comment->delete();

        return redirect()->route('admin.deal.comments.list', ['id' => $deal->id])->with('success', 'Commentaire supprimé avec succès');
    }
}
