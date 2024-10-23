<?php

namespace Database\Seeders;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryDiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Cuisine',
            'Électronique',
            'Mode',
            'Santé',
            'Beauté',
            'Voyage',
            'Maison',
            'Jardin',
            'Sport',
            'Divertissement',
            'Éducation',
            'Finance',
            'Assurance',
            'Automobile',
            'Affaires',
            'Services',
            'Autre',
        ];
        $slugify = new Slugify();

        // Create categories
        foreach ($categories as $category) {
            if (\App\Models\CategoryDiscussion::where('name', $category)->exists()) {
                continue;
            }

            \App\Models\CategoryDiscussion::create([
                'name' => $category,
                'slug' => $slugify->slugify($category),
            ])->save();
        }
    }
}
