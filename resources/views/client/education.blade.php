@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Éducation Financière</h1>

        <div class="space-y-6">
            <!-- Introduction -->
            <div class="rounded-lg bg-gradient-to-r from-emerald-50 to-blue-50 p-6">
                <h2 class="text-xl font-semibold text-slate-900 mb-3">Bienvenue dans votre espace éducation</h2>
                <p class="text-slate-700">Découvrez des ressources pour améliorer vos connaissances en investissement et finance personnelle.</p>
            </div>

            <!-- Modules d'éducation -->
            <div class="grid gap-4">
                <div class="rounded-lg border border-slate-200 p-5 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-2xl">
                            📚
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Les bases de l'investissement</h3>
                            <p class="text-sm text-slate-600 mb-3">Apprenez les concepts fondamentaux de l'investissement et comment faire fructifier votre argent.</p>
                            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">Débutant</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 p-5 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-2xl">
                            💡
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Stratégies de parrainage</h3>
                            <p class="text-sm text-slate-600 mb-3">Maîtrisez l'art du parrainage et développez votre réseau pour maximiser vos revenus.</p>
                            <span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">Intermédiaire</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 p-5 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 text-2xl">
                            🎯
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Gestion des risques</h3>
                            <p class="text-sm text-slate-600 mb-3">Comprenez comment gérer les risques liés aux investissements et protéger votre capital.</p>
                            <span class="inline-block rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800">Avancé</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 p-5 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 text-2xl">
                            📊
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Analyse de marché</h3>
                            <p class="text-sm text-slate-600 mb-3">Apprenez à analyser les tendances du marché pour prendre de meilleures décisions d'investissement.</p>
                            <span class="inline-block rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800">Avancé</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conseils pratiques -->
            <div class="rounded-lg bg-yellow-50 p-6 border border-yellow-200">
                <h3 class="text-lg font-semibold text-yellow-900 mb-3">💡 Conseil du jour</h3>
                <p class="text-sm text-yellow-800">
                    La diversification est la clé d'un portefeuille d'investissement réussi. Ne mettez pas tous vos œufs dans le même panier !
                </p>
            </div>

            <!-- Ressources supplémentaires -->
            <div class="rounded-lg bg-slate-50 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Ressources utiles</h3>
                <ul class="space-y-2 text-sm text-slate-700">
                    <li>• Consultez régulièrement les actualités pour rester informé</li>
                    <li>• Participez au weekly challenge pour améliorer vos compétences</li>
                    <li>• Utilisez la section statistiques pour suivre votre progression</li>
                    <li>• Contactez le support pour toute question</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection