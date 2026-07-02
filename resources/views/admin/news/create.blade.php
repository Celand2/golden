@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Créer une Actualité</h1>

        <form action="{{ route('admin.news.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Titre</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-slate-700 mb-2">Contenu</label>
                <textarea name="content" id="content" rows="10" required
                          class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_published" class="text-sm font-medium text-slate-700">Publier immédiatement</label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-3 text-white hover:bg-emerald-700">
                    Créer l'actualité
                </button>
                <a href="{{ route('admin.news.index') }}" class="rounded-lg bg-slate-200 px-6 py-3 text-slate-700 hover:bg-slate-300">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection