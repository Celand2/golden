<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    private const WITHDRAWAL_FEE_PERCENTAGE = 5;
    private const WITHDRAWAL_MIN_AMOUNT = 5000;
    private const WITHDRAWAL_START_HOUR = 7;
    private const WITHDRAWAL_END_HOUR = 21;

    /**
     * Créer une demande de retrait
     * WORKFLOW : Décrémenter immédiatement le solde (lock first)
     * SÉCURISÉ : Transaction DB atomique pour éviter race condition
     */
    public function createWithdrawalRequest(User $user, float $amount, string $recipientPhone, string $recipientName): Transaction
    {
        // Vérifier les horaires (7h-21h)
        $now = Carbon::now();
        if ($now->hour < self::WITHDRAWAL_START_HOUR || $now->hour >= self::WITHDRAWAL_END_HOUR) {
            throw new \Exception("Les retraits ne sont disponibles que de {self::WITHDRAWAL_START_HOUR}h à {self::WITHDRAWAL_END_HOUR}h.");
        }

        // Vérifier le minimum
        if ($amount < self::WITHDRAWAL_MIN_AMOUNT) {
            throw new \Exception('Montant minimum de retrait: '.self::WITHDRAWAL_MIN_AMOUNT.' FBU');
        }

        // Transaction DB atomique pour éviter race condition
        return DB::transaction(function () use ($user, $amount, $recipientPhone, $recipientName) {
            // Lock l'utilisateur pour éviter modifications concurrentes
            $user = User::lockForUpdate()->findOrFail($user->id);

            // Vérifier : MAX 1 retrait pending par user
            $existingPending = Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->exists();

            if ($existingPending) {
                throw new \Exception('Vous avez déjà une demande de retrait en attente. Veuillez attendre son approbation ou son rejet.');
            }

            // Vérifier le solde retirable (dans la transaction verrouillée)
            if ($user->withdrawable_balance < $amount) {
                throw new \Exception('Solde retirable insuffisant');
            }

            // DÉCRÉMENTER ATOMIQUEMENT le solde (lock first, vérifier et décrémenter dans la même transaction)
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->where('withdrawable_balance', '>=', $amount)
                ->decrement('withdrawable_balance', $amount);

            if (!$updated) {
                throw new \Exception('Solde insuffisant (vérification atomique échouée). Veuillez réessayer.');
            }

            // Calculer les frais 5%
            $fee = $amount * (self::WITHDRAWAL_FEE_PERCENTAGE / 100);
            $amountAfterFees = $amount - $fee;

            // Créer la transaction en pending
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'amount_after_fees' => $amountAfterFees,
                'status' => 'pending',
                'recipient_phone' => $recipientPhone,
                'recipient_name' => $recipientName,
            ]);

            // Notification au user
            Notification::create([
                'user_id' => $user->id,
                'type' => 'withdrawal_pending',
                'title' => 'Demande de retrait en attente',
                'message' => "Votre demande de retrait de {$amount} FBU est en attente d'approbation. Frais appliqués : {$fee} FBU. Montant final : {$amountAfterFees} FBU.",
            ]);

            return $transaction;
        });
    }

    /**
     * Approuver un retrait
     * Note : Le solde a déjà été déduit à la création
     */
    public function approveWithdrawal(Transaction $transaction): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return;
        }

        // Mettre à jour le statut
        $transaction->update(['status' => 'approved']);

        // Notification
        Notification::create([
            'user_id' => $transaction->user_id,
            'type' => 'withdrawal_approved',
            'title' => 'Retrait approuvé',
            'message' => "Votre retrait de {$transaction->amount} FBU a été approuvé (montant après frais: {$transaction->amount_after_fees} FBU). Les fonds seront transférés à {$transaction->recipient_phone} ({$transaction->recipient_name}).",
        ]);
    }

    /**
     * Rejeter un retrait
     * IMPORTANT : Restaurer le solde immédiatement
     * SÉCURISÉ : Transaction atomique
     */
    public function rejectWithdrawal(Transaction $transaction, string $reason): void
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return;
        }

        DB::transaction(function () use ($transaction, $reason) {
            $user = $transaction->user;
            $amount = $transaction->amount;

            // RESTAURER le montant au solde retirable (remboursement) - atomiquement
            DB::table('users')
                ->where('id', $user->id)
                ->increment('withdrawable_balance', $amount);

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
                'message' => "Votre demande de retrait de {$amount} FBU a été rejetée. Raison: {$reason}. Le montant a été restauré à votre solde retirable.",
            ]);
        });
    }
}


