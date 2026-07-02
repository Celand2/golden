@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Bonus Parrainage</h1>
        <p class="text-slate-600 mb-6">Réclamez vos bonus en fonction de votre équipe de parrainage</p>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistiques des parrainages -->
        <div class="mb-8 rounded-lg bg-gradient-to-br from-emerald-50 to-blue-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Vos statistiques</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-lg bg-white p-4 text-center">
                    <p class="text-3xl font-bold text-emerald-600">{{ $level1Count }}</p>
                    <p class="text-sm text-slate-600 mt-1">Parrainages Niveau 1</p>
                </div>
                <div class="rounded-lg bg-white p-4 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $activeLevel1Count }}</p>
                    <p class="text-sm text-slate-600 mt-1">VIP Actifs (Niveau 1)</p>
                </div>
            </div>
        </div>

        <!-- Bonus disponibles -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Bonus disponibles</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg border @if($activeLevel1Count >= 10) border-emerald-500 bg-emerald-50 @else border-slate-200 bg-slate-50 @endif p-4">
                    <div>
                        <p class="font-semibold text-slate-900">10 Parrainages VIP Actifs</p>
                        <p class="text-sm text-slate-600">Récompense : 5 000 FBU</p>
                    </div>
                    @if($activeLevel1Count >= 10)
                        @if($claimedBonuses[10] ?? false)
                            <span class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Réclamé</span>
                        @else
                            <form action="{{ route('client.bonus.claim') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="level" value="10">
                                <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Réclamer
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="text-sm text-slate-500">{{ $activeLevel1Count }}/10</span>
                    @endif
                </div>

                <div class="flex items-center justify-between rounded-lg border @if($activeLevel1Count >= 20) border-emerald-500 bg-emerald-50 @else border-slate-200 bg-slate-50 @endif p-4">
                    <div>
                        <p class="font-semibold text-slate-900">20 Parrainages VIP Actifs</p>
                        <p class="text-sm text-slate-600">Récompense : 10 000 FBU</p>
                    </div>
                    @if($activeLevel1Count >= 20)
                        @if($claimedBonuses[20] ?? false)
                            <span class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Réclamé</span>
                        @else
                            <form action="{{ route('client.bonus.claim') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="level" value="20">
                                <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Réclamer
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="text-sm text-slate-500">{{ $activeLevel1Count }}/20</span>
                    @endif
                </div>

                <div class="flex items-center justify-between rounded-lg border @if($activeLevel1Count >= 30) border-emerald-500 bg-emerald-50 @else border-slate-200 bg-slate-50 @endif p-4">
                    <div>
                        <p class="font-semibold text-slate-900">30 Parrainages VIP Actifs</p>
                        <p class="text-sm text-slate-600">Récompense : 15 000 FBU</p>
                    </div>
                    @if($activeLevel1Count >= 30)
                        @if($claimedBonuses[30] ?? false)
                            <span class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Réclamé</span>
                        @else
                            <form action="{{ route('client.bonus.claim') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="level" value="30">
                                <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Réclamer
                                </button>
                            </form>
                        @endif
                    @else
                        <span class="text-sm text-slate-500">{{ $activeLevel1Count }}/30</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="rounded-lg bg-blue-50 p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Comment ça marche ?</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li>• Parrainez des amis et encouragez-les à investir dans un plan VIP</li>
                <li>• Plus vous avez de parrainages actifs, plus vos bonus augmentent</li>
                <li>• Les bonus sont versés directement sur votre solde principal (Main Balance)</li>
                <li>• Chaque bonus ne peut être réclamé qu'une seule fois</li>
            </ul>
        </div>
    </div>
</div>
@endsection