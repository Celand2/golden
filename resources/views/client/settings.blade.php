@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Paramètres</h1>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informations du compte -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Informations du compte</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-slate-200">
                    <span class="text-slate-600">Nom complet</span>
                    <span class="font-semibold text-slate-900">{{ Auth::user()->name }}</span>
                </div>
                
                <div class="flex justify-between py-2 border-b border-slate-200">
                    <span class="text-slate-600">Téléphone</span>
                    <span class="font-semibold text-slate-900">{{ Auth::user()->phone ?? 'Non renseigné' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200">
                    <span class="text-slate-600">Membre depuis</span>
                    <span class="font-semibold text-slate-900">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Modifier les coordonnées -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Modifier mes coordonnées</h2>
            <form action="{{ route('client.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           placeholder="+257 XX XXX XXX">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full rounded-lg bg-emerald-600 px-4 py-3 font-semibold text-white transition hover:bg-emerald-700">
                    Mettre à jour
                </button>
            </form>
        </div>

        <!-- Changer le mot de passe -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Changer le mot de passe</h2>
            <form action="{{ route('client.settings.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                           required>
                </div>

                <button type="submit" 
                        class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700">
                    Changer le mot de passe
                </button>
            </form>
        </div>

        <!-- Déconnexion -->
        <div class="border-t border-slate-200 pt-6">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                @csrf
                <button type="submit" 
                        class="w-full rounded-lg bg-red-600 px-4 py-3 font-semibold text-white transition hover:bg-red-700">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection