@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Plans VIP</h1>
                <p class="mt-2 text-sm text-slate-500">Gérez les plans VIP que les clients peuvent acheter.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au tableau</a>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Ajouter un nouveau plan VIP</h2>
        <form method="POST" action="{{ route('admin.vip-plans.store') }}" class="mt-5 grid gap-4 lg:grid-cols-3">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nom du plan</label>
                <input name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Montant minimum</label>
                <input name="min_amount" type="number" step="0.01" value="{{ old('min_amount') }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Taux journalier (%)</label>
                <input name="daily_rate" type="number" step="0.01" value="{{ old('daily_rate') }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Durée (jours)</label>
                <input name="duration_days" type="number" value="{{ old('duration_days') }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div class="lg:col-span-2">
                <label class="mb-2 inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                    <input name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                    Actif
                </label>
            </div>
            <div class="lg:col-span-3">
                <button type="submit" class="rounded-3xl bg-emerald-600 px-5 py-3 text-white">Ajouter le plan</button>
            </div>
        </form>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Liste des plans VIP</h2>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Nom</th>
                        <th class="px-4 py-3 text-right font-semibold">Montant min.</th>
                        <th class="px-4 py-3 text-right font-semibold">Taux journalier</th>
                        <th class="px-4 py-3 text-right font-semibold">Durée</th>
                        <th class="px-4 py-3 text-center font-semibold">Statut</th>
                        <th class="px-4 py-3 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($plans as $plan)
                        <tr>
                            <td class="px-4 py-3">{{ $plan->name }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($plan->min_amount, 0, ',', ' ') }} FBU</td>
                            <td class="px-4 py-3 text-right">{{ $plan->daily_rate }}%</td>
                            <td class="px-4 py-3 text-right">{{ $plan->duration_days }} jours</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                                    <form method="POST" action="{{ route('admin.vip-plans.update', $plan) }}" class="grid gap-2 sm:grid-cols-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $plan->name }}" />
                                        <input type="hidden" name="min_amount" value="{{ $plan->min_amount }}" />
                                        <input type="hidden" name="daily_rate" value="{{ $plan->daily_rate }}" />
                                        <input type="hidden" name="duration_days" value="{{ $plan->duration_days }}" />
                                        <input type="hidden" name="is_active" value="{{ $plan->is_active ? 0 : 1 }}" />
                                        <button type="submit" class="rounded-2xl bg-slate-100 px-4 py-2 text-slate-700 hover:bg-slate-200">{{ $plan->is_active ? 'Désactiver' : 'Activer' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.vip-plans.destroy', $plan) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-2xl bg-red-100 px-4 py-2 text-red-700 hover:bg-red-200">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
