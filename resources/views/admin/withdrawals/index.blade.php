@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Gestion des Retraits</h1>
                <p class="mt-2 text-sm text-slate-500">Approuvez ou rejetez les demandes de retrait.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="copyAllWithdrawals()" class="inline-flex items-center rounded-3xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copier la liste
                </button>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au tableau</a>
            </div>
        </div>
    </section>

    <!-- Retraits en attente -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Retraits en Attente</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Après frais (5%)</th>
                        <th class="px-4 py-3 text-left font-semibold">Récepteur</th>
                        <th class="px-4 py-3 text-left font-semibold">Crédité le</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendingWithdrawals as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3 font-semibold text-emerald-600">{{ number_format($transaction->amount_after_fees, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">
                                <div class="text-xs">
                                    <p class="font-semibold">{{ $transaction->recipient_name }}</p>
                                    <p class="text-slate-500">{{ $transaction->recipient_phone }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    <!-- Copier -->
                                    <button type="button" onclick="copyWithdrawalInfo('{{ $transaction->recipient_phone }}', '{{ $transaction->amount }}', '{{ $transaction->recipient_name }}')" class="rounded-2xl bg-blue-100 px-3 py-2 text-blue-700 hover:bg-blue-200 text-xs">
                                        Copier
                                    </button>

                                    <!-- Approuver -->
                                    <form method="POST" action="{{ route('admin.withdrawal.approve', $transaction) }}">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Approuver ce retrait ?')" class="rounded-2xl bg-emerald-100 px-3 py-2 text-emerald-700 hover:bg-emerald-200 text-xs">
                                            Approuver
                                        </button>
                                    </form>

                                    <!-- Rejeter -->
                                    <form method="POST" action="{{ route('admin.withdrawal.reject', $transaction) }}" onsubmit="return addRejectReason(this)">
                                        @csrf
                                        <input type="hidden" name="reason" value="">
                                        <button type="submit" class="rounded-2xl bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200 text-xs">
                                            Rejeter
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun retrait en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Retraits approuvés -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Retraits Approuvés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Numéro Lumicash</th>
                        <th class="px-4 py-3 text-left font-semibold">Approuvé le</th>
                        <th class="px-4 py-3 text-center font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($approvedWithdrawals as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">{{ $transaction->phone }}</td>
                            <td class="px-4 py-3">{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.withdrawal.reject', $transaction) }}" class="flex justify-center">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="rounded-2xl bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200 text-xs">
                                        Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun retrait approuvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Retraits rejetés -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Retraits Rejetés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Raison</th>
                        <th class="px-4 py-3 text-left font-semibold">Rejeté le</th>
                        <th class="px-4 py-3 text-center font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($rejectedWithdrawals as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3 text-xs">{{ $transaction->rejection_reason ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.withdrawal.approve', $transaction) }}" class="flex justify-center">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-emerald-100 px-3 py-2 text-emerald-700 hover:bg-emerald-200 text-xs">
                                        Approuver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun retrait rejeté.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        function copyWithdrawalInfo(phone, amount, name) {
            const text = `Nom: ${name}\nNuméro: ${phone}\nMontant: ${amount} FBU`;
            navigator.clipboard.writeText(text).then(() => {
                alert('Informations copiées!');
            });
        }

        function copyAllWithdrawals() {
            const withdrawals = [
                @foreach($pendingWithdrawals as $transaction)
                    {
                        userName: '{{ $transaction->user->name }}',
                        recipientName: '{{ $transaction->recipient_name }}',
                        recipientPhone: '{{ $transaction->recipient_phone }}',
                        amount: {{ $transaction->amount }},
                        amountAfterFees: {{ $transaction->amount_after_fees }}
                    },
                @endforeach
            ];

            if (withdrawals.length === 0) {
                alert('Aucun retrait en attente à copier.');
                return;
            }

            let text = 'RETRAITS EN ATTENTE\n';
            text += '==================\n\n';
            withdrawals.forEach((w, idx) => {
                text += `${idx + 1}. Client: ${w.userName}\n`;
                text += `   Destinataire: ${w.recipientName}\n`;
                text += `   Numéro: ${w.recipientPhone}\n`;
                text += `   Montant: ${w.amount} FBU\n`;
                text += `   Après frais: ${w.amountAfterFees} FBU\n\n`;
            });

            navigator.clipboard.writeText(text).then(() => {
                alert('Liste complète copiée!');
            });
        }

        // Demande la raison du rejet avant de soumettre le formulaire
        function addRejectReason(form) {
            const reason = prompt('Raison du rejet (optionnel):', '');
            if (reason === null) {
                // L'admin a annulé le prompt -> on annule l'envoi du formulaire
                return false;
            }
            form.querySelector('input[name="reason"]').value = reason || 'Raison non spécifiée';
            return true;
        }
    </script>
</div>
@endsection