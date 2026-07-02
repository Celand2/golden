<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BonusClaim;
use App\Models\DailyClaim;
use App\Models\Investment;
use App\Models\News;
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

        // Vérifier si l'utilisateur a déjà un VIP actif (peu importe le plan)
        if ($user->investments()->where('status', 'active')->exists()) {
            return back()->withErrors(['vip' => 'Vous ne pouvez pas avoir plus d\'un plan VIP actif à la fois.']);
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

        // Vérifier le délai de 24h depuis la dernière réclamation
        $lastClaim = DailyClaim::where('user_id', $user->id)
            ->where('investment_id', $investment->id)
            ->latest('claimed_at')
            ->first();

        if ($lastClaim && $lastClaim->claimed_at->diffInHours(now()) < 24) {
            $nextClaimTime = $lastClaim->claimed_at->addHours(24);
            return back()->withErrors(['claim' => "Vous devez attendre 24h avant de réclamer à nouveau. Prochain claim disponible à {$nextClaimTime->format('H:i')}."]);
        }

        $amount = $investment->accumulated_gains;

        $investment->total_claimed += $amount;
        $investment->accumulated_gains = 0;
        $investment->save();

        // Ajouter au withdrawable_balance (pas wallet_balance)
        $user->increment('withdrawable_balance', $amount);

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
            'message' => "Vous avez réclamé {$amount} FBU de gains. Ces fonds sont maintenant dans votre Main Balance.",
        ]);

        return back()->with('success', 'Gain journalier crédité sur votre Main Balance.');
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

        // Vérifier le délai de 24h depuis la dernière réclamation pour cet investment
        $lastClaim = DailyClaim::where('user_id', $user->id)
            ->where('investment_id', $investment->id)
            ->latest('claimed_at')
            ->first();

        if ($lastClaim && $lastClaim->claimed_at->diffInHours(now()) < 24) {
            $nextClaimTime = $lastClaim->claimed_at->addHours(24);
            return back()->withErrors(['claim' => "Vous devez attendre 24h avant de réclamer à nouveau. Prochain claim disponible à {$nextClaimTime->format('H:i')}."]);
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

    public function settings(Request $request)
    {
        return view('client.settings', [
            'user' => $request->user(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Vos coordonnées ont été mises à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été changé avec succès.');
    }

    public function bonus(Request $request)
    {
        $user = $request->user();

        // Compter les parrainages niveau 1
        $level1 = $user->referrals()->get();
        $level1Count = $level1->count();

        // Compter les parrainages niveau 1 avec VIP actifs
        $activeLevel1Count = $level1->filter(function ($member) {
            return $member->activeInvestments()->exists();
        })->count();

        // Vérifier quels bonus ont déjà été réclamés
        $claimedBonuses = $user->bonusClaims()
            ->whereIn('level', [10, 20, 30])
            ->pluck('level', 'level')
            ->all();

        return view('client.bonus', [
            'user' => $user,
            'level1Count' => $level1Count,
            'activeLevel1Count' => $activeLevel1Count,
            'claimedBonuses' => $claimedBonuses,
        ]);
    }

    public function claimBonus(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'level' => 'required|integer|in:10,20,30',
        ]);

        $level = $validated['level'];

        // Vérifier si le bonus a déjà été réclamé
        if ($user->bonusClaims()->where('level', $level)->exists()) {
            return back()->withErrors(['bonus' => 'Vous avez déjà réclamé ce bonus.']);
        }

        // Compter les parrainages niveau 1 avec VIP actifs
        $activeLevel1Count = $user->referrals()
            ->filter(function ($member) {
                return $member->activeInvestments()->exists();
            })
            ->count();

        // Vérifier si l'utilisateur a atteint le niveau requis
        if ($activeLevel1Count < $level) {
            return back()->withErrors(['bonus' => "Vous n'avez pas encore atteint le niveau requis. Il vous faut {$level} parrainages VIP actifs."]);
        }

        // Déterminer le montant du bonus
        $bonusAmount = match ($level) {
            10 => 5000,
            20 => 10000,
            30 => 15000,
        };

        // Créer l'enregistrement du bonus
        BonusClaim::create([
            'user_id' => $user->id,
            'level' => $level,
            'amount' => $bonusAmount,
            'referral_count' => $activeLevel1Count,
        ]);

        // Ajouter le montant au withdrawable_balance
        $user->increment('withdrawable_balance', $bonusAmount);

        // Créer une notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'bonus',
            'title' => 'Bonus parrainage réclamé',
            'message' => "Vous avez réclamé {$bonusAmount} FBU pour avoir atteint {$level} parrainages VIP actifs.",
        ]);

        return back()->with('success', "Félicitations ! Vous avez réclamé {$bonusAmount} FBU. Le montant a été ajouté à votre Main Balance.");
    }

    public function news(Request $request)
    {
        $news = News::where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('client.news', [
            'news' => $news,
        ]);
    }

    public function statistics(Request $request)
    {
        $user = $request->user();

        // Total deposits
        $totalDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'approved')
            ->sum('amount');

        // Total withdrawals
        $totalWithdrawals = $user->transactions()
            ->where('type', 'withdrawal')
            ->where('status', 'approved')
            ->sum('amount');

        // Total gains (daily claims + investment claims)
        $totalGains = $user->transactions()
            ->whereIn('type', ['daily_gain', 'claim'])
            ->where('status', 'approved')
            ->sum('amount');

        // Transactions for evolution table
        $transactions = $user->transactions()
            ->whereIn('type', ['deposit', 'withdrawal', 'daily_gain', 'claim', 'investment'])
            ->orderByDesc('created_at')
            ->paginate(20);

        // Referral statistics
        $totalReferrals = $user->referrals()->count();
        $activeReferrals = $user->referrals()
            ->whereHas('activeInvestments')
            ->count();
        
        $totalCommission = $user->referralCommissions()->sum('amount');

        return view('client.statistics', [
            'user' => $user,
            'totalDeposits' => $totalDeposits,
            'totalWithdrawals' => $totalWithdrawals,
            'totalGains' => $totalGains,
            'transactions' => $transactions,
            'totalReferrals' => $totalReferrals,
            'activeReferrals' => $activeReferrals,
            'totalCommission' => $totalCommission,
        ]);
    }

    public function weeklyChallenge(Request $request)
    {
        $user = $request->user();

        // Get top 3 referrers of the week (by number of new referrals this week)
        $startOfWeek = now()->startOfWeek();
        
        $topReferrers = \App\Models\User::whereHas('referrals', function ($query) use ($startOfWeek) {
                $query->where('created_at', '>=', $startOfWeek);
            })
            ->withCount(['referrals as week_referrals_count' => function ($query) use ($startOfWeek) {
                $query->where('created_at', '>=', $startOfWeek);
            }])
            ->orderByDesc('week_referrals_count')
            ->limit(3)
            ->get();

        // Check if current user is in top 3
        $userRank = $topReferrers->search(function ($referrer) use ($user) {
            return $referrer->id === $user->id;
        });

        $userWeekReferrals = $user->referrals()
            ->where('created_at', '>=', $startOfWeek)
            ->count();

        return view('client.weekly-challenge', [
            'topReferrers' => $topReferrers,
            'userRank' => $userRank !== false ? $userRank + 1 : null,
            'userWeekReferrals' => $userWeekReferrals,
        ]);
    }

    public function mkopo(Request $request)
    {
        $user = $request->user();

        // Count active L1 referrals
        $activeL1Count = $user->referrals()
            ->whereHas('activeInvestments')
            ->count();

        // Check if user is premium (30+ active L1 referrals)
        $isPremium = $activeL1Count >= 30;

        // Get user's loan history
        $loans = $user->loans()->orderByDesc('created_at')->get();

        return view('client.mkopo', [
            'user' => $user,
            'activeL1Count' => $activeL1Count,
            'isPremium' => $isPremium,
            'loans' => $loans,
        ]);
    }

    public function requestLoan(Request $request)
    {
        $user = $request->user();

        // Count active L1 referrals
        $activeL1Count = $user->referrals()
            ->whereHas('activeInvestments')
            ->count();

        if ($activeL1Count < 30) {
            return back()->withErrors(['loan' => 'Vous devez avoir au moins 30 filleuls L1 actifs pour demander un prêt.']);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000|max:500000',
            'duration_months' => 'required|integer|min:1|max:12',
        ]);

        // Calculate interest rate based on active referrals
        $interestRate = match (true) {
            $activeL1Count >= 100 => 5,
            $activeL1Count >= 60 => 10,
            default => 15,
        };

        $loanAmount = $validated['amount'];
        $durationMonths = $validated['duration_months'];
        $totalRepayment = $loanAmount + ($loanAmount * $interestRate / 100);

        // Create loan
        $loan = $user->loans()->create([
            'amount' => $loanAmount,
            'interest_rate' => $interestRate,
            'duration_months' => $durationMonths,
            'total_repayment' => $totalRepayment,
            'status' => 'pending',
        ]);

        // Add loan amount to user's wallet
        $user->increment('wallet_balance', $loanAmount);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'loan_approved',
            'title' => 'Prêt accordé',
            'message' => "Votre prêt de {$loanAmount} FBU a été approuvé. Taux d'intérêt: {$interestRate}%. Montant à rembourser: {$totalRepayment} FBU.",
        ]);

        return back()->with('success', "Votre prêt de {$loanAmount} FBU a été approuvé. Le montant a été ajouté à votre portefeuille.");
    }
}
