@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Notifications</h1>
                <p class="mt-2 text-sm text-slate-500">Toutes les alertes envoyées aux utilisateurs de l'application.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center rounded-3xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Non lues : {{ $unreadCount }}</span>
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="rounded-3xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Tout marquer lu</button>
                </form>
            </div>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Utilisateur</th>
                    <th class="px-4 py-3 text-left font-semibold">Titre</th>
                    <th class="px-4 py-3 text-left font-semibold">Message</th>
                    <th class="px-4 py-3 text-left font-semibold">Date</th>
                    <th class="px-4 py-3 text-center font-semibold">Statut</th>
                    <th class="px-4 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($notifications as $notification)
                    <tr class="{{ $notification->is_read ? '' : 'bg-blue-50' }}">
                        <td class="px-4 py-3">{{ $notification->user?->name ?? 'Utilisateur supprimé' }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $notification->title }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $notification->message }}</td>
                        <td class="px-4 py-3">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $notification->is_read ? 'bg-slate-100 text-slate-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $notification->is_read ? 'Lue' : 'Non lue' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2 justify-center">
                                @unless($notification->is_read)
                                    <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="rounded-2xl bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">Marquer lu</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" onsubmit="return confirm('Supprimer cette notification ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-2xl bg-red-100 px-4 py-2 text-red-700 hover:bg-red-200">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Aucune notification trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="bg-white p-6 rounded-[32px] shadow-sm">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
