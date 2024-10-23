<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategoryDealSeeder::class,
            CategoryDiscussionSeeder::class,
            DiscussionSeeder::class,
            DealSeeder::class,
            ImageDealSeeder::class,
            BlogSeeder::class,
            PageSeeder::class,
            CommentBlogSeeder::class,
            CommentDealSeeder::class,
            CommentDiscussionSeeder::class,
            ReportSeeder::class,
            FavoriteSeeder::class,
            ContactSeeder::class
        ]);

        // create role admin
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    }
}
