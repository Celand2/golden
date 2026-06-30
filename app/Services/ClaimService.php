<?php

namespace App\Services;

use App\Models\DailyClaim;
use App\Models\Investment;
use App\Models\Notification;
use App\Models\Transaction;

class ClaimService
{
    /**
     * Effectuer un claim pour un investment
     */
    public function claimGains(Investment $investment): void
    {
        // Vérifier les conditions
        if ($investment->status !== 'active') {
            throw new \Exception('Cet investment n\'est pas actif');
        }

        if ($investment->accumulated_gains <= 0) {
            throw new \Exception('Aucun gain à réclamer');
        }

        $user = $investment->user;
        $claimedAmount = $investment->accumulated_gains;

        // Transférer accumulated_gains vers withdrawable_balance
        $user->increment('withdrawable_balance', $claimedAmount);

        // Incrémenter total_claimed
        $investment->increment('total_claimed', $claimedAmount);

        // Remettre accumulated_gains à 0
        $investment->update(['accumulated_gains' => 0]);

        // Créer une entrée dans daily_claims
        DailyClaim::create([
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'amount_claimed' => $claimedAmount,
        ]);

        // Créer une transaction de type claim
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'claim',
            'amount' => $claimedAmount,
            'status' => 'approved',
        ]);

        // Notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'daily_claim',
            'title' => 'Gains réclamés',
            'message' => "Vous avez réclamé {$claimedAmount} FBU du plan {$investment->vipPlan->name}. Ces fonds sont maintenant dans votre portefeuille retirable.",
        ]);
    }
}
