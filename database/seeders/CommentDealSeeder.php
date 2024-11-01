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
        $deals->each(function ($deal) {
            if (rand(1, 10) !== 1) { // 10% chance to generate comments
                $commentsCount = rand(1, 10);
                \App\Models\CommentDeal::factory()->count($commentsCount)->create([
                    'deal_id' => $deal->id,
                ]);
            }
        });
    }
}
