@extends('layouts.client')

@section('content')
<div class="mx-auto max-w-2xl rounded-3xl bg-white p-8 shadow-lg">
    <h1 class="mb-6 text-2xl font-semibold text-slate-900">Faire un dépôt</h1>

    <div class="mb-6 rounded-3xl bg-slate-50 p-5 border border-slate-200">
        <p class="mb-2 text-sm uppercase tracking-[0.2em] text-slate-500">Coordonnées Lumicash</p>
        <div class="space-y-3 text-sm text-slate-700">
            <div class="rounded-3xl bg-white p-4 shadow-sm">
                <p class="text-slate-500">Nom</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $lumicash['name'] ?? 'Non défini' }}</p>
            </div>
            <div class="rounded-3xl bg-white p-4 shadow-sm">
                <p class="text-slate-500">Numéro</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $lumicash['phone'] ?? 'Non défini' }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('client.deposit') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Montant à déposer (minimum 30 000 FBU)</label>
            <input type="number" name="amount" min="30000" value="{{ old('amount') }}" required class="w-full rounded-3xl border border-slate-200 px-4 py-3" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Preuve de paiement</label>
            <input type="file" name="payment_proof" accept="image/png,image/jpeg,image/webp" required class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3" />
            <p class="mt-2 text-xs text-slate-500">Téléchargez une capture d’écran ou une photo de votre paiement.</p>
        </div>
        <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-4 py-3 text-white">Soumettre le dépôt</button>
    </form>
</div>
@endsection
