@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Statistiques de votre Compte</h1>

        <!-- Vue d'ensemble -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Vue d'ensemble</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-lg bg-gradient-to-br from-emerald-50 to-emerald-100 p-4">
                    <p class="text-sm text-slate-600">Solde Actuel</p>
                    <p class="text-2xl font-bold text-emerald-700">{{ number_format($user->wallet_balance, 0, ',', ' ') }} FBU</p>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-4">
                    <p class="text-sm text-slate-600">Total Dépôts</p>
                    <p class="text-2xl font-bold text-blue-700">{{ number_format($totalDeposits, 0, ',', ' ') }} FBU</p>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 p-4">
                    <p class="text-sm text-slate-600">Total Retraits</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($totalWithdrawals, 0, ',', ' ') }} FBU</p>
                </div>
                <div class="rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 p-4">
                    <p class="text-sm text-slate-600">Gains Totaux</p>
                    <p class="text-2xl font-bold text-orange-700">{{ number_format($totalGains, 0, ',', ' ') }} FBU</p>
                </div>
            </div>
        </div>

        <!-- Évolution du compte -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Évolution de votre Compte</h2>
            <div class="rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-700">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-700">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-700">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-slate-700">Solde</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($transaction->type === 'deposit')
                                            <span class="text-green-600 font-medium">Dépôt</span>
                                        @elseif($transaction->type === 'withdrawal')
                                            <span class="text-red-600 font-medium">Retrait</span>
                                        @elseif($transaction->type === 'daily_gain')
                                            <span class="text-blue-600 font-medium">Gain Journalier</span>
                                        @elseif($transaction->type === 'investment')
                                            <span class="text-purple-600 font-medium">Investissement</span>
                                        @else
                                            <span class="text-slate-600">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium {{ in_array($transaction->type, ['deposit', 'daily_gain']) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ in_array($transaction->type, ['deposit', 'daily_gain']) ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', ' ') }} FBU
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ number_format($transaction->balance_after, 0, ',', ' ') }} FBU</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Aucune transaction pour le moment</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

        <!-- Statistiques des parrainages -->
        <div>
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Vos Parrainages</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="rounded-lg bg-slate-50 p-4 text-center">
                    <p class="text-3xl font-bold text-slate-900">{{ $totalReferrals }}</p>
                    <p class="text-sm text-slate-600 mt-1">Total Parrainages</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 text-center">
                    <p class="text-3xl font-bold text-emerald-600">{{ $activeReferrals }}</p>
                    <p class="text-sm text-slate-600 mt-1">Parrainages Actifs</p>
                </div>
                <div class="rounded-lg bg-slate-50 p-4 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $totalCommission }}</p>
                    <p class="text-sm text-slate-600 mt-1">Commission Totale (FBU)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection