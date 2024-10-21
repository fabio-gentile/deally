<?php

namespace App\Http\Controllers;

use App\Models\CommentBlog;
use App\Models\CommentDeal;
use App\Models\CommentDiscussion;
use App\Models\Deal;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        switch ($request->input('type')) {
            case 'deal':
                $request->validate([
                    'reason' => ['required', 'in:Spam,Inapproprié,Autre,Informations manquantes,Mauvaise catégorie'],
                    'description' => ['nullable', 'string', 'max:1024'],
                    'id' => ['required', 'integer', 'exists:deals,id'],
                ]);
                $reportable = Deal::findOrFail($request->input('id'));
                break;
            case 'discussion':
                $request->validate([
                    'reason' => ['required', 'in:Spam,Inapproprié,Autre,Mauvaise catégorie'],
                    'description' => ['nullable', 'string', 'max:1024'],
                    'id' => ['required', 'integer', 'exists:discussions,id'],
                ]);
                $reportable = Discussion::findOrFail($request->input('id'));
                break;
            case 'comment_deal':
                $request->validate([
                    'reason' => ['required', 'in:Spam,Inapproprié,Autre'],
                    'description' => ['nullable', 'string', 'max:1024'],
                    'id' => ['required', 'integer', 'exists:comment_deals,id'],
                ]);
                $reportable = CommentDeal::findOrFail($request->input('id'));
                break;
            case 'comment_discussion':
                $request->validate([
                    'reason' => ['required', 'in:Spam,Inapproprié,Autre'],
                    'description' => ['nullable', 'string', 'max:1024'],
                    'id' => ['required', 'integer', 'exists:comment_discussions,id'],
                ]);
                $reportable = CommentDiscussion::findOrFail($request->input('id'));
                break;
            case 'comment_blog':
                $request->validate([
                    'reason' => ['required', 'in:Spam,Inapproprié,Autre'],
                    'description' => ['nullable', 'string', 'max:1024'],
                    'id' => ['required', 'integer', 'exists:comment_blogs,id'],
                ]);
                $reportable = CommentBlog::findOrFail($request->input('id'));
                break;
            default:
                return back()->with('error', 'Erreur lors du signalement');
        }

        $user = Auth::user();
        $existingReport = $reportable->reports()->where('user_id', $user->id)->first();
        if ($existingReport) {
            return back()->with('error', 'Vous avez déjà effectué un signalement.');
        }

        $reportable->reports()->create([
            'user_id' => $user->id,
            'reason' => $request->input('reason'),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Signalement effectué avec succès');
   }
}
