@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Demandes de transaction</h1>
                <p class="mt-2 text-sm text-slate-500">Approuvez ou rejetez les dépôts et retraits en attente.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au tableau</a>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Dépôts en attente</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Preuve</th>
                        <th class="px-4 py-3 text-left font-semibold">Numéro Lumicash</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendingDeposits as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">
                                @if ($transaction->payment_proof)
                                    <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="text-emerald-600 hover:underline">Voir preuve</a>
                                @else
                                    <span class="text-slate-500">Aucune</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $transaction->phone }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                                    <form method="POST" action="{{ route('admin.deposit.approve', $transaction) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-emerald-600 px-4 py-2 text-white">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.deposit.reject', $transaction) }}" class="grid gap-2">
                                        @csrf
                                        <textarea name="note" rows="2" placeholder="Note de rejet" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm"></textarea>
                                        <button type="submit" class="rounded-2xl bg-red-600 px-4 py-2 text-white">Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun dépôt en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Retraits en attente</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Numéro Lumicash</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendingWithdrawals as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">{{ $transaction->phone }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                                    <form method="POST" action="{{ route('admin.withdrawal.approve', $transaction) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-emerald-600 px-4 py-2 text-white">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.withdrawal.reject', $transaction) }}" class="grid gap-2">
                                        @csrf
                                        <textarea name="note" rows="2" placeholder="Note de rejet" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm"></textarea>
                                        <button type="submit" class="rounded-2xl bg-red-600 px-4 py-2 text-white">Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">Aucun retrait en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
