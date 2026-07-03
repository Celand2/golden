<?php

namespace App\Services;

use App\Models\LumicashAccount;

class LumicashService
{
    /**
     * Récupérer les données Lumicash depuis la BD
     */
    public static function get(): array
    {
        $account = LumicashAccount::first();

        if ($account) {
            return [
                'phone' => $account->phone,
                'name' => $account->name,
            ];
        }

        // Fallback si aucun compte trouvé
        return [
            'phone' => env('LUMICASH_PHONE', ''),
            'name' => env('LUMICASH_NAME', ''),
        ];
    }

    /**
     * Mettre à jour les données Lumicash dans la BD
     */
    public static function set(string $phone, string $name): void
    {
        $account = LumicashAccount::first();

        if ($account) {
            $account->update([
                'phone' => $phone,
                'name' => $name,
            ]);
        } else {
            LumicashAccount::create([
                'phone' => $phone,
                'name' => $name,
            ]);
        }
    }
}

