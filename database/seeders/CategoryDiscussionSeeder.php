<?php

namespace Database\Seeders;

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
            'Food',
            'Drink',
            'Electronics',
            'Fashion',
            'Health',
            'Beauty',
            'Travel',
            'Home',
            'Garden',
            'Sport',
            'Entertainment',
            'Education',
            'Finance',
            'Insurance',
            'Automotive',
            'Business',
            'Services',
            'Other',
        ];

        // Create categories
        foreach ($categories as $category) {
            \App\Models\CategoryDiscussion::factory()->create([
                'name' => $category,
            ]);
        }
    }
}
