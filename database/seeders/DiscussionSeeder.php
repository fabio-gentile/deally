<?php

namespace Database\Seeders;

use App\Models\CategoryDiscussion;
use App\Models\Discussion;
use App\Models\User;
use Cocur\Slugify\Slugify;
use Faker\Factory;
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
        $faker = Factory::create('fr_FR');
        $path = 'uploads/discussions/';
        $slugify = new Slugify();

        for ($i = 0; $i < 150; $i++) {
            $filename = uniqid('discussion-', true) . '.png';
            Storage::copy('400x300.png', 'uploads/discussions/' . $filename);
            $title = $faker->sentence;

            Discussion::create([
                'title' => $title,
                'slug' => $slugify->slugify($title),
                'content' => $faker->paragraph(rand(3, 10)),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'user_id' => User::all()->random()->id,
                'category_discussion_id' => CategoryDiscussion::all()->random()->id,
                'thumbnail' => $filename,
                'original_filename' => 'discussion-' . $i . '.jpg',
                'path' => $path,
            ])->save();
        }
    }
}
