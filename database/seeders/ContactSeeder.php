<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            "Proposer une amélioration",
            "Signaler un contenu inapproprié",
            "Offre commerciale",
            "Signaler un bug",
            "Problème de connexion",
            "Autre",
        ];

        for ($i = 0; $i < 50; $i++) {
            Contact::create([
                'name' => 'Contact ' . $i,
                'email' => 'contact' . $i . '@example.com',
                'message' => 'Ceci est un message test numéro ' . $i,
                'subject' => $subjects[array_rand($subjects)],
            ]);
        }
    }
}
