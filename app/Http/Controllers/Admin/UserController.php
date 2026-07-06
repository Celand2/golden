<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::orderByDesc('created_at')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,standard,premium',
            'is_active' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,standard,premium',
            'is_active' => 'nullable|boolean',
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->role = $data['role'];
        $user->is_active = $request->boolean('is_active', true);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

   public function destroy(User $user)
{
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
}

    public function resetPassword(Request $request, User $user)
    {
        // Générer un mot de passe aléatoire de 8 caractères
        $newPassword = \Illuminate\Support\Str::random(8);

        // Hash et enregistrer
        $user->update(['password' => Hash::make($newPassword)]);

        // Retourner avec le mot de passe visible une seule fois
        return back()->with('password_reset', [
            'user_name' => $user->name,
            'temporary_password' => $newPassword,
            'message' => "Mot de passe réinitialisé pour {$user->name}. Communiquez ce mot de passe au user.",
        ]);
    }
}
