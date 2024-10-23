<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GiveRoleAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:give-role-admin {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attribuer le rôle administrateur à un utilisateur';

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'user' => 'Quelle est l\'adresse email de l\'utilisateur ?',
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = $this->argument('user');
        $user = User::where('email', $email)->first();

        if ($user === null) {
            $this->error('Aucun utilisateur trouvé avec cette adresse email.');
            return;
        }

        $user->assignRole('admin');

        $this->info('Le rôle administrateur a été attribué à l\'utilisateur.');
    }
}
