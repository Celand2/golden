<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DepositService
{
    /**
     * Approuver un dépôt et distribuer les commissions de parrainage
     */
    public function approveDeposit(Transaction $transaction): void
    {
        if ($transaction->type !== 'deposit' || $transaction->status === 'approved') {
            return;
        }

        DB::transaction(function () use ($transaction) {
            $user = $transaction->user;
            $amount = $transaction->amount;

            // Créditer wallet_balance
            $user->increment('wallet_balance', $amount);

            // Mettre à jour le statut de la transaction
            $transaction->update(['status' => 'approved']);

            // Créer notification de dépôt approuvé
            Notification::create([
                'user_id' => $user->id,
                'type' => 'deposit_approved',
                'title' => 'Dépôt approuvé',
                'message' => "Votre dépôt de {$amount} FBU a été approuvé. Votre solde portefeuille est maintenant de {$user->wallet_balance} FBU.",
            ]);

            // Distribuer les commissions de parrainage
            $this->distributeReferralCommissions($user, $amount, $transaction);

            // Vérifier si l'utilisateur L1 atteint 30 filleuls
            $this->checkPremiumPromotion($user);
        });
    }

    /**
     * Rejeter un dépôt
     */
    public function rejectDeposit(Transaction $transaction, ?string $reason = null): void
    {
        if ($transaction->type !== 'deposit' || $transaction->status === 'rejected') {
            return;
        }
        $reason = $reason ?? 'Aucun motif fourni';

        $user = $transaction->user;

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Créer notification de rejet
        Notification::create([
            'user_id' => $user->id,
            'type' => 'deposit_rejected',
            'title' => 'Dépôt rejeté',
            'message' => "Votre dépôt a été rejeté. Raison: {$reason}",
        ]);
    }

    /**
     * Distribuer les commissions de parrainage L1/L2/L3
     */
    private function distributeReferralCommissions(User $user, float $amount, Transaction $transaction): void
    {
        $rates = [
            1 => 0.09, // L1: 9%
            2 => 0.02, // L2: 2%
            3 => 0.01, // L3: 1%
        ];

        $upline = $user->getReferralUpline(3);

        foreach ($upline as $level => $referrer) {
            if (! isset($rates[$level])) {
                continue;
            }

            $commissionAmount = $amount * $rates[$level];

            // Créditer withdrawable_balance du parrain
            $referrer->increment('withdrawable_balance', $commissionAmount);

            // Créer l'enregistrement de commission
            ReferralCommission::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'transaction_id' => $transaction->id,
                'level' => $level,
                'rate' => $rates[$level] * 100, // Convertir en pourcentage (9, 2, 1)
                'amount' => $commissionAmount,
            ]);

            // Créer notification de commission reçue
            Notification::create([
                'user_id' => $referrer->id,
                'type' => 'commission_received',
                'title' => "Commission L{$level} reçue",
                'message' => "Vous avez reçu une commission de {$commissionAmount} FBU (niveau {$level}) du dépôt de {$user->name}.",
            ]);
        }
    }

    /**
     * Vérifier si le parrain L1 atteint 30 filleuls et passer à premium
     */
    private function checkPremiumPromotion(User $user): void
    {
        $referrer = $user->referrer;

        if (! $referrer) {
            return;
        }

        // Incrémenter referral_count du parrain L1 seulement si c'est un nouveau filleul
        // (on suppose que c'est appelé une fois par inscription, pas par dépôt)
        if ($referrer->role === 'standard' && $referrer->referral_count >= 30) {
            $referrer->update(['role' => 'premium']);

            Notification::create([
                'user_id' => $referrer->id,
                'type' => 'premium_upgrade',
                'title' => 'Félicitations!',
                'message' => 'Vous êtes passé au statut Premium après avoir atteint 30 filleuls directs!',
            ]);
        }
    }
}