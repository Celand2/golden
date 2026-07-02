@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Weekly Challenge</h1>
        <p class="text-slate-600 mb-6">Devinez parmi les 3 grands parrains de la semaine !</p>

        <!-- Top 3 Referrers -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">🏆 Top 3 Parrains de la Semaine</h2>
            
            @if($topReferrers->isEmpty())
                <div class="rounded-lg bg-slate-50 p-8 text-center">
                    <p class="text-slate-600">Aucun parrainage cette semaine pour le moment.</p>
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($topReferrers as $index => $referrer)
                        <div class="rounded-lg border-2 {{ $index === 0 ? 'border-yellow-400 bg-yellow-50' : ($index === 1 ? 'border-gray-300 bg-gray-50' : 'border-orange-300 bg-orange-50') }} p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-300' : 'bg-orange-300') }} text-white text-xl font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $referrer->name }}</p>
                                    <p class="text-sm text-slate-600">{{ $referrer->phone ?? 'Téléphone non renseigné' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-slate-900">{{ $referrer->week_referrals_count }}</p>
                                    <p class="text-xs text-slate-600">parrainages</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- User's Performance -->
        <div class="rounded-lg bg-gradient-to-r from-emerald-50 to-blue-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Votre Performance</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-600">Votre rang cette semaine</p>
                    <p class="text-3xl font-bold text-slate-900">
                        {{ $userRank ? '#' . $userRank : 'Non classé' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-slate-600">Vos parrainages cette semaine</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $userWeekReferrals }}</p>
                </div>
            </div>
            
            @if($userRank && $userRank <= 3)
                <div class="mt-4 rounded-lg bg-yellow-100 p-4 text-center">
                    <p class="text-lg font-semibold text-yellow-900">🎉 Félicitations ! Vous êtes dans le top 3 !</p>
                </div>
            @endif
        </div>

        <!-- Info -->
        <div class="mt-6 rounded-lg bg-blue-50 p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Comment ça marche ?</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>• Le classement est basé sur le nombre de nouveaux parrainages cette semaine</li>
                <li>• La semaine commence le lundi et se termine le dimanche</li>
                <li>• Les 3 premiers parrains de la semaine sont récompensés</li>
                <li>• Continuez à parrainer pour monter dans le classement !</li>
            </ul>
        </div>
    </div>
</div>
@endsection