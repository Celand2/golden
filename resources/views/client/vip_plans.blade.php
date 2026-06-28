@extends('layouts.client')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Plans VIP</h1>
                <p class="mt-2 text-sm text-slate-500">Choisissez un plan VIP et investissez pour commencer à gagner.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
        </div>
        <div class="mt-6 rounded-3xl bg-emerald-50 p-5 text-slate-700">
            Solde disponible : <span class="font-semibold text-emerald-900">{{ number_format($user->wallet_balance, 0, ',', ' ') }} FBU</span>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        <div class="grid gap-4 lg:grid-cols-3">
            @forelse($vipPlans as $plan)
                <div class="rounded-3xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-xl font-semibold text-slate-900">{{ $plan->name }}</h2>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">VIP</span>
                    </div>
                    <p class="mt-4 text-slate-600">Montant minimum : <span class="font-semibold text-slate-900">{{ number_format($plan->min_amount, 0, ',', ' ') }} FBU</span></p>
                    <p class="mt-2 text-slate-600">Taux journalier : <span class="font-semibold text-slate-900">{{ $plan->daily_rate }}%</span></p>
                    <p class="mt-2 text-slate-600">Durée : <span class="font-semibold text-slate-900">{{ $plan->duration_days }} jours</span></p>
                    <div class="mt-6 flex items-center justify-between gap-3">
                        <form action="{{ route('client.vip-plans.invest', $plan) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-emerald-600 px-4 py-3 text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-slate-300" @if($user->wallet_balance < $plan->min_amount) disabled @endif>
                                {{ $user->wallet_balance < $plan->min_amount ? 'Solde insuffisant' : 'Investir' }}
                            </button>
                        </form>
                        <span class="text-sm text-slate-500">Actif</span>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 p-6 text-center text-slate-500">
                    Aucun plan VIP disponible pour le moment.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
