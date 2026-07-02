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
                                    <button type="button" class="text-emerald-600 hover:underline" onclick="openProofModal('{{ asset('storage/' . $transaction->payment_proof) }}', '{{ $transaction->user->name }}')">
                                        Voir preuve
                                    </button>
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

    <!-- Modal de prévisualisation des preuves -->
    <div id="proofModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 p-4">
        <div class="relative w-full max-w-2xl max-h-[90vh] bg-white rounded-[32px] shadow-2xl flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Preuve de paiement</h2>
                <button type="button" onclick="closeProofModal()" class="text-slate-500 hover:text-slate-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="overflow-y-auto flex-1 px-6 py-6">
                <div id="proofContent" class="flex flex-col items-center">
                    <!-- Image ou contenu sera inséré ici -->
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-slate-200 px-6 py-4 flex gap-3 justify-end">
                <button type="button" onclick="closeProofModal()" class="rounded-2xl bg-slate-100 px-4 py-2 text-slate-700 hover:bg-slate-200">Fermer</button>
                <a id="downloadLink" href="#" target="_blank" class="rounded-2xl bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">
                    Télécharger
                </a>
            </div>
        </div>
    </div>

    <script>
        function openProofModal(imageUrl, clientName) {
            const modal = document.getElementById('proofModal');
            const proofContent = document.getElementById('proofContent');
            const downloadLink = document.getElementById('downloadLink');

            // Déterminer le type de fichier
            const ext = imageUrl.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
            const isPDF = ext === 'pdf';

            // Créer le contenu approprié
            if (isImage) {
                proofContent.innerHTML = `
                    <img src="${imageUrl}" alt="Preuve de paiement" class="w-full h-auto rounded-2xl border border-slate-200">
                    <p class="mt-4 text-center text-sm text-slate-600">Preuve de paiement pour ${clientName}</p>
                `;
            } else if (isPDF) {
                proofContent.innerHTML = `
                    <div class="w-full border border-slate-200 rounded-2xl p-8 flex flex-col items-center justify-center bg-slate-50 min-h-96">
                        <svg class="h-16 w-16 text-red-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-lg font-semibold text-slate-900">Fichier PDF</p>
                        <p class="mt-2 text-sm text-slate-600">Cliquez sur "Télécharger" pour ouvrir le fichier</p>
                    </div>
                `;
            } else {
                proofContent.innerHTML = `
                    <div class="w-full border border-slate-200 rounded-2xl p-8 flex flex-col items-center justify-center bg-slate-50 min-h-96">
                        <svg class="h-16 w-16 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-semibold text-slate-900">Fichier non reconnu</p>
                        <p class="mt-2 text-sm text-slate-600">Cliquez sur "Télécharger" pour accéder au fichier</p>
                    </div>
                `;
            }

            downloadLink.href = imageUrl;
            downloadLink.download = `preuve_${clientName.replace(/\s/g, '_')}.${ext}`;

            modal.classList.remove('hidden');
        }

        function closeProofModal() {
            const modal = document.getElementById('proofModal');
            modal.classList.add('hidden');
        }

        // Fermer le modal en cliquant en dehors
        document.getElementById('proofModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProofModal();
            }
        });
    </script>
</div>
@endsection
