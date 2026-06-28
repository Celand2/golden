<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VipPlan;
use App\Services\LumicashService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'pendingDeposits' => Transaction::where('type', 'deposit')->where('status', 'pending')->get(),
            'pendingWithdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->get(),
            'vipPlans' => VipPlan::all(),
            'totalDeposits' => Transaction::where('type', 'deposit')->where('status', 'approved')->sum('amount'),
            'totalWithdrawals' => Transaction::where('type', 'withdrawal')->where('status', 'approved')->sum('amount'),
            'activeInvestments' => Investment::where('status', 'active')->count(),
            'topReferrersThisWeek' => User::withCount(['referrals as weekly_referrals_count' => function ($query) {
                $query->where('created_at', '>=', now()->subWeek());
            }])->having('weekly_referrals_count', '>', 0)->orderByDesc('weekly_referrals_count')->take(3)->get(),
            'lumicash' => LumicashService::get(),
        ]);
    }

    public function updateLumicash(Request $request)
    {
        $request->validate([
            'lumicash_phone' => 'required|string',
            'lumicash_name' => 'required|string',
        ]);

        LumicashService::set(
            $request->input('lumicash_phone'),
            $request->input('lumicash_name')
        );

        return back()->with('success', 'Coordonnées Lumicash mises à jour.');
    }
}
