@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <section class="rounded-[32px] bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-slate-900">Modifier l’utilisateur</h1>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 grid gap-5 lg:grid-cols-2">
            @csrf
            @method('PUT')
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nom</label>
                <input name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Téléphone</label>
                <input name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Rôle</label>
                <select name="role" required class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    <option value="standard" {{ old('role', $user->role) === 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="premium" {{ old('role', $user->role) === 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Nouveau mot de passe</label>
                <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Confirmer le mot de passe</label>
                <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3" />
            </div>
            <div class="lg:col-span-2 flex items-center gap-3">
                <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                    <input name="is_active" type="checkbox" value="1" {{ $user->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-emerald-600" />
                    Actif
                </label>
            </div>
            <div class="lg:col-span-2">
                <button type="submit" class="rounded-3xl bg-emerald-600 px-5 py-3 text-white">Mettre à jour</button>
            </div>
        </form>
    </section>
</div>
@endsection
