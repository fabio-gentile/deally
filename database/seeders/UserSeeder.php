<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'uploads/avatar/';
        for ($i = 0; $i < 200; $i++) {
            $filename = uniqid('avatar-', true) . '.png';
            Storage::copy('avatar.png', $path . $filename);

            User::factory()->create([
                'avatar' => $filename,
            ]);
        }
    }
}
