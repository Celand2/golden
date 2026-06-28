<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VipPlan;
use Illuminate\Http\Request;

class VipPlanController extends Controller
{
    public function index()
    {
        return view('admin.vip_plans.index', [
            'plans' => VipPlan::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0|max:100',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        VipPlan::create([
            'name' => $request->input('name'),
            'min_amount' => $request->input('min_amount'),
            'daily_rate' => $request->input('daily_rate'),
            'duration_days' => $request->input('duration_days'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Plan VIP ajouté.');
    }

    public function update(Request $request, VipPlan $vipPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_amount' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0|max:100',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $vipPlan->update([
            'name' => $request->input('name'),
            'min_amount' => $request->input('min_amount'),
            'daily_rate' => $request->input('daily_rate'),
            'duration_days' => $request->input('duration_days'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Plan VIP mis à jour.');
    }

    public function destroy(VipPlan $vipPlan)
    {
        $vipPlan->delete();

        return back()->with('success', 'Plan VIP supprimé.');
    }
}
