<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\DepositService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $depositService;
    protected $withdrawalService;

    public function __construct(DepositService $depositService, WithdrawalService $withdrawalService)
    {
        $this->depositService = $depositService;
        $this->withdrawalService = $withdrawalService;
    }

    public function approveDeposit(Transaction $transaction)
    {
        if ($transaction->type !== 'deposit' || $transaction->status !== 'pending') {
            abort(404);
        }

        try {
            // Utiliser DepositService pour approuver
            $this->depositService->approveDeposit($transaction);
            return back()->with('success', 'Dépôt approuvé.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function rejectDeposit(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'deposit' || $transaction->status !== 'pending') {
            abort(404);
        }

        $reason = $request->filled('reason')
            ? $request->input('reason')
            : 'Raison non spécifiée';

        try {
            // Utiliser DepositService pour rejeter
            $this->depositService->rejectDeposit($transaction, $reason);
            return back()->with('success', 'Dépôt rejeté.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Approuver un retrait
     * Note: WithdrawalService gère le workflow (solde déjà déduit à la création)
     */
    public function approveWithdrawal(Transaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            abort(404);
        }

        // Utiliser le service pour approuver
        $this->withdrawalService->approveWithdrawal($transaction);

        return back()->with('success', 'Retrait approuvé.');
    }

    /**
     * Rejeter un retrait
     * Note: WithdrawalService restaure le solde
     */
    public function rejectWithdrawal(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            abort(404);
        }

        $reason = $request->input('reason', 'Raison non spécifiée');

        // Utiliser le service pour rejeter (restaure le solde)
        $this->withdrawalService->rejectWithdrawal($transaction, $reason);

        return back()->with('success', 'Retrait rejeté.');
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
