<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Display faq page.
     *
     * @return Response
     */
    public function faq(): Response
    {
        $faq = Page::where('title', 'Faq')->first();
        return Inertia::render('Page/Page', [
            'page' => $faq,
            'title' => 'Foire aux questions'
        ]);
    }

    /**
     * Display cgu page.
     *
     * @return Response
     */
    public function cgu(): Response
    {
        $cgu = Page::where('title', 'Conditions générales')->first();
        return Inertia::render('Page/Page', [
            'page' => $cgu,
            'title' => 'Conditions générales'
        ]);
    }

    /**
     * Display legal mentions page.
     *
     * @return Response
     */
    public function legalMentions(): Response
    {
        $legalMentions = Page::where('title', 'Mentions légales')->first();
        return Inertia::render('Page/Page', [
            'page' => $legalMentions,
            'title' => 'Mentions légales'
        ]);
    }

    /**
     * Display privacy policy page.
     *
     * @return Response
     */
    public function privacyPolicy(): Response
    {
        $privacyPolicy = Page::where('title', 'Politique de confidentialité')->first();
        return Inertia::render('Page/Page', [
            'page' => $privacyPolicy,
            'title' => 'Politique de confidentialité'
        ]);
    }

    /**
     * Display cookie policy page.
     *
     * @return Response
     */
    public function cookiePolicy(): Response
    {
        $cookiePolicy = Page::where('title', 'Politique d\'utilisation des cookies')->first();
        return Inertia::render('Page/Page', [
            'page' => $cookiePolicy,
            'title' => 'Politique d\'utilisation des cookies'
        ]);
    }

    /**
     * Display about page.
     *
     * @return Response
     */
    public function about(): Response
    {
        $about = Page::where('title', 'A propos')->first();
        return Inertia::render('Page/Page', [
            'page' => $about,
            'title' => 'A propos'
        ]);
    }
}
