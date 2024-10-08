<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\CommentDeal;
use App\Models\Deal;
use Illuminate\Http\Request;

class CommentDealController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $slug, CommentRequest $commentRequest): \Illuminate\Http\RedirectResponse
    {
        $validated = $commentRequest->validated();

        $deal = Deal::where('slug', $slug)->firstOrFail();

        if ($request->get('parent_id')) {
            $comment = CommentDeal::where('id', $request->get('parent_id'))->firstOrFail();
        } else {
            $comment = null;
        }

        CommentDeal::create([
            'content' => $validated['content'],
            'deal_id' => $deal->id,
            'user_id' => auth()->id(),
            'parent_id' => $comment ? $comment->id : null,
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $comment = CommentDeal::with('user')->where('id', $id)->firstOrFail();
//dd($comment, $id);
        if ($comment->user->id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        // Méthode pour supprimer le commentaire et tous ses sous-commentaires récursivement
        $this->deleteCommentAndReplies($comment);

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }

    /**
     * Supprimer le commentaire et tous ses sous-commentaires récursivement.
     */
    protected function deleteCommentAndReplies($comment): void
    {
        $comment->replies()->each(function ($reply) {
            $this->deleteCommentAndReplies($reply);
        });

        $comment->delete();
    }
}
