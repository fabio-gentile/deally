<?php

namespace Database\Seeders;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Seeder;

class CategoryDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Cuisine',
            'Boisson',
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
            if (\App\Models\CategoryDeal::where('name', $category)->exists()) {
                continue;
            }

            \App\Models\CategoryDeal::create([
                'name' => $category,
                'slug' => $slugify->slugify($category),
            ])->save();
        }
    }
}
