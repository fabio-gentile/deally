<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = \App\Models\Deal::all();
        $users = \App\Models\User::all();
        $deals->each(function ($deal) use ($users) {
            if (rand(1, 5) !== 1) { // 80% chance to generate comments
                $commentsCount = rand(1, 40);
                $user = $users->random();
                \App\Models\CommentDeal::factory()->count($commentsCount)->create([
                    'deal_id' => $deal->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
