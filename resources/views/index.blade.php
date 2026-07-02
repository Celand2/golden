@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <section class="flex flex-col lg:flex-row items-center rounded-[32px] bg-orange-400 px-3 py-7 gap-8 text-white shadow-2xl shadow-emerald-900/20 sm:px-10">
        <!-- Text Section -->
        <div class="flex-1 flex flex-col justify-center">
            <h1 class="text-3xl font-semibold tracking-tight sm:text-5xl">Investissez, parrainez et récoltez des gains VIP avec <span class="font-emerald-600">GoldenRise-INVEST</span>.</h1>
            <p class="mt-6 max-w-xl text-lg text-white/90">Gérez vos dépôts Lumicash, achetez des plans VIP, réclamez vos gains quotidiens.</p>
            <div class="mt-6 flex gap-4">
                @auth
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
                @else
                <a href="{{ route('login') }}" class="rounded-md bg-white px-3 py-1 text-emerald-600 border-emerald-600">Connexion</a>
                <a href="{{ route('register') }}" class="rounded-md border border-emerald-600 bg-emerald-600 px-3 py-1">commencer</a>
                @endauth
            </div>
        </div>

        <!-- Image Section -->
        <div class="flex-1 flex justify-center">
            <img src="{{ asset('assets/icons/images/hero.png') }}" alt="Hero Image" class="h-[320px] w-auto object-contain sm:h-[360px] lg:h-[400px]" />
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

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 rounded-[32px]">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-8 mb-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">GoldenRise-INVEST</h3>
                    <p class="text-slate-400">Plateforme d'investissement VIP pour les investisseurs avisés au Burundi.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liens Rapides</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition">Accueil</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">Plans VIP</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">Parrainage</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#" class="hover:text-emerald-400 transition">Contact</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition">Conditions d'utilisation</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-slate-400">Email: support@goldenrise.bi</p>
                    <p class="text-slate-400 mt-2">Téléphone: +257 xx xxx xxx</p>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-slate-400 text-sm">
                    <p>&copy; 2026 GoldenRise-INVEST. Tous droits réservés.</p>
                    <div class="flex gap-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-emerald-400 transition">Politique de confidentialité</a>
                        <a href="#" class="hover:text-emerald-400 transition">Mentions légales</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection