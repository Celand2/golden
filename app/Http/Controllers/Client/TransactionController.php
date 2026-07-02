<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\LumicashService;
use Illuminate\Http\Request;

class TransactionController extends Controller
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

        $transaction = $request->user()->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'phone' => $request->input('recipient_phone'),
            'recipient_name' => $request->input('recipient_name'),
            'provider' => 'lumicash',
        ]);

        Notification::create([
            'user_id' => $request->user()->id,
            'type' => 'withdrawal_requested',
            'title' => 'Retrait en attente',
            'message' => "Votre demande de retrait de {$transaction->amount} FBU est en cours de traitement.",
        ]);

        return back()->with('success', 'Votre demande de retrait a été soumise.');
    }
}
