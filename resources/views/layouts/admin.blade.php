<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GoldenRise-INVEST') }} - Admin</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.4/dist/tailwind.min.css">
    @endif
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="sticky top-0 z-40 bg-emerald-700 text-white shadow-sm">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                <div class="flex items-center gap-4">
                    <button id="admin-sidebar-toggle" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white transition hover:bg-emerald-500 md:hidden">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/icons/images/logo.png') }}" alt="GoldenRise Logo" class="h-8 w-auto">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold tracking-tight">GoldenRise Admin</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <nav class="hidden items-center gap-2 text-sm md:flex">
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Utilisateurs</a>
                        <a href="{{ route('admin.vip-plans.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Plans VIP</a>
                        <a href="{{ route('admin.deposits.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Dépôts</a>
                        <a href="{{ route('admin.withdrawals.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Retraits</a>
                    </nav>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.notifications.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-full hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </a>

                        <div class="hidden gap-3 rounded-3xl bg-white/10 px-4 py-2 text-sm sm:flex items-center">
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="rounded-md bg-white px-3 py-1 text-emerald-700 hover:bg-slate-100 transition">Déconnexion</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline sm:hidden">
                            @csrf
                            <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full hover:bg-white/10 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Overlay pour sidebar mobile -->
        <div id="admin-sidebar-overlay" class="hidden fixed inset-0 z-30 bg-black/50 md:hidden"></div>

        <div class="flex min-h-[calc(100vh-80px)]">
            <aside id="admin-sidebar" class="hidden fixed left-0 top-[80px] z-40 h-[calc(100vh-80px)] w-72 overflow-y-auto border-r border-slate-200 bg-white p-5 shadow-lg md:static md:z-0 md:h-auto md:overflow-visible md:shadow-sm">
                <div class="mb-8">
                    <h2 class="text-sm uppercase tracking-[0.2em] text-slate-500">Menu Admin</h2>
                    <p class="mt-2 text-sm text-slate-600">Accès rapide aux écrans de gestion.</p>
                </div>
                <nav class="space-y-2 text-sm text-slate-700">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Utilisateurs</a>
                    <a href="{{ route('admin.vip-plans.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Plans VIP</a>
                    <a href="{{ route('admin.deposits.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Dépôts</a>
                    <a href="{{ route('admin.withdrawals.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Retraits</a>
                    <a href="{{ route('admin.notifications.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Notifications</a>
                </nav>
            </aside>

            <main class="flex-1 px-4 py-6 sm:px-6">
                @if (session('success'))
                    <div class="mb-4 rounded-xl bg-emerald-100 border border-emerald-200 p-4 text-emerald-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-red-900">
                        <ul class="space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('admin-sidebar-toggle');
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');

        toggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('hidden');
            overlay?.classList.toggle('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar?.classList.add('hidden');
            overlay?.classList.add('hidden');
        });
    </script>
</body>
</html>
