@extends('layouts.auth')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold text-slate-900">Inscription</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Téléphone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Mot de passe</label>
            <input type="password" name="password" required class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Code de parrainage (optionnel)</label>
            <input type="text" name="referral_code" value="{{ old('referral_code', $referral) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none" />
        </div>
        <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-white">Créer un compte</button>
    </form>
    <p class="mt-4 text-center text-sm text-slate-500">Déjà membre ? <a href="{{ route('login') }}" class="font-semibold text-emerald-600">Se connecter</a></p>
</div>
@endsection
