@extends('layouts.auth')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-lg">
<div class="flex items-center justify-center gap-3">
                <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-white/20">
                    <img src="{{ asset('assets/icons/logo.png') }}" alt="Logo" class="h-full w-full" />
                </div>
            </div>

    <h1 class="flex items-center justify-center mb-4 text-2xl font-semibold text-slate-900">Inscription</h1>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="flex  flex-col items-center w-full gap-3 ">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700" >Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-[12px] border border-slate-200 px-2 py-1.5 focus:border-emerald-500 focus:outline-none @error('name') border-red-500 @enderror" />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Numéro de téléphone *</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+25700000000" class="w-full rounded-xl border border-slate-200 px-2 py-1.5 focus:border-emerald-500 focus:outline-none @error('phone') border-red-500 @enderror" />
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Mot de passe *</label>
            <input type="password" name="password" required class="w-full rounded-xl border border-slate-200 px-2 py-1.5 focus:border-emerald-500 focus:outline-none @error('password') border-red-500 @enderror" />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Confirmer le mot de passe *</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-200 px-2 py-1.5 focus:border-emerald-500 focus:outline-none @error('password_confirmation') border-red-500 @enderror" />
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Code de parrainage (optionnel)</label>
            <input type="text" name="referral_code" value="{{ old('referral_code', $referral) }}" placeholder="Ex: ABC123" class="w-full rounded-xl border border-slate-200 px-2 py-1.5 focus:border-emerald-500 focus:outline-none @error('referral_code') border-red-500 @enderror" />
            @error('referral_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
        <button type="submit" class="w-full rounded-xl bg-emerald-600 px-2 py-1.5 text-white font-semibold hover:bg-emerald-700">Créer un compte</button>
        </div>
    </form>
</div>
    <p class="mt-4 text-center text-sm text-slate-500">Déjà membre ? <a href="{{ route('login') }}" class="font-semibold text-emerald-600">Se connecter</a></p>
</div>
@endsection
