@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    <div class="mx-auto max-w-xl rounded-3xl bg-white p-8 shadow-lg">
        <h1 class="mb-4 text-2xl font-semibold text-slate-900">Demande de retrait</h1>
        
        <!-- Info solde -->
        <div class="mb-6 rounded-2xl bg-slate-50 p-4">
            <p class="text-sm text-slate-500">Solde retirable disponible</p>
            <p class="mt-1 text-2xl font-semibold text-emerald-600">{{ number_format($user->withdrawable_balance, 0, ',', ' ') }} FBU</p>
        </div>

        <!-- Vérifications -->
        @php
            $now = \Carbon\Carbon::now();
            $isWithinHours = $now->hour >= 7 && $now->hour < 21;
            $hasPendingWithdrawal = $user->transactions()
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->exists();
        @endphp

        @if (!$isWithinHours)
            <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-200">
                <p class="text-sm font-semibold text-red-900">🚫 Retraits indisponibles</p>
                <p class="text-xs text-red-700 mt-1">Les retraits sont disponibles de 7h à 21h. Heure actuelle: {{ $now->format('H:i') }}</p>
            </div>
        @endif

        @if ($hasPendingWithdrawal)
            <div class="mb-6 rounded-2xl bg-orange-50 p-4 border border-orange-200">
                <p class="text-sm font-semibold text-orange-900">⏳ Retrait en attente</p>
                <p class="text-xs text-orange-700 mt-1">Vous avez déjà une demande de retrait en attente. Attendez son approbation ou rejet avant d'en soumettre une nouvelle.</p>
            </div>
        @endif

    <form method="POST" action="{{ route('client.withdraw') }}" class="space-y-5" id="withdrawForm" onsubmit="return validateWithdrawalForm(event)">
        @csrf
        
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Montant (minimum 5 000 FBU)</label>
            <input 
                type="number" 
                name="amount" 
                id="amountInput"
                min="5000" 
                step="1"
                value="{{ old('amount') }}" 
                required 
                class="w-full rounded-3xl border border-slate-200 px-4 py-3"
                oninput="calculateFees()"
            />
            <p class="mt-1 text-xs text-slate-500">Maximum disponible: {{ number_format($user->withdrawable_balance, 0, ',', ' ') }} FBU</p>
        </div>

        <!-- Affichage frais 5% -->
        <div id="feesSection" class="hidden rounded-2xl bg-blue-50 p-4 border border-blue-200">
            <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-slate-600">Montant brut:</p>
                <p class="font-semibold text-slate-900" id="grossAmount">0 FBU</p>
            </div>
            <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-slate-600">Frais (5%):</p>
                <p class="font-semibold text-red-600" id="feeAmount">0 FBU</p>
            </div>
            <div class="border-t border-blue-200 pt-2 flex justify-between items-center">
                <p class="text-sm font-semibold text-slate-700">Montant final:</p>
                <p class="text-lg font-bold text-emerald-600" id="netAmount">0 FBU</p>
            </div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Nom du destinataire</label>
            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" required class="w-full rounded-3xl border border-slate-200 px-4 py-3" />
        </div>

        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Téléphone du destinataire</label>
            <input type="text" name="recipient_phone" value="{{ old('recipient_phone') }}" required class="w-full rounded-3xl border border-slate-200 px-4 py-3" placeholder="Ex: +257612345678" />
        </div>

        <button 
            type="submit" 
            id="submitBtn"
            class="w-full rounded-3xl bg-emerald-600 px-4 py-3 text-white font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed"
            {{ !$isWithinHours || $hasPendingWithdrawal ? 'disabled' : '' }}
        >
            Soumettre la demande
        </button>
    </form>
</div>

<script>
    const maxBalance = {{ $user->withdrawable_balance }};
    const isWithinHours = {{ $isWithinHours ? 'true' : 'false' }};
    const hasPendingWithdrawal = {{ $hasPendingWithdrawal ? 'true' : 'false' }};

    function calculateFees() {
        const amountInput = document.getElementById('amountInput');
        const amount = parseFloat(amountInput.value) || 0;
        const submitBtn = document.getElementById('submitBtn');
        const feesSection = document.getElementById('feesSection');

        if (amount >= 5000 && amount <= maxBalance) {
            const fee = amount * 0.05;
            const netAmount = amount - fee;

            document.getElementById('grossAmount').textContent = new Intl.NumberFormat('fr-FR').format(Math.floor(amount)) + ' FBU';
            document.getElementById('feeAmount').textContent = new Intl.NumberFormat('fr-FR').format(Math.floor(fee)) + ' FBU';
            document.getElementById('netAmount').textContent = new Intl.NumberFormat('fr-FR').format(Math.floor(netAmount)) + ' FBU';

            feesSection.classList.remove('hidden');
            submitBtn.disabled = false;
        } else if (amount > maxBalance) {
            feesSection.classList.add('hidden');
            submitBtn.disabled = true;
            amountInput.classList.add('border-red-500');
        } else if (amount > 0 && amount < 5000) {
            feesSection.classList.add('hidden');
            submitBtn.disabled = true;
        } else {
            feesSection.classList.add('hidden');
            submitBtn.disabled = !isWithinHours || hasPendingWithdrawal;
        }

        // Remove error styling if valid
        if (amount <= maxBalance && amount >= 5000) {
            amountInput.classList.remove('border-red-500');
        }
    }

    function validateWithdrawalForm(event) {
        // Vérification horaires
        const now = new Date();
        const hour = now.getHours();
        if (hour < 7 || hour >= 21) {
            alert('❌ Les retraits ne sont disponibles que de 7h à 21h.');
            event.preventDefault();
            return false;
        }

        // Vérification solde
        const amount = parseFloat(document.getElementById('amountInput').value);
        if (amount > maxBalance) {
            alert('❌ Montant insuffisant. Votre solde retirable: ' + new Intl.NumberFormat('fr-FR').format(Math.floor(maxBalance)) + ' FBU');
            event.preventDefault();
            return false;
        }

        if (amount < 5000) {
            alert('❌ Le montant minimum est 5 000 FBU.');
            event.preventDefault();
            return false;
        }

        // Vérification si retrait pending
        if (hasPendingWithdrawal) {
            alert('❌ Vous avez déjà une demande de retrait en attente.');
            event.preventDefault();
            return false;
        }

        return true;
    }

    // Initialiser
    window.addEventListener('load', () => {
        calculateFees();
    });
</script>
@endsection
