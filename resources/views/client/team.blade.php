@extends('layouts.client')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Mon équipe</h1>
                <p class="mt-2 text-sm text-slate-500">Vos filleuls directs avec leur téléphone et la commission qu'ils vous ont rapportée.</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
        </div>
        <div class="mt-6 rounded-3xl bg-slate-50 border border-slate-200 p-5">
            <p class="text-sm uppercase text-slate-500">Lien de parrainage</p>
            <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center">
                <input id="team-referral-link" readonly type="text" value="{{ $user->referral_link }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900" />
                <button id="copy-team-referral-link" type="button" data-copy-target="#team-referral-link" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-4 py-3 text-white transition hover:bg-emerald-700">Copier</button>
            </div>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="rounded-3xl border border-slate-200 p-5 mb-8">
            <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Commission totale L1</p>
            <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totalCommissionL1, 0, ',', ' ') }} FBU</p>
            <p class="mt-2 text-sm text-slate-500">{{ $level1->count() }} filleul(s) direct(s)</p>
        </div>

        @if ($level1->isEmpty())
            <div class="rounded-3xl border border-slate-200 p-6 text-center text-slate-500">
                Vous n'avez pas encore de filleuls directs.
            </div>
        @else
            <div class="grid gap-4">
                @foreach ($level1 as $member)
                    <div class="rounded-3xl border border-slate-200 p-4 bg-slate-50">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $member->name }}</p>
                                <p class="text-sm text-slate-600">{{ $member->phone ?? 'Téléphone non renseigné' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-[0.15em] text-slate-400">Commission accumulée</p>
                                <p class="text-lg font-bold text-emerald-600">{{ number_format($commissionPerMember[$member->id] ?? 0, 0, ',', ' ') }} FBU</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection