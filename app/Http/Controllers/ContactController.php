<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

class ContactController extends Controller
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
    public function create(): \Inertia\Response
    {
        return Inertia::render('Contact', [
            'recaptchaKey' => config('services.recaptcha.key'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request): \Illuminate\Http\RedirectResponse
    {
        // Honeypot to prevent spam
        if (!empty($request->input('website'))) {
            return back()->with('error', 'Erreur !');
        }

        // Limit the number of requests to 1 every 15 minutes per IP
        if (RateLimiter::tooManyAttempts('contact-form:'.$request->ip(), 1)) {
            $seconds = RateLimiter::availableIn('contact-form:'.$request->ip());
            $minutes = floor($seconds / 60);
            $string = $minutes > 0 ? $minutes.' minute'.($minutes > 1 ? 's' : '') : '';
            return back()->with('error', 'Vous avez déjà envoyé un message. Veuillez réessayer dans '.$string);
        }

        RateLimiter::increment('contact-form:'.$request->ip(), 15 * 60);

        \App\Models\Contact::create($request->validated());

        $emailData = [
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
        ];

        // Send email
        Mail::to(env('CONTACT_FORM_EMAIL'))->send(new \App\Mail\ContactMail($emailData));

        return back()->with('success', 'Votre message a bien été envoyé !');
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
    public function destroy(string $id)
    {
        //
    }
}
