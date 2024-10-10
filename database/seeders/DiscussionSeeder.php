<?php

namespace Database\Seeders;

use App\Models\Discussion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $categories = \App\Models\CategoryDiscussion::all();
        $path = 'uploads/discussions/';

        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $category = $categories->random();
            $filename = uniqid('discussion-', true) . '.png';
            Storage::copy('600x400.png', 'uploads/discussions/' . $filename);

            Discussion::factory()->create([
                'user_id' => $user->id,
                'category_discussion_id' => $category->id,
                'thumbnail' => $filename,
                'original_filename' => 'discussion-' . $i . '.jpg',
                'path' => $path,
            ]);
        }
    }
}
