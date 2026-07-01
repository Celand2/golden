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
                <div class="flex items-center gap-3">
                    <button id="admin-sidebar-toggle" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white transition hover:bg-emerald-500 md:hidden">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold tracking-tight">GoldenRise Admin</a>
                </div>

                <div class="flex items-center gap-3">
                    <nav class="hidden items-center gap-2 text-sm md:flex">
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Utilisateurs</a>
                        <a href="{{ route('admin.vip-plans.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Plans VIP</a>
                        <a href="{{ route('admin.transactions.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Transactions</a>
                        <a href="{{ route('admin.notifications.index') }}" class="rounded-full px-3 py-2 hover:bg-white/10">Notifications</a>
                    </nav>
                    <div class="hidden gap-3 rounded-3xl bg-white/10 px-4 py-2 text-sm sm:flex items-center">
                        <span class="font-medium">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md bg-white px-3 py-1 text-emerald-700">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex min-h-[calc(100vh-80px)]">
            <aside id="admin-sidebar" class="hidden w-72 shrink-0 border-r border-slate-200 bg-white p-5 shadow-sm md:block">
                <div class="mb-8">
                    <h2 class="text-sm uppercase tracking-[0.2em] text-slate-500">Menu Admin</h2>
                    <p class="mt-2 text-sm text-slate-600">Accès rapide aux écrans de gestion.</p>
                </div>
                <nav class="space-y-2 text-sm text-slate-700">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Utilisateurs</a>
                    <a href="{{ route('admin.vip-plans.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Plans VIP</a>
                    <a href="{{ route('admin.transactions.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Transactions</a>
                    <a href="{{ route('admin.notifications.index') }}" class="block rounded-2xl px-4 py-3 hover:bg-emerald-50">Notifications</a>
                </nav>
                <div class="mt-10 rounded-3xl bg-slate-50 p-4 text-sm text-slate-700">
                    <p class="font-semibold text-slate-900">Contrôle total</p>
                    <p class="mt-2 leading-6">Gérez les utilisateurs, les transactions, les plans VIP et les notifications depuis un seul espace.</p>
                </div>
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
        toggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
