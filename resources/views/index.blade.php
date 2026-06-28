@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <section class="rounded-[32px] bg-emerald-600 px-6 py-14 text-white shadow-2xl shadow-emerald-900/20 sm:px-10">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl space-y-6">
                    <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-semibold uppercase tracking-[0.24em] text-emerald-100">Plateforme VIP d'investissement</span>
                    <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">GoldenRise-INVEST: Investissez, parrainez et récoltez des gains VIP.</h1>
                    <p class="max-w-xl text-lg text-emerald-100/90">Gérez vos dépôts Lumicash, achetez des plans VIP, réclamez vos gains quotidiens et montez en statut premium grâce à votre réseau de parrainage.</p>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-3xl bg-white px-6 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-slate-100">Créer un compte</a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-3xl border border-white/40 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/20">Se connecter</a>
                    </div>
                </div>

                <div class="grid max-w-xl gap-4 sm:grid-cols-2">
                    <div class="rounded-[32px] bg-white/10 p-6 shadow-xl shadow-black/10 backdrop-blur">
                        <p class="text-sm uppercase tracking-[0.24em] text-emerald-100">Dépôt minimum</p>
                        <p class="mt-4 text-3xl font-semibold">30 000 FBU</p>
                    </div>
                    <div class="rounded-[32px] bg-white/10 p-6 shadow-xl shadow-black/10 backdrop-blur">
                        <p class="text-sm uppercase tracking-[0.24em] text-emerald-100">Retrait minimum</p>
                        <p class="mt-4 text-3xl font-semibold">5 000 FBU</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Plans VIP</h2>
            <p class="mt-3 text-slate-600">Des rendements fixes de 7% par jour pendant 180 jours. Choisissez le plan qui correspond à vos objectifs.</p>
        </div>
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Parrainage 3 niveaux</h2>
            <p class="mt-3 text-slate-600">9% pour L1, 2% pour L2 et 1% pour L3. Faites grandir votre équipe et gagnez plus avec vos filleuls.</p>
        </div>
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Premium</h2>
            <p class="mt-3 text-slate-600">Obtenez le statut premium automatiquement à partir de 30 filleuls directs vérifiés.</p>
        </div>
    </section>

    <section class="grid gap-10 lg:grid-cols-2">
        <div class="rounded-[32px] bg-white p-10 shadow-sm">
            <h2 class="text-3xl font-semibold text-slate-900">Gestion simple des dépôts Lumicash</h2>
            <p class="mt-4 text-slate-600">Soumettez vos demandes de dépôt, attendez l'approbation admin et suivez vos transactions directement depuis votre dashboard client.</p>
            <ul class="mt-6 space-y-3 text-slate-600">
                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Approbation manuelle sécurisée</li>
                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Coordonnées Lumicash modifiables par l'admin</li>
                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-600"></span>Suivi clair des statuts de dépôt / retrait</li>
            </ul>
        </div>

        <div class="rounded-[32px] bg-white p-10 shadow-sm">
            <h2 class="text-3xl font-semibold text-slate-900">Dashboard de parrainage</h2>
            <p class="mt-4 text-slate-600">Visualisez votre lien de parrainage, le nombre de filleuls et l'historique de commissions pour booster votre réseau.</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl bg-emerald-50 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-emerald-700">Lien</p>
                    <p class="mt-3 text-sm text-slate-700">https://domaine.com/register?ref=XXXXXX</p>
                </div>
                <div class="rounded-3xl bg-emerald-50 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-emerald-700">Commission L1</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">9%</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-[32px] bg-slate-900 px-8 py-10 text-white shadow-2xl shadow-black/10">
        <div class="max-w-4xl">
            <div class="grid gap-8 lg:grid-cols-3">
                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-emerald-300">Pourquoi GoldenRise</p>
                    <h2 class="mt-3 text-3xl font-semibold">Une interface conçue pour les investisseurs VIP au Burundi.</h2>
                </div>
                <div class="space-y-4 text-slate-200">
                    <p class="font-semibold">Commissions multi-niveaux</p>
                    <p>Gagnez sur 3 niveaux de parrainage avec un calcul automatique dès l'approbation du dépôt.</p>
                </div>
                <div class="space-y-4 text-slate-200">
                    <p class="font-semibold">Claim journalier</p>
                    <p>Réclamez vos gains accumulés à tout moment depuis votre investissement actif.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
