<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $posts = Blog::query();
        $first = $posts->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->first();

        $posts = $posts->where('is_published', true)
            ->whereNotIn('id', [$first->id]) // Exclude the first post
            ->orderBy('published_at', 'desc')
            ->paginate(9);

//        dd($posts->items());
        return Inertia::render('Blog/List', [
            'firstPost' => $first,
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
            'filters' => $request->all()
        ]);
    }
}
