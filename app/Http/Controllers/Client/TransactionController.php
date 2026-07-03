<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\LumicashService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected WithdrawalService $withdrawalService;

    public function __construct(WithdrawalService $withdrawalService)
    {
        $this->withdrawalService = $withdrawalService;
    }
{
    public function showDepositForm(Request $request)
    {
        return view('client.deposit', [
            'user' => $request->user(),
            'lumicash' => LumicashService::get(),
        ]);
    }

    public function createDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:30000',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Vérifier : MAX 1 dépôt pending par user
        $existingPendingDeposit = $request->user()->transactions()
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->exists();

        if ($existingPendingDeposit) {
            return back()->withErrors(['deposit' => 'Vous avez déjà une demande de dépôt en attente. Veuillez attendre son approbation ou son rejet.']);
        }

        $lumicash = LumicashService::get();
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $transaction = $request->user()->transactions()->create([
            'type' => 'deposit',
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'phone' => $lumicash['phone'] ?? null,
            'provider' => 'lumicash',
            'payment_proof' => $proofPath,
        ]);

        Notification::create([
            'user_id' => $request->user()->id,
            'type' => 'deposit_requested',
            'title' => 'Dépôt en attente',
            'message' => "Votre dépôt de {$transaction->amount} FBU est en attente de validation.",
        ]);

        return back()->with('success', 'Votre demande de dépôt a été soumise.');
    }

    public function showWithdrawForm(Request $request)
    {
        return view('client.withdraw', [
            'user' => $request->user(),
        ]);
    }

    public function createWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string',
        ]);

        try {
            // Utiliser WithdrawalService pour tous les contrôles
            $this->withdrawalService->createWithdrawalRequest(
                user: $request->user(),
                amount: (float) $request->input('amount'),
                recipientPhone: $request->input('recipient_phone'),
                recipientName: $request->input('recipient_name')
            );

            return back()->with('success', 'Votre demande de retrait a été soumise.');
        } catch (\Exception $e) {
            return back()->withErrors(['withdrawal' => $e->getMessage()]);
        }
    }
}
