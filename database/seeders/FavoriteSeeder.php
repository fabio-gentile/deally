<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\Discussion;
use App\Models\Favorite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $deals = Deal::all();
        $discussions = Discussion::all();

        foreach ($users as $user) {
            // Favorites Deals
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $deals->random()->id,
                'favoritable_type' => Deal::class,
            ]);

            // Favorites Discussions
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $discussions->random()->id,
                'favoritable_type' => Discussion::class,
            ]);
        }
    }
}
