<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserAdmin extends Command
{
    protected $signature = 'user:set-admin {identifier : ID ou numéro de téléphone de l\'utilisateur}';
    protected $description = 'Rendre un utilisateur admin par ID ou numéro de téléphone';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        // Chercher par ID d'abord
        $user = User::find($identifier);

        // Si pas trouvé, chercher par téléphone
        if (!$user) {
            $user = User::where('phone', $identifier)->first();
        }

        if (!$user) {
            $this->error("Utilisateur non trouvé: {$identifier}");
            return self::FAILURE;
        }

        $user->update(['role' => 'admin']);

        $this->info("✓ {$user->name} ({$user->phone}) est maintenant ADMIN");
        return self::SUCCESS;
    }
}
