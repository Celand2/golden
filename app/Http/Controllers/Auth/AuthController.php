<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['phone' => 'Identifiants invalides.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('client.dashboard')
        );
    }

    public function showRegister(Request $request)
    {
        return view('auth.register', [
            'referral' => $request->query('ref'),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referrer = null;

        if (! empty($data['referral_code'])) {
            $referrer = User::where('referral_code', $data['referral_code'])->first();
        }

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'referred_by' => $referrer?->id,
        ]);

        // Incrémenter referral_count du parrain L1
        if ($referrer) {
            $referrer->increment('referral_count');

            // Créer notification de nouveau filleul
            \App\Models\Notification::create([
                'user_id' => $referrer->id,
                'type' => 'new_referral',
                'title' => 'Nouveau filleul inscrit',
                'message' => "{$user->name} ({$user->phone}) s'est inscrit avec votre code de parrainage!",
            ]);

            // Vérifier si le parrain atteint 30 filleuls et passer en Premium
            if ($referrer->referral_count >= 30 && $referrer->role === 'standard') {
                $referrer->update(['role' => 'premium']);

                \App\Models\Notification::create([
                    'user_id' => $referrer->id,
                    'type' => 'premium_upgrade',
                    'title' => 'Félicitations!',
                    'message' => 'Vous êtes passé au statut Premium après avoir atteint 30 filleuls directs!',
                ]);
            }
        }

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
