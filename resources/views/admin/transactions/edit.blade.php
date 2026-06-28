@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Modifier une transaction</h1>
        <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}" class="mt-6 grid gap-5 lg:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Statut</label>
                <select name="status" required class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $transaction->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $transaction->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Note</label>
                <textarea name="note" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3">{{ old('note', $transaction->note) }}</textarea>
            </div>
            <div class="lg:col-span-2 flex items-center justify-between gap-3">
                <a href="{{ route('admin.transactions.index') }}" class="rounded-3xl bg-slate-100 px-5 py-3 text-slate-700 hover:bg-slate-200">Annuler</a>
                <button type="submit" class="rounded-3xl bg-emerald-600 px-5 py-3 text-white">Mettre à jour</button>
            </div>
        </form>
    </section>
</div>
@endsection
