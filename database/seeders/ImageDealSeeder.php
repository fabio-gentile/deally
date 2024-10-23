<?php

namespace Database\Seeders;

use App\Models\ImageDeal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImageDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = \App\Models\Deal::all();
        $path = 'uploads/deals/';

        foreach ($deals as $deal) {
            for ($i = 0; $i < rand(0, 5); $i++) {
                $filename = uniqid('deal-', true) . '.png';
                Storage::copy('400x300.png', 'uploads/deals/' . $filename);
                ImageDeal::create([
                    'deal_id' => $deal->id,
                    'filename' => $filename,
                    'original_filename' => 'deals-' . $i . '.png',
                    'path' => $path,
                ])->save();
            }
        }
    }
}
