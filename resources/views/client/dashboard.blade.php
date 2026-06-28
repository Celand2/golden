@extends('layouts.client')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Bonjour, {{ $user->name }}</h1>
                <p class="text-sm text-slate-500">{{ ucfirst($user->role) }} · Solde principal</p>
            </div>
            <div class="rounded-3xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">{{ $user->role === 'premium' ? 'Premium' : 'Standard' }}</div>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-3xl bg-emerald-600 p-5 text-white shadow-md">
                <p class="text-sm uppercase">Main Balance</p>
                <p class="mt-4 text-3xl font-semibold">{{ number_format($user->wallet_balance, 0, ',', ' ') }} FBU</p>
            </div>
            <div class="rounded-3xl bg-white border border-slate-200 p-5 shadow-sm">
                <p class="text-sm uppercase text-slate-500">Total Deposit</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ number_format($user->transactions()->where('type', 'deposit')->where('status', 'approved')->sum('amount'), 0, ',', ' ') }} FBU</p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-4">
        @foreach(['Deposit' => 'client.deposit.form', 'Withdraw' => 'client.withdraw.form', 'Invest' => 'client.vip-plans', 'Team' => 'client.team'] as $label => $route)
            <a href="{{ $route ? route($route) : '#' }}" class="rounded-3xl bg-white p-5 text-center shadow-sm transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">{{ $label }}</p>
            </a>
        @endforeach
    </section>

    <section class="grid gap-4 sm:grid-cols-3">
        @foreach(['VIP Plans', 'Statistics', 'Support', 'Bonus', 'News', 'Education'] as $item)
            <div class="rounded-3xl bg-white p-5 text-center shadow-sm">
                <p class="text-sm font-medium text-slate-700">{{ $item }}</p>
            </div>
        @endforeach
    </section>

    <section class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Weekly Challenge</p>
            <h2 class="mt-2 text-lg font-semibold text-slate-900">Complete tasks and win big rewards</h2>
            <div class="mt-4 inline-flex rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">View Challenge</div>
        </div>
        @if ($user->role === 'premium')
            <div class="rounded-3xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Mkopo (Premium uniquement)</p>
                <h2 class="mt-2 text-lg font-semibold text-slate-900">Accédez à nos offres de prêts exclusives</h2>
                <div class="mt-4 inline-flex rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Voir plus</div>
            </div>
        @endif
    </section>

    <section class="fixed bottom-4 left-1/2 z-20 w-[calc(100%-2rem)] -translate-x-1/2 rounded-3xl bg-white p-4 shadow-2xl sm:hidden">
        <div class="grid grid-cols-3 gap-3">
            @foreach(['Dashboard', 'VIP', 'Active VIP', 'Team', 'Bonus', 'Paramètres'] as $item)
                <button class="rounded-3xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">{{ $item }}</button>
            @endforeach
        </div>
    </section>
</div>
@endsection
