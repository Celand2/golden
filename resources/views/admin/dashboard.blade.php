@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Tableau de bord</h1>
                <p class="mt-2 text-sm text-slate-500">Vue d’ensemble de l’application et accès rapide aux écrans de gestion.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.notifications.index') }}" class="rounded-3xl bg-slate-50 px-5 py-4 text-center shadow-sm hover:bg-slate-100">
                    <p class="text-sm uppercase text-slate-500">Notifications non lues</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $unreadNotifications }}</p>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="rounded-3xl bg-slate-50 px-5 py-4 text-center shadow-sm hover:bg-slate-100">
                    <p class="text-sm uppercase text-slate-500">Notifications cette semaine</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $notificationsThisWeek }}</p>
                </a>
            </div>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-3xl bg-emerald-600 p-5 text-white">
                <p class="text-sm uppercase">Total utilisateurs</p>
                <p class="mt-4 text-3xl font-semibold">{{ $totalUsers }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-5 text-slate-900 shadow-sm">
                <p class="text-sm uppercase text-slate-500">Dépôts en attente</p>
                <p class="mt-4 text-3xl font-semibold">{{ $pendingDeposits->count() }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-5 text-slate-900 shadow-sm">
                <p class="text-sm uppercase text-slate-500">Retraits en attente</p>
                <p class="mt-4 text-3xl font-semibold">{{ $pendingWithdrawals->count() }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-5 text-slate-900 shadow-sm">
                <p class="text-sm uppercase text-slate-500">Investissements actifs</p>
                <p class="mt-4 text-3xl font-semibold">{{ $activeInvestments }}</p>
            </div>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.vip-plans.index') }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">Gérer les plans VIP</p>
            </a>
            <a href="{{ route('admin.deposits.index') }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">Gérer les dépôts</p>
            </a>
            <a href="{{ route('admin.withdrawals.index') }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">Gérer les retraits</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">Gérer les utilisateurs</p>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-center transition hover:-translate-y-0.5">
                <p class="text-sm font-medium text-slate-700">Gérer les notifications</p>
            </a>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Top 3 des parrains directs de la semaine</h2>
        @if ($topReferrersThisWeek->isEmpty())
            <p class="mt-4 text-sm text-slate-500">Aucun parrain direct cette semaine.</p>
        @else
            <div class="mt-4 grid gap-4 lg:grid-cols-3">
                @foreach ($topReferrersThisWeek as $referrer)
                    <div class="rounded-3xl border border-slate-200 p-5 shadow-sm">
                        <p class="text-sm uppercase text-slate-500">{{ $loop->iteration }}. {{ $referrer->name }}</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900">{{ $referrer->phone ?? 'Téléphone non renseigné' }}</p>
                        <p class="mt-2 text-sm text-slate-500">Filleuls cette semaine : <span class="font-semibold text-slate-900">{{ $referrer->weekly_referrals_count }}</span></p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Coordonnées Lumicash</h2>
        <form method="POST" action="{{ route('admin.lumicash.update') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Numéro Lumicash</label>
                <input name="lumicash_phone" type="text" value="{{ $lumicash['phone'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nom Lumicash</label>
                <input name="lumicash_name" type="text" value="{{ $lumicash['name'] ?? '' }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="rounded-3xl bg-emerald-600 px-5 py-3 text-white">Enregistrer</button>
            </div>
        </form>
    </section>
</div>
@endsection
