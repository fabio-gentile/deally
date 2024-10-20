<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminBlogController extends Controller
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
        return Inertia::render('Admin/Blog/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->extension();
            $filename = uniqid('blog-', true) . '.' . $extension;
            $path = 'uploads/blog/';

            $image->storeAs($path, $filename, 'public');
        }

        Blog::create([
            'title' => $request->title,
            'content' => $request->description,
            'image' => $filename,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'is_published' => $request->is_published,
            'published_at' => $request->is_published === true ? now() : null,
        ]);

        return redirect()->route('admin.blog.list')->with('success', 'Article créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
