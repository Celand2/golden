<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;

class WithdrawalService
{
    /**
     * Créer une demande de retrait
     */
    public function createWithdrawalRequest(User $user, float $amount, string $recipientPhone, string $recipientName): Transaction
    {
        // Vérifier le minimum
        if ($amount < 5000) {
            throw new \Exception('Montant minimum de retrait: 5000 FBU');
        }

        // Vérifier le solde retirable
        if ($user->withdrawable_balance < $amount) {
            throw new \Exception('Solde retirable insuffisant');
        }

        // Créer la transaction en pending (NE PAS déduire le solde ici)
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'status' => 'pending',
            'recipient_phone' => $recipientPhone,
            'recipient_name' => $recipientName,
        ]);

        // Notification au user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_pending',
            'title' => 'Demande de retrait en attente',
            'message' => "Votre demande de retrait de {$amount} FBU est en attente d'approbation.",
        ]);

        return $transaction;
    }

    /**
     * Approuver un retrait
     */
    public function approveWithdrawal(Transaction $transaction): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status === 'approved') {
            return;
        }

        $user = $transaction->user;
        $amount = $transaction->amount;

        // Vérifier le solde (sécurité)
        if ($user->withdrawable_balance < $amount) {
            throw new \Exception('Solde retirable insuffisant');
        }

        // Déduire le solde retirable
        $user->decrement('withdrawable_balance', $amount);

        // Mettre à jour le statut
        $transaction->update(['status' => 'approved']);

        // Notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_approved',
            'title' => 'Retrait approuvé',
            'message' => "Votre retrait de {$amount} FBU a été approuvé. Les fonds seront transférés à {$transaction->recipient_phone}.",
        ]);
    }

    /**
     * Rejeter un retrait
     */
    public function rejectWithdrawal(Transaction $transaction, string $reason): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status === 'rejected') {
            return;
        }

        $user = $transaction->user;

        // Mettre à jour le statut et la raison
        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_rejected',
            'title' => 'Retrait rejeté',
            'message' => "Votre retrait a été rejeté. Raison: {$reason}",
        ]);
    }
}
