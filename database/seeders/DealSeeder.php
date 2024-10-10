<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\ImageDeal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'uploads/deals/';

        for ($i = 0; $i < 150; $i++) {
            $filename = uniqid('deal-', true) . '.png';
            Storage::copy('400x300.png', 'uploads/deals/' . $filename);

            $deal = Deal::factory()->create([]);

            for ($j = 0; $j < rand(0, 5); $j++) {
                ImageDeal::create([
                    'deal_id' => $deal->id,
                    'filename' => $filename,
                    'original_filename' => 'deals-' . $i . '.png',
                    'path' => $path,
                ]);
            }
        }
    }
}
