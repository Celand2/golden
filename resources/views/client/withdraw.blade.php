@extends('layouts.client')

@section('content')
<div class="mx-auto max-w-xl rounded-3xl bg-white p-8 shadow-lg">
    <h1 class="mb-4 text-2xl font-semibold text-slate-900">Demande de retrait</h1>
    <form method="POST" action="{{ route('client.withdraw') }}" class="space-y-5">
        @csrf
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Montant (minimum 5 000 FBU)</label>
            <input type="number" name="amount" min="5000" value="{{ old('amount') }}" required class="w-full rounded-3xl border border-slate-200 px-4 py-3" />
        </div>
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">Téléphone Lumicash</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full rounded-3xl border border-slate-200 px-4 py-3" />
        </div>
        <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-4 py-3 text-white">Soumettre la demande</button>
    </form>
</div>
@endsection
