@extends('layouts.client')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Mon équipe</h1>
                <p class="mt-2 text-sm text-slate-500">Vos filleuls directs sont listés ici avec leur téléphone.</p>
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

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Commission L1</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($commissionTotals[1] ?? 0, 0, ',', ' ') }} FBU</p>
                <p class="mt-2 text-sm text-slate-500">Filleuls directs</p>
            </div>
            <div class="rounded-3xl border border-slate-200 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Commission L2</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($commissionTotals[2] ?? 0, 0, ',', ' ') }} FBU</p>
                <p class="mt-2 text-sm text-slate-500">Filleuls de vos filleuls</p>
            </div>
            <div class="rounded-3xl border border-slate-200 p-5">
                <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Commission L3</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($commissionTotals[3] ?? 0, 0, ',', ' ') }} FBU</p>
                <p class="mt-2 text-sm text-slate-500">Troisième niveau</p>
            </div>
        </div>

        <div class="mt-8">
            @if ($level1->isEmpty() && $level2->isEmpty() && $level3->isEmpty())
                <div class="rounded-3xl border border-slate-200 p-6 text-center text-slate-500">
                    Vous n’avez pas encore de filleuls dans votre équipe L1, L2 ou L3.
                </div>
            @else
                <div class="grid gap-6">
                    @foreach (['L1' => $level1, 'L2' => $level2, 'L3' => $level3] as $level => $members)
                        <div class="rounded-3xl border border-slate-200 p-6 shadow-sm">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-slate-500">Niveau {{ $level }}</p>
                                    <h2 class="mt-2 text-xl font-semibold text-slate-900">{{ $members->count() }} membre(s)</h2>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Commission totale : {{ number_format($commissionTotals[(int)substr($level, 1)] ?? 0, 0, ',', ' ') }} FBU</span>
                            </div>

                            @if ($members->isEmpty())
                                <p class="text-sm text-slate-500">Aucun membre sur ce niveau.</p>
                            @else
                                <div class="grid gap-4">
                                    @foreach ($members as $member)
                                        <div class="rounded-3xl border border-slate-200 p-4 bg-slate-50">
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                                <div>
                                                    <p class="text-sm text-slate-500">{{ $member->name }}</p>
                                                    <p class="text-sm text-slate-700">{{ $member->phone ?? 'Téléphone non renseigné' }}</p>
                                                </div>
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $member->role }}</span>
                                            </div>
                                            <div class="mt-3 text-sm text-slate-600">
                                                <p>Inscrit le : <span class="font-semibold text-slate-900">{{ $member->created_at->format('d/m/Y') }}</span></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
