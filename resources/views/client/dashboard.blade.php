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

            <button class="relative inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-white/15">
                <img src="{{ asset('assets/icons/notification.png') }}" alt="Notifications" class="h-6 w-6" />
                @if($unreadNotificationsCount > 0)
                    <span class="absolute -right-1 -top-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-semibold text-white">{{ $unreadNotificationsCount }}</span>
                @endif
            </button>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3" h-[110px]>
        <div class="rounded-[12px] bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-700 p-4 text-white shadow-lg">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Main Balance</p>
            <p class="mt-3 text-2xl font-semibold">{{ number_format($user->wallet_balance, 0, ',', ' ') }} FBU</p>
        </div>
        <div class="rounded-[12px] bg-white p-4 shadow-lg">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Deposit</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900">{{ number_format($user->transactions()->where('type', 'deposit')->where('status', 'approved')->sum('amount'), 0, ',', ' ') }} FBU</p>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3">
        @php
            $quickActions = [
                ['label' => 'Deposit', 'icon' => asset('assets/icons/deposit.png'), 'route' => route('client.deposit.form')],
                ['label' => 'Withdraw', 'icon' => asset('assets/icons/withdraw.png'), 'route' => route('client.withdraw.form')],
            ];
        @endphp

        @foreach ($quickActions as $action)
            <a href="{{ $action['route'] }}" class="rounded-[12px] bg-gradient-to-br from-blue-500 p-4 text-center shadow-sm transition hover:-translate-y-0.5 h-[70px]">
                <div class="mx-auto flex h-4 w-10 items-center justify-center rounded-3xl bg-slate-100">
                    <img src="{{ $action['icon'] }}" alt="{{ $action['label'] }}" class="h-3 w-3" />
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-700">{{ $action['label'] }}</p>
            </a>
        @endforeach
    </section>

    <section class="grid grid-cols-4 gap-3">
        @php
            $menuItems = [
                ['label' => 'Invest', 'icon' => asset('assets/icons/invest.png'), 'route' => route('client.vip')],
                ['label' => 'Referral', 'icon' => asset('assets/icons/referral.png'), 'route' => route('client.referral')],
                ['label' => 'VIP Plans', 'icon' => asset('assets/icons/vip.png'), 'route' => route('client.vip')],
                ['label' => 'Statistics', 'icon' => asset('assets/icons/statistics.png'), 'route' => route('client.statistics')],
                ['label' => 'Support', 'icon' => asset('assets/icons/support.png'), 'route' => route('client.support')],
                ['label' => 'Bonus', 'icon' => asset('assets/icons/bonus.png'), 'route' => route('client.bonus')],
                ['label' => 'News', 'icon' => asset('assets/icons/news.png'), 'route' => route('client.news')],
                ['label' => 'Education', 'icon' => asset('assets/icons/education.png'), 'route' => route('client.education')],

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
        <div class="rounded-[12px] bg-gradient-to-r from-violet-600 to-blue-600 p-4 text-white shadow-lg h-[70px]">
            <div class="flex items-center justify-between gap-3">
                 <img src="{{ asset('assets/icons/challenge.png') }}" alt="Challenge" class="h-12 w-12 rounded-3xl" />
                <div>
                      
                    
                    <h2 class="mt-2 text-base ">Complete tasks and win</h2>
                </div>
                
                
            </div>
        </div>

        <div class="rounded-[12px] bg-gradient-to-r from-emerald-600 to-slate-900 p-4 text-white shadow-lg">
             <img src="{{ asset('assets/icons/mkopo.png') }}" alt="Mkopo" class="h-6 w-6 rounded-3xl" />
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-200">Mkopo</p>
                    
                </div>
                
        </div>
    </section>

    <section class="fixed bottom-3 left-1/2 z-30 w-[calc(100%-1.5rem)] -translate-x-1/2 rounded-[28px] bg-slate-950/95 p-2 shadow-2xl backdrop-blur-sm">
        <div class="grid grid-cols-5 gap-2">
            @php
                $bottomNav = [
                    ['label' => 'Dashboard', 'icon' => asset('assets/icons/home.png'), 'route' => route('client.dashboard')],
                    ['label' => 'VIP', 'icon' => asset('assets/icons/vip.png'), 'route' => route('client.vip')],
                    ['label' => 'Active VIP', 'icon' => asset('assets/icons/active-vip.png'), 'route' => route('client.active-vip')],
                    ['label' => 'Team', 'icon' => asset('assets/icons/team.png'), 'route' => route('client.team')],
                    ['label' => 'Bonus', 'icon' => asset('assets/icons/bonus.png'), 'route' => route('client.bonus')],
                ];
            @endphp

            @foreach ($bottomNav as $item)
                <a href="{{ $item['route'] }}" class="inline-flex flex-col items-center justify-center rounded-[10px] bg-slate-900 px-2 py-2 text-[10px] text-slate-300 transition hover:bg-slate-800 hover:text-white">
                    <img src="{{ $item['icon'] }}" alt="{{ $item['label'] }}" class="h-5 w-5" />
                    <span class="mt-1">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

</div>
@endsection
            </div>