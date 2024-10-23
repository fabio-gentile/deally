<?php

namespace Database\Seeders;

use App\Models\Deal;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reason = [
            'Spam',
            'Inapproprié / Injurieux / Vulgaire',
            'Autre'
        ];
        $users = User::all();
        $deals = Deal::all();

        foreach ($users as $user) {
            Report::create([
                'description' => 'Description signalement',
                'user_id' => $user->id,
                'reason' => $reason[array_rand($reason)],
                'reportable_type' => Deal::class,
                'reportable_id' => $deals->random()->id,
            ]);
        }
    }
}
