<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DailyClaim;
use App\Models\Investment;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\VipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activeInvestment = $user->investments()->where('status', 'active')->first();
        $pendingTransactions = $user->transactions()->where('status', 'pending')->get();

        return view('client.dashboard', [
            'user' => $user,
            'activeInvestment' => $activeInvestment,
            'vipPlans' => VipPlan::where('is_active', true)->get(),
            'notifications' => $user->notifications()->latest()->take(5)->get(),
            'pendingTransactions' => $pendingTransactions,
            'teamMembers' => $user->referrals()->get(),
        ]);
    }

    public function team(Request $request)
    {
        $user = $request->user();

        return view('client.team', [
            'user' => $user,
            'teamMembers' => $user->referrals()->get(),
        ]);
    }

    public function showVipPlans(Request $request)
    {
        return view('client.vip_plans', [
            'user' => $request->user(),
            'vipPlans' => VipPlan::where('is_active', true)->get(),
        ]);
    }

    public function investVipPlan(Request $request, VipPlan $vipPlan)
    {
        $user = $request->user();

        if (! $vipPlan->is_active) {
            return back()->withErrors(['vip' => 'Ce plan VIP n’est plus disponible.']);
        }

        if ($user->wallet_balance < $vipPlan->min_amount) {
            return back()->withErrors(['vip' => 'Solde insuffisant pour activer ce plan VIP. Déposez d’abord un montant suffisant.']);
        }

        $investment = $user->investments()->create([
            'vip_plan_id' => $vipPlan->id,
            'amount' => $vipPlan->min_amount,
            'daily_gain' => round($vipPlan->min_amount * $vipPlan->daily_rate / 100, 2),
            'accumulated_gains' => 0,
            'total_claimed' => 0,
            'status' => 'active',
            'expires_at' => now()->addDays($vipPlan->duration_days),
        ]);

        $user->wallet_balance -= $vipPlan->min_amount;
        $user->save();

        Notification::create([
            'user_id' => $user->id,
            'type' => 'vip_investment',
            'title' => 'Investissement VIP activé',
            'message' => "Votre plan VIP {$vipPlan->name} a été activé avec {$vipPlan->min_amount} FBU.",
        ]);

        return back()->with('success', 'VIP activé avec succès. Votre solde a été débité.');
    }

    public function claimDailyGain(Request $request)
    {
        $user = $request->user();
        $investment = $user->investments()->where('status', 'active')->first();

        if (! $investment || $investment->accumulated_gains <= 0) {
            return back()->withErrors(['claim' => 'Aucun gain journalier disponible.']);
        }

        $amount = $investment->accumulated_gains;
        $investment->wallet_balance ??= 0; // no-op placeholder

        $investment->total_claimed += $amount;
        $investment->accumulated_gains = 0;
        $investment->save();

        $user->wallet_balance += $amount;
        $user->save();

        DailyClaim::create([
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'amount' => $amount,
            'claimed_at' => Carbon::now(),
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'daily_gain',
            'title' => 'Gain journalier réclamé',
            'message' => "Vous avez réclamé {$amount} FBU de gains.",
        ]);

        return back()->with('success', 'Gain journalier crédité sur votre wallet.');
    }
}
