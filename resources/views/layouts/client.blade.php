<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GoldenRise-INVEST') }} - Espace client</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.4/dist/tailwind.min.css">
    @endif
</head>

<body class="min-h-screen bg-white text-slate-900">
    
        <!-- <header class="bg-white shadow-sm">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6">
                <a href="{{ route('client.dashboard') }}" class="text-lg font-semibold text-slate-900">GoldenRise Invest</a>
                <nav class="flex items-center gap-3 text-sm">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-emerald-600 px-3 py-1 text-white">Déconnexion</button>
                    </form>
                </nav>
            </div>
        </header> -->

        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6">
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
    
</body>
</html>
