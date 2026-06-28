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
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        @if ($teamMembers->isEmpty())
            <div class="rounded-3xl border border-slate-200 p-6 text-center text-slate-500">
                Vous n’avez pas encore de filleuls.
            </div>
        @else
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach ($teamMembers as $member)
                    <div class="rounded-3xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase text-slate-500">Filleul direct</p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-900">{{ $member->name }}</h2>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $member->role }}</span>
                        </div>
                        <div class="mt-4 text-sm text-slate-600">
                            <p>Téléphone : <span class="font-semibold text-slate-900">{{ $member->phone ?? 'Non renseigné' }}</span></p>
                            <p class="mt-2">Inscrit le : <span class="font-semibold text-slate-900">{{ $member->created_at->format('d/m/Y') }}</span></p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
