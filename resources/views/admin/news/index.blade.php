@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Gestion des Actualités</h1>
        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">
            + Nouvelle Actualité
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg bg-white shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">Date de publication</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">Créé le</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($news as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $item->title }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($item->is_published)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Publié</span>
                            @else
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">Brouillon</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->published_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.news.edit', $item) }}" class="text-blue-600 hover:text-blue-800">Modifier</a>
                            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette actualité?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:text-red-800">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Aucune actualité pour le moment</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $news->links() }}
    </div>
</div>
@endsection