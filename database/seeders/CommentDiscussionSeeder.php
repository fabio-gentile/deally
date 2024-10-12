<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentDiscussionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discussions = \App\Models\Discussion::all();
        $discussions->each(function ($discussion) {
            if (rand(1, 5) !== 1) { // 80% chance to generate comments
                $commentsCount = rand(1, 50);
                \App\Models\CommentDiscussion::factory()->count($commentsCount)->create([
                    'discussion_id' => $discussion->id,
                ]);
            }
        });
    }
}
