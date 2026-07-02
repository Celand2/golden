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
            'unreadNotificationsCount' => $user->notifications()->where('is_read', false)->count(),
            'pendingTransactions' => $pendingTransactions,
            'teamMembers' => $user->referrals()->get(),
            'withdrawableBalance' => $user->withdrawable_balance,
            'approvedDepositAmount' => $user->transactions()->where('type', 'deposit')->where('status', 'approved')->sum('amount'),
        ]);
    }

    public function team(Request $request)
    {
        $user = $request->user();

        $level1 = $user->referrals()->with('referrals.referrals')->get();
        $level2 = $level1->flatMap(fn ($member) => $member->referrals)->unique('id')->values();
        $level3 = $level2->flatMap(fn ($member) => $member->referrals)->unique('id')->values();

        $commissionTotals = $user->referralCommissions()
            ->whereIn('level', [1, 2, 3])
            ->selectRaw('level, SUM(amount) as total')
            ->groupBy('level')
            ->pluck('total', 'level')
            ->all();

        return view('client.team', [
            'user' => $user,
            'level1' => $level1,
            'level2' => $level2,
            'level3' => $level3,
            'commissionTotals' => $commissionTotals,
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

        if ($user->investments()->where('vip_plan_id', $vipPlan->id)->where('status', 'active')->exists()) {
            return back()->withErrors(['vip' => 'Vous ne pouvez pas investir deux fois dans le même plan VIP en même temps.']);
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

        $user->decrement('wallet_balance', $vipPlan->min_amount);

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

    public function showMyVips(Request $request)
    {
        $user = $request->user();
        $investments = $user->investments()->orderBy('created_at', 'desc')->get();

        return view('client.my-vips', [
            'user' => $user,
            'investments' => $investments,
        ]);
    }

    public function claimInvestmentGains(Request $request, Investment $investment)
    {
        $user = $request->user();

        // Vérifier que l'investment appartient à l'user
        if ($investment->user_id !== $user->id) {
            return back()->withErrors(['claim' => 'Accès non autorisé.']);
        }

        // Vérifier les conditions de claim
        if ($investment->status !== 'active') {
            return back()->withErrors(['claim' => 'Cet investment n\'est pas actif.']);
        }

        if ($investment->accumulated_gains <= 0) {
            return back()->withErrors(['claim' => 'Aucun gain à réclamer pour ce VIP.']);
        }

        $amount = $investment->accumulated_gains;

        // Transférer les gains vers withdrawable_balance
        $user->increment('withdrawable_balance', $amount);

        // Incrémenter total_claimed
        $investment->increment('total_claimed', $amount);

        // Remettre accumulated_gains à 0
        $investment->update(['accumulated_gains' => 0]);

        // Créer une entrée dans daily_claims
        DailyClaim::create([
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'amount_claimed' => $amount,
        ]);

        // Créer une transaction de type claim
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'claim',
            'amount' => $amount,
            'status' => 'approved',
        ]);

        // Notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'daily_claim',
            'title' => 'Gains réclamés',
            'message' => "Vous avez réclamé {$amount} FBU du plan {$investment->vipPlan->name}.",
        ]);

        return back()->with('success', "Vous avez réclamé {$amount} FBU. Ces fonds sont maintenant dans votre portefeuille retirable.");
    }
}
