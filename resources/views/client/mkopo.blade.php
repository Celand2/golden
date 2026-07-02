@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Mkopo (Prêt)</h1>
        <p class="text-slate-600 mb-6">Demandez un prêt si vous avez au moins 30 filleuls L1 actifs</p>

        <!-- Premium Status -->
        <div class="mb-6 rounded-lg {{ $isPremium ? 'bg-gradient-to-r from-yellow-50 to-amber-50 border-2 border-yellow-400' : 'bg-slate-50 border border-slate-200' }} p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 mb-1">
                        {{ $isPremium ? '👑 Compte Premium' : 'Compte Standard' }}
                    </h2>
                    <p class="text-sm text-slate-600">
                        Filleuls L1 actifs: <span class="font-bold {{ $isPremium ? 'text-yellow-700' : 'text-slate-900' }}">{{ $activeL1Count }} / 30</span>
                    </p>
                </div>
                @if($isPremium)
                    <div class="rounded-full bg-yellow-400 px-4 py-2 text-sm font-bold text-white">
                        PREMIUM
                    </div>
                @endif
            </div>
            
            @if(!$isPremium)
                <div class="mt-4 rounded-lg bg-blue-50 p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Information:</strong> Vous avez besoin de {{ 30 - $activeL1Count }} filleuls L1 actifs supplémentaires pour débloquer les prêts.
                    </p>
                </div>
            @endif
        </div>

        @if($isPremium)
            <!-- Loan Request Form -->
            <div class="mb-8 rounded-lg border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Demander un Prêt</h3>
                
                <form action="{{ route('client.mkopo.request') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant du prêt (FBU)</label>
                        <input type="number" name="amount" min="10000" max="500000" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="Minimum 10,000 FBU">
                        <p class="mt-1 text-xs text-slate-500">Minimum: 10,000 FBU | Maximum: 500,000 FBU</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Durée (mois)</label>
                        <input type="number" name="duration_months" min="1" max="12" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="1 à 12 mois">
                    </div>

                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-sm text-slate-600 mb-2">Taux d'intérêt selon vos filleuls actifs:</p>
                        <ul class="text-sm text-slate-700 space-y-1">
                            <li>• 100+ filleuls L1 actifs: <strong>5%</strong></li>
                            <li>• 60-99 filleuls L1 actifs: <strong>10%</strong></li>
                            <li>• 30-59 filleuls L1 actifs: <strong>15%</strong></li>
                        </ul>
                        <p class="mt-2 text-sm font-semibold text-slate-900">
                            Votre taux: {{ match(true) { $activeL1Count >= 100 => 5, $activeL1Count >= 60 => 10, default => 15 } }}%
                        </p>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-white font-semibold hover:bg-emerald-700">
                        Demander le Prêt
                    </button>
                </form>
            </div>
        @endif

        <!-- Loan History -->
        @if($loans->isNotEmpty())
            <div class="rounded-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Historique des Prêts</h3>
                </div>
                <div class="divide-y divide-slate-200">
                    @foreach($loans as $loan)
                        <div class="p-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ number_format($loan->amount, 0, ',', ' ') }} FBU</p>
                                    <p class="text-sm text-slate-600">
                                        Taux: {{ $loan->interest_rate }}% | Durée: {{ $loan->duration_months }} mois
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Demandé le {{ $loan->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($loan->status === 'paid' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                    <p class="text-sm text-slate-600 mt-1">
                                        À rembourser: {{ number_format($loan->total_repayment, 0, ',', ' ') }} FBU
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection