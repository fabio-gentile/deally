<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = ['Faq', 'Conditions générales', 'Mentions légales', 'Politique de confidentialité', 'Politique d\'utilisation des cookies', 'A propos'];

        foreach ($pages as $page) {
            if (\App\Models\Page::where('title', $page)->exists()) {
                continue;
            }

            \App\Models\Page::create([
                'title' => $page,
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec pur',
            ]);
        }
    }
}
