<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionCrudController extends Controller
{
    public function index()
    {
        return view('admin.transactions.all', [
            'transactions' => Transaction::with('user')->orderByDesc('created_at')->get(),
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', [
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'note' => 'nullable|string',
        ]);

        $transaction->update($data);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction mise à jour.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction supprimée.');
    }
}
