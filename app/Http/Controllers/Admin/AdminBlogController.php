<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use App\Models\CategoryDiscussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $blogs = Blog::query()->withCount('comments');

        if ($request->has('created_at')) {
            $blogs->orderBy('created_at', $request->created_at === 'desc' ? 'desc' : 'asc');
        } else {
            $blogs->orderBy('created_at', 'desc');
        }

        if ($request->has('comments') && $request->comments !== null) {
            $blogs->orderBy('comments_count', $request->comments === 'desc' ? 'desc' : 'asc');
        }

        if ($request->has('search') && $request->search !== null) {
            $blogs->where('title', 'like', '%' . $request->search . '%');
        }

        $blogs = $blogs->paginate($request->per_page ?? 10);

        return Inertia::render('Admin/Blog/List', [
            'blogs' => $blogs->getCollection()->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'comments_count' => $blog->comments_count,
                    'created_at' => $blog->created_at,
                    'is_published' => $blog->is_published,
                    'published_at' => $blog->published_at,
                ];
            }),
            'pagination' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
            'filters' => $request->all(),
        ]);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Inertia\Response
    {
        $blog = Blog::where('id', $id)->firstOrFail();

        if ($blog->image) {
            $currentThumbnail = [
                'path' => 'uploads/blog/',
                'filename' => $blog->image,
            ];
        } else {
            $currentThumbnail = null;
        }

        return Inertia::render('Admin/Blog/Edit', [
            'blog' => $blog,
            'currentThumbnail' => $currentThumbnail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBlogRequest $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $blog = Blog::where('id', $id)->firstOrFail();
//        dd($request->all());
        if ($request->get('isThumbnailRemoved')) {
            try {
                Storage::delete('uploads/blog/' . $blog->image);
                $blog->update([
                    'image' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image');
            }
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->extension();
            $filename = uniqid('blog-', true) . '.' . $extension;
            $path = 'uploads/blog/';

            $image->storeAs($path, $filename, 'public');
        }

        $blog->update([
            'title' => $request->title,
            'content' => $request->description,
            'image' => $filename ?? $blog->image,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'is_published' => $request->is_published,
            'published_at' => $request->is_published === true ? now() : null,
        ]);

        return redirect()->route('admin.blog.list')->with('success', 'Article modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
