@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Gestion des Dépôts</h1>
                <p class="mt-2 text-sm text-slate-500">Approuvez ou rejetez les demandes de dépôt et consultez la preuve de paiement.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au tableau</a>
        </div>
    </section>

    <!-- Dépôts en attente -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Dépôts en Attente</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Preuve</th>
                        <th class="px-4 py-3 text-left font-semibold">Numéro Lumicash</th>
                        <th class="px-4 py-3 text-left font-semibold">Crédité le</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendingDeposits as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">
                                @if ($transaction->payment_proof)
                                    <button type="button" onclick="openProofModal('{{ asset('storage/' . $transaction->payment_proof) }}', '{{ $transaction->user->name }}')" class="text-emerald-600 hover:underline">
                                        Voir preuve
                                    </button>
                                @else
                                    <span class="text-slate-500">Aucune</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $transaction->phone }}</td>
                            <td class="px-4 py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <button type="button" data-open-modal="modal-deposit-{{ $transaction->id }}" class="rounded-2xl bg-slate-100 px-3 py-2 text-slate-700 hover:bg-slate-200 text-xs">
                                    Actions
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucun dépôt en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Dépôts approuvés -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Dépôts Approuvés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Client</th>
                        <th class="px-4 py-3 text-left font-semibold">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold">Prévi</th>
                        <th class="px-4 py-3 text-left font-semibold">Approuvé le</th>
                        <th class="px-4 py-3 text-center font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($approvedDeposits as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3">
                                @if ($transaction->payment_proof)
                                    <button type="button" onclick="openProofModal('{{ asset('storage/' . $transaction->payment_proof) }}', '{{ $transaction->user->name }}')" class="text-emerald-600 hover:underline">
                                        Voir
                                    </button>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.deposit.reject', $transaction) }}" class="flex justify-center">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="rounded-2xl bg-red-100 px-3 py-2 text-red-700 hover:bg-red-200 text-xs">
                                        Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun dépôt approuvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Dépôts rejetés -->
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900 mb-5">Dépôts Rejetés</h2>
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
                    @forelse($rejectedDeposits as $transaction)
                        <tr>
                            <td class="px-4 py-3">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3 text-xs">{{ $transaction->rejection_reason ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.deposit.approve', $transaction) }}" class="flex justify-center">
                                    @csrf
                                    <button type="submit" class="rounded-2xl bg-emerald-100 px-3 py-2 text-emerald-700 hover:bg-emerald-200 text-xs">
                                        Approuver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun dépôt rejeté.</td>
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

    <!-- Modals pour actions -->
    @foreach($pendingDeposits as $transaction)
        <div id="modal-deposit-{{ $transaction->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 px-4 py-10">
            <div class="mx-auto max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Dépôt #{{ $transaction->id }}</h2>
                        <p class="text-sm text-slate-500">{{ $transaction->user->name }}</p>
                    </div>
                    <button type="button" data-close-modal="modal-deposit-{{ $transaction->id }}" class="rounded-full bg-slate-100 px-3 py-2 text-slate-700">✕</button>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Montant</p>
                        <p class="mt-1 text-2xl font-semibold text-slate-900">{{ number_format($transaction->amount, 0, ',', ' ') }} FBU</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Numéro Lumicash</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $transaction->phone }}</p>
                    </div>
                    @if ($transaction->payment_proof)
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500 mb-2">Preuve de paiement</p>
                            <button type="button" onclick="openProofModal('{{ asset('storage/' . $transaction->payment_proof) }}', '{{ $transaction->user->name }}')" class="text-emerald-600 hover:underline">
                                Cliquez pour voir
                            </button>
                        </div>
                    @endif
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <form method="POST" action="{{ route('admin.deposit.approve', $transaction) }}">
                        @csrf
                        <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-5 py-3 text-white font-semibold hover:bg-emerald-700">
                            Approuver
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.deposit.reject', $transaction) }}" class="space-y-3">
                        @csrf
                        <textarea name="reason" rows="2" placeholder="Raison du rejet" class="w-full rounded-3xl border border-slate-200 px-4 py-3 text-sm"></textarea>
                        <button type="submit" class="w-full rounded-3xl bg-red-600 px-5 py-3 text-white font-semibold hover:bg-red-700">
                            Rejeter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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

        // Fermer modal en cliquant dehors
        document.querySelectorAll('[id^="modal-deposit-"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
</div>
@endsection
