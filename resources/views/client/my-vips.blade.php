@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 mb-6">Retour au dashboard</a>
    <h1 class="text-3xl font-bold mb-6">Mes VIP Actifs</h1>

    @if($investments->isEmpty())
        <div class="bg-gray-100 p-6 rounded-lg text-center">
            <p class="text-gray-700 mb-4">Vous n'avez pas encore d'investment actif.</p>
            <a href="{{ route('client.vip-plans') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                Découvrir les plans VIP
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($investments as $investment)
                <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-6">
                    <!-- Header -->
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">{{ $investment->vipPlan->name }}</h2>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $investment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($investment->status) }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="space-y-3 mb-6 text-sm text-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">Montant investi:</span>
                            <span>{{ number_format($investment->amount, 2) }} FBU</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Gain journalier:</span>
                            <span>{{ number_format($investment->daily_gain, 2) }} FBU</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Gains accumulés:</span>
                            <span class="text-green-600 font-bold">{{ number_format($investment->accumulated_gains, 2) }} FBU</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Total réclamé:</span>
                            <span>{{ number_format($investment->total_claimed, 2) }} FBU</span>
                        </div>
                    </div>

                    <!-- Countdown avant prochain claim -->
                    @if($investment->status === 'active')
                        <div class="mb-4 text-center">
                            @if($investment->next_claim_at && $investment->next_claim_at->isFuture())
                                <p class="text-xs text-gray-500 mb-1">Prochain claim dans :</p>
                                <p class="text-lg font-bold text-blue-600 countdown-timer" 
                                   data-target="{{ $investment->next_claim_at->timestamp }}">
                                    --:--:--
                                </p>
                            @else
                                <p class="text-sm font-semibold text-green-600">✅ Claim disponible maintenant</p>
                            @endif
                        </div>
                    @endif

                    <!-- Claim Button -->
                    @if($investment->status === 'active' && $investment->accumulated_gains > 0)
                        <form action="{{ route('client.investment.claim', $investment->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                💰 Réclamer {{ number_format($investment->accumulated_gains, 2) }} FBU
                            </button>
                        </form>
                    @elseif($investment->status !== 'active')
                        <button disabled class="w-full bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                            Plan expiré
                        </button>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                            Aucun gain à réclamer
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@if ($errors->any())
    <div class="mt-6 max-w-md mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mt-6 max-w-md mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const timers = document.querySelectorAll('.countdown-timer');

    timers.forEach(function (el) {
        const targetTimestamp = parseInt(el.dataset.target, 10) * 1000; // en ms

        function update() {
            const now = Date.now();
            const diff = targetTimestamp - now;

            if (diff <= 0) {
                el.textContent = 'Disponible maintenant';
                el.classList.remove('text-blue-600');
                el.classList.add('text-green-600');
                clearInterval(interval);
                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            el.textContent = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }

        update();
        const interval = setInterval(update, 1000);
    });
});
</script>

@endsection