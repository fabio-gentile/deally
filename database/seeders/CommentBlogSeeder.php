<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = \App\Models\Blog::all();
        $blogs->each(function ($blog) {
            if (rand(1, 10) !== 1) { // 10% chance to generate comments
                $commentsCount = rand(1, 10);
                \App\Models\CommentBlog::factory()->count($commentsCount)->create([
                    'blog_id' => $blog->id,
                ]);
            }
        });
    }
}
