<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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

        $request->user()->transactions()->create([
            'type' => 'deposit',
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'phone' => $lumicash['phone'] ?? null,
            'provider' => 'lumicash',
            'payment_proof' => $proofPath,
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
            'phone' => 'required|string',
        ]);

        $request->user()->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'phone' => $request->input('phone'),
            'provider' => 'lumicash',
        ]);

        return back()->with('success', 'Votre demande de retrait a été soumise.');
    }
}
