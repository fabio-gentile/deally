<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscribeNewsletterController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $request->validate([
            'newsletter' => 'required|boolean',
            'blog_notification' => 'required|boolean',
        ]);
        $preferences = $user->preferences;

        $preferences['newsletter'] = $request->newsletter;
        $preferences['blog_notification'] = $request->blog_notification;
        $user->preferences = $preferences;
        $user->save();

        return redirect()->back()->with('success', 'Vos préférences ont été mises à jour avec succès');
    }
}
