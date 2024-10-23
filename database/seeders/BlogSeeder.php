<?php

namespace Database\Seeders;

use App\Models\Blog;
use Cocur\Slugify\Slugify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slugify = new Slugify();
        for ($i = 0; $i < 20; $i++) {
            $filename = uniqid('blog-', true) . '.png';
            Storage::copy('400x300.png', 'uploads/blog/' . $filename);

            $title = $this->faker->sentence;
            Blog::create([
               'title' => $title,
                'slug' => $slugify->slugify($title),
                'content' => $this->faker->randomHtml(),
                'image' => $filename,
                'meta_title' => 'Blog post ' . $i + 1,
                'meta_description' => $this->faker->sentence(10),
                'meta_keywords' => $this->faker->sentence(5),
                'is_published' => true,
                'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
                'published_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
            ])->save();
        }
    }
}
