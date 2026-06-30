@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <section class="rounded-[32px] bg-orange-400 px-3 py-7 text-white shadow-2xl shadow-emerald-900/20 sm:px-10">
        <div class=" flex items-center justify-between mx-auto max-w-6xl">
            <div class=" flex gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl space-y-6">

                    <h6 class="text-xl font-semibold tracking-tight sm:text-5xl"> Investissez, parrainez et récoltez des gains VIP <br>avec <span class="font-emerald-600">GoldenRise-INVEST</span>.</h6>
                    <p class="max-w-xl text-lg text-emerald-100/90">Gérez vos dépôts Lumicash, achetez des plans VIP, réclamez vos gains quotidiens .</p>

                    </nav>
                     @auth
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>

                @else
                <a href="{{ route('login') }}" class="rounded-md bg-white px-3 py-1 text-emerald-600 border-emerald-600">Connexion</a>
                <a href="{{ route('register') }}" class="rounded-md border border-emerald-600 bg-emerald-600 px-3 py-1">commencer</a>
                @endauth
                </div>
                
            </div>

            <div class="grid max-w-xl gap-4 sm:grid-cols-2">
                <div class="rounded-[12px] bg-white/10 p-6 shadow-xl shadow-black/10 backdrop-blur">
                    <p class="text-sm uppercase tracking-[0.24em] text-emerald-100">Dépôt minimum</p>
                    <p class="mt-4 text-3xl font-semibold">30 000 FBU</p>
                </div>
                <div class="rounded-[12px] bg-white/10 p-6 shadow-xl shadow-black/10 backdrop-blur">
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
<footer>all right reserved </footer>
</div>
@endsection