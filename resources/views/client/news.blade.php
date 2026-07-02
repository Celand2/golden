@extends('layouts.client')

@section('content')
<div class="space-y-4 px-3 pb-24 pt-4">
    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Retour au dashboard</a>
    
    <div class="rounded-[12px] bg-white p-6 shadow-lg">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Actualités</h1>

        @if($news->isEmpty())
            <div class="rounded-lg bg-slate-50 p-8 text-center">
                <p class="text-slate-600">Aucune actualité disponible pour le moment.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($news as $item)
                    <div class="rounded-lg border border-slate-200 p-5 hover:shadow-md transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-slate-900 mb-2">{{ $item->title }}</h2>
                                <div class="text-sm text-slate-600 whitespace-pre-line">{{ $item->content }}</div>
                                <p class="mt-3 text-xs text-slate-500">
                                    Publié le {{ $item->published_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $news->links() }}
            </div>
        @endif
    </div>
</div>
@endsection