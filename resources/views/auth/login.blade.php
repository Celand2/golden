@extends('layouts.auth')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold text-slate-900">Connexion</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Numéro de téléphone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required autofocus placeholder="+25700000000" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none @error('phone') border-red-500 @enderror" />
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Mot de passe</label>
            <input type="password" name="password" required class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none @error('password') border-red-500 @enderror" />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-white font-semibold hover:bg-emerald-700">Se connecter</button>
    </form>
    <p class="mt-4 text-center text-sm text-slate-500">Pas encore inscrit ? <a href="{{ route('register') }}" class="font-semibold text-emerald-600">Créer un compte</a></p>
</div>
@endsection
