<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ReferralCommission;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function approveDeposit(Transaction $transaction)
    {
        if ($transaction->type !== 'deposit' || $transaction->status !== 'pending') {
            abort(404);
        }

        $transaction->status = 'approved';
        $transaction->save();

        $user = $transaction->user;
        $user->wallet_balance += $transaction->amount;
        $user->save();

        $this->distributeReferralCommissions($user, $transaction);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'deposit_approved',
            'title' => 'Dépôt approuvé',
            'message' => "Votre dépôt de {$transaction->amount} FBU a été approuvé.",
        ]);

        return back()->with('success', 'Dépôt approuvé.');
    }

    public function rejectDeposit(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'deposit' || $transaction->status !== 'pending') {
            abort(404);
        }

        $reason = $request->input('reason', 'Raison non spécifiée');

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        Notification::create([
            'user_id' => $transaction->user_id,
            'type' => 'deposit_rejected',
            'title' => 'Dépôt rejeté',
            'message' => "Votre dépôt a été rejeté. Raison: {$reason}",
        ]);

        return back()->with('success', 'Dépôt rejeté.');
    }

    public function approveWithdrawal(Transaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            abort(404);
        }

        $user = $transaction->user;

        // Vérifier le solde retirable (pas wallet_balance)
        if ($user->withdrawable_balance < $transaction->amount) {
            return back()->withErrors(['amount' => 'Solde retirable insuffisant.']);
        }

        // Déduire du solde retirable
        $user->decrement('withdrawable_balance', $transaction->amount);

        $transaction->update(['status' => 'approved']);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_approved',
            'title' => 'Retrait approuvé',
            'message' => "Votre retrait de {$transaction->amount} FBU a été approuvé. Les fonds seront transférés à {$transaction->recipient_phone}.",
        ]);

        return back()->with('success', 'Retrait approuvé.');
    }

    public function rejectWithdrawal(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            abort(404);
        }

        $reason = $request->input('reason', 'Raison non spécifiée');

        $transaction->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        Notification::create([
            'user_id' => $transaction->user_id,
            'type' => 'withdrawal_rejected',
            'title' => 'Retrait rejeté',
            'message' => "Votre demande de retrait a été rejetée. Raison: {$reason}",
        ]);

        return back()->with('success', 'Retrait rejeté.');
    }

    protected function distributeReferralCommissions(User $user, Transaction $transaction): void
    {
        $rates = [1 => 9, 2 => 2, 3 => 1];
        $referrer = $user->referrer;

        foreach ($rates as $level => $rate) {
            if (! $referrer) {
                break;
            }

            $amount = round($transaction->amount * $rate / 100, 2);
            $referrer->wallet_balance += $amount;
            $referrer->save();

            ReferralCommission::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'transaction_id' => $transaction->id,
                'level' => $level,
                'rate' => $rate,
                'amount' => $amount,
            ]);

            Notification::create([
                'user_id' => $referrer->id,
                'type' => 'commission_received',
                'title' => 'Commission de parrainage',
                'message' => "Vous avez reçu une commission de {$amount} FBU.",
            ]);

            if ($level === 1) {
                $referrer->referral_count += 1;
                if ($referrer->referral_count >= 30 && $referrer->role === 'standard') {
                    $referrer->role = 'premium';
                }
                $referrer->save();
            }

            $referrer = $referrer->referrer;
        }
    }

    public function showWithdrawals()
    {
        return view('admin.withdrawals.index', [
            'pendingWithdrawals' => Transaction::where('type', 'withdrawal')
                ->where('status', 'pending')
                ->with('user')
                ->orderByDesc('created_at')
                ->get(),
            'approvedWithdrawals' => Transaction::where('type', 'withdrawal')
                ->where('status', 'approved')
                ->with('user')
                ->orderByDesc('updated_at')
                ->get(),
            'rejectedWithdrawals' => Transaction::where('type', 'withdrawal')
                ->where('status', 'rejected')
                ->with('user')
                ->orderByDesc('updated_at')
                ->get(),
        ]);
    }

    public function showDeposits()
    {
        return view('admin.deposits.index', [
            'pendingDeposits' => Transaction::where('type', 'deposit')
                ->where('status', 'pending')
                ->with('user')
                ->orderByDesc('created_at')
                ->get(),
            'approvedDeposits' => Transaction::where('type', 'deposit')
                ->where('status', 'approved')
                ->with('user')
                ->orderByDesc('updated_at')
                ->get(),
            'rejectedDeposits' => Transaction::where('type', 'deposit')
                ->where('status', 'rejected')
                ->with('user')
                ->orderByDesc('updated_at')
                ->get(),
        ]);
    }
}
