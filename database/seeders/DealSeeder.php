<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\ImageDeal;
use Cocur\Slugify\Slugify;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($i = 0; $i < 150; $i++) {
            $price = rand(0, 5) === 0 ? null : rand(0, 500);
            $title = $faker->sentence;
            Deal::create([
                'title' => $title,
                'slug' => $slugify->slugify($title),
                'description' => $faker->paragraph(rand(3, 10)),
                'deal_url' => $faker->url,
                'votes' => rand(-10, 100),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'start_date' => $faker->dateTimeBetween('-7 days', 'now'),
                'expiration_date' => $faker->dateTimeBetween('-3 days', '+14 days'),
                'user_id' => \App\Models\User::all()->random()->id,
                'category_deal_id' => \App\Models\CategoryDeal::all()->random()->id,
                'price' => $price,
                'original_price' => $price ? $price + rand(50, 150) : null,
                'promo_code' => rand(0, 5) === 0 ? null : $faker->word,
            ])->save();
        }
    }
}
