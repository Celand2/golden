@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Utilisateurs</h1>
                <p class="mt-2 text-sm text-slate-500">Gestion complète des comptes clients et administrateurs.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-3xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Nouveau utilisateur</a>
        </div>
    </section>

    <section class="rounded-[32px] bg-white p-6 shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Nom</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-left font-semibold">Téléphone</th>
                    <th class="px-4 py-3 text-left font-semibold">Rôle</th>
                    <th class="px-4 py-3 text-left font-semibold">Actif</th>
                    <th class="px-4 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->phone ?? '-' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-3">{{ $user->is_active ? 'Oui' : 'Non' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2 justify-center">
                                <a href="{{ route('admin.users.edit', $user) }}" class="rounded-2xl bg-slate-100 px-4 py-2 text-slate-700 hover:bg-slate-200">Modifier</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-2xl bg-red-100 px-4 py-2 text-red-700 hover:bg-red-200">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
