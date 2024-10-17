<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Page/List', [
            'pages' => Page::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Page/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        Page::create(request()->validate([
            'title' => 'required|unique:pages',
            'content' => 'required',
        ]));

        return redirect()->route('admin.pages.list')->with('success', 'La page ' . request('title') . ' a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return Response
     */
    public function edit(string $id): Response
    {
        $page = Page::findOrFail($id);
        return Inertia::render('Admin/Page/Edit', [
            'page' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function update(string $id): RedirectResponse
    {
        $page = Page::findOrFail($id);
        $page->update(request()->validate([
            'content' => 'required',
        ]));

        return redirect()->route('admin.pages.list')->with('success', 'La page ' . $page->title . ' a bien été modifiée.');
    }
}
