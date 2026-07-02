@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <section class="rounded-[12px] bg-white-200 p-4 text-white shadow-lg h-[80px] overflow-hidden">
        <div class="flex h-full min-h-[64px] items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-white/20">
                    <img src="{{ asset('assets/icons/logo.png') }}" alt="Logo" class="h-full w-full" />
                </div>
            </div>

            <div class="text-center">
                <p class="text-xs uppercase tracking-[0.3em] text-yellow-900">GoldenRise</p>
                <p class="text-green-600 font-semibold">-Invest-</p>
            </div>

            <a href="{{ route('client.notifications') }}" class="relative inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-white/15">
                <img src="{{ asset('assets/icons/notification.svg') }}" alt="Notifications" class="h-6 w-6" />
                @if($unreadNotificationsCount > 0)
                    <span class="absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-semibold text-white">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="rounded-[12px] bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-700 p-4 text-white shadow-lg">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Main Balance</p>
            <p class="mt-3 text-2xl font-semibold">{{ number_format($withdrawableBalance, 0, ',', ' ') }} FBU</p>
        </div>
        <div class="rounded-[12px] bg-white p-4 shadow-lg">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Deposit</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900">{{ number_format($approvedDepositAmount, 0, ',', ' ') }} FBU</p>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3">
        @php
            $quickActions = [
                ['label' => 'Deposit', 'icon' => asset('assets/icons/deposit.svg'), 'route' => route('client.deposit.form')],
                ['label' => 'Withdraw', 'icon' => asset('assets/icons/withdraw.svg'), 'route' => route('client.withdraw.form')],
            ];
        @endphp

        @foreach ($quickActions as $action)
            <a href="{{ $action['route'] }}" class="rounded-[12px] bg-gradient-to-br  to-orange-600 p-4 text-center shadow-sm transition hover:-translate-y-0.5 h-[70px]">
                <div class="mx-auto flex h-5.5 w-10 items-center justify-center rounded-xl bg-slate-100">
                    <img src="{{ $action['icon'] }}" alt="{{ $action['label'] }}" class="h-3 w-3" />
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-700">{{ $action['label'] }}</p>
            </a>
        @endforeach
    </section>

    <section class="grid grid-cols-4 gap-3">
        @php
            $menuItems = [
                ['label' => 'Invest', 'icon' => asset('assets/icons/invest.svg'), 'route' => route('client.vip')],
                ['label' => 'Referral', 'icon' => asset('assets/icons/referral.svg'), 'route' => route('client.referral')],
                ['label' => 'VIP Plans', 'icon' => asset('assets/icons/vip.svg'), 'route' => route('client.vip')],
                ['label' => 'Statistics', 'icon' => asset('assets/icons/statistics.svg'), 'route' => route('client.statistics')],
                ['label' => 'Support', 'icon' => asset('assets/icons/support.svg'), 'route' => route('client.support')],
                ['label' => 'Bonus', 'icon' => asset('assets/icons/bonus.svg'), 'route' => route('client.bonus')],
                ['label' => 'News', 'icon' => asset('assets/icons/news.svg'), 'route' => route('client.news')],
                ['label' => 'Education', 'icon' => asset('assets/icons/education.svg'), 'route' => route('client.education')],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <a href="{{ $item['route'] }}" class="rounded-[12px] bg-white p-0.5 text-center shadow-sm transition hover:-translate-y-0.5">
                <div class="mx-auto flex h-6 w-6 items-center justify-center rounded-3xl bg-slate-100">
                    <img src="{{ $item['icon'] }}" alt="{{ $item['label'] }}" class="h-6 w-6" />
                </div>
                <p class="mt-2 text-[11px] font-semibold text-slate-700">{{ $item['label'] }}</p>
            </a>
        @endforeach
    </section>

    <section class="grid gap-3">
        <a href="{{ route('client.weekly-challenge') }}" class="rounded-[12px] bg-gradient-to-r from-violet-600 to-blue-600 p-3 text-white shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                        <img src="{{ asset('assets/icons/challenge.svg') }}" alt="Challenge" class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold">Weekly Challenge</h2>
                        <p class="text-xs text-white/80">Complete tasks & win</p>
                    </div>
                </div>
                <div class="rounded-full bg-white/20 px-3 py-1.5 text-xs font-medium">
                    View >
                </div>
            </div>
        </a>

        <a href="{{ route('client.mkopo') }}" class="rounded-[12px] bg-gradient-to-r from-emerald-600 to-teal-600 p-3 text-white shadow-lg hover:shadow-xl transition">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                        <img src="{{ asset('assets/icons/mkopo.svg') }}" alt="Mkopo" class="h-5 w-5" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold">Mkopo - Comptes vérifiés</h2>
                        <p class="text-xs text-white/80">Offres de prêts exclusives</p>
                    </div>
                </div>
                <div class="rounded-full bg-white/20 px-3 py-1.5 text-xs font-medium">
                    Voir >
                </div>
            </div>
        </a>
    </section>

    <section class="fixed bottom-3 left-1/2 z-30 w-[calc(100%-1.5rem)] -translate-x-1/2 rounded-[28px] bg-white p-1.5 shadow-[0_4px_20px_rgba(0,0,0,0.5)] backdrop-blur-sm border-2 border-yellow-500">
        <div class="grid grid-cols-5 gap-1">
            @php
                $bottomNav = [
                    ['label' => 'Dashboard', 'icon' => asset('assets/icons/home.svg'), 'route' => route('client.dashboard')],
                    ['label' => 'VIP', 'icon' => asset('assets/icons/vip.svg'), 'route' => route('client.vip')],
                    ['label' => 'Active VIP', 'icon' => asset('assets/icons/active-vip-new.svg'), 'route' => route('client.active-vip')],
                    ['label' => 'Team', 'icon' => asset('assets/icons/team.svg'), 'route' => route('client.team')],
                    ['label' => 'Settings', 'icon' => asset('assets/icons/settings-new.svg'), 'route' => route('client.settings')],
                ];
            @endphp

            @foreach ($bottomNav as $item)
                <a href="{{ $item['route'] }}" class="inline-flex flex-col items-center justify-center rounded-[10px] px-1.5 py-1.5 text-[10px] text-slate-700 transition hover:bg-slate-100">
                    <img src="{{ $item['icon'] }}" alt="{{ $item['label'] }}" class="h-5 w-5" />
                    <span class="mt-0.5">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
            </div>