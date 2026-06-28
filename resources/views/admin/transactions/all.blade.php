@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Toutes les transactions</h1>
                <p class="mt-2 text-sm text-slate-500">Historique et gestion complète des transactions.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au tableau</a>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        <div class="min-w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Type</th>
                        <th class="px-4 py-3 text-left font-semibold">Preuve</th>
                        <th class="px-4 py-3 text-right font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">État</th>
                        <th class="px-4 py-3 text-left font-semibold">Créé le</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-3">#{{ $transaction->id }}</td>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3">{{ ucfirst($transaction->type) }}</td>
                            <td class="px-4 py-3">
                                @if ($transaction->payment_proof)
                                    <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="text-emerald-600 hover:underline">Voir preuve</a>
                                @else
                                    <span class="text-slate-500">Aucune</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">{{ ucfirst($transaction->status) }}</td>
                            <td class="px-4 py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @if ($transaction->status === 'pending')
                                        <button type="button" data-open-modal="modal-transaction-{{ $transaction->id }}" class="rounded-2xl bg-slate-100 px-4 py-2 text-slate-700 hover:bg-slate-200">Actions</button>
                                    @else
                                        <span class="text-slate-500">Aucune action</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @foreach($transactions as $transaction)
        <div id="modal-transaction-{{ $transaction->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 px-4 py-10">
            <div class="mx-auto max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Transaction #{{ $transaction->id }}</h2>
                        <p class="text-sm text-slate-500">{{ ucfirst($transaction->type) }} • {{ ucfirst($transaction->status) }}</p>
                    </div>
                    <button type="button" data-close-modal="modal-transaction-{{ $transaction->id }}" class="rounded-full bg-slate-100 px-3 py-2 text-slate-700">Fermer</button>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Client</p>
                        <p class="mt-2 font-semibold text-slate-900">{{ $transaction->user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $transaction->user->email }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Montant</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</p>
                        <p class="mt-3 text-sm text-slate-500">Numéro Lumicash : {{ $transaction->phone ?? 'Non défini' }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Preuve de paiement</p>
                    @if ($transaction->payment_proof)
                        <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="mt-2 inline-flex text-emerald-600 hover:underline">Voir la preuve</a>
                    @else
                        <p class="mt-2 text-sm text-slate-500">Aucune preuve fournie.</p>
                    @endif
                </div>

                @if ($transaction->status === 'pending' && in_array($transaction->type, ['deposit', 'withdrawal']))
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <form method="POST" action="{{ route($transaction->type === 'deposit' ? 'admin.deposit.approve' : 'admin.withdrawal.approve', $transaction) }}">
                            @csrf
                            <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-5 py-3 text-white">Approuver</button>
                        </form>
                        <form method="POST" action="{{ route($transaction->type === 'deposit' ? 'admin.deposit.reject' : 'admin.withdrawal.reject', $transaction) }}" class="grid gap-3">
                            @csrf
                            <textarea name="note" rows="3" placeholder="Note de rejet (optionnel)" class="w-full rounded-3xl border border-slate-200 px-4 py-3"></textarea>
                            <button type="submit" class="w-full rounded-3xl bg-red-600 px-5 py-3 text-white">Rejeter</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <script>
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);
                modal?.classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.closeModal);
                modal?.classList.add('hidden');
            });
        });
    </script>
</div>
@endsection
