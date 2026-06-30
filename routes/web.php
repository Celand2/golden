<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\TransactionCrudController as AdminTransactionCrudController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VipPlanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\NotificationController as ClientNotificationController;
use App\Http\Controllers\Client\TransactionController as ClientTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::post('/lumicash', [AdminDashboardController::class, 'updateLumicash'])->name('admin.lumicash.update');

    Route::resource('users', AdminUserController::class)->except(['show'])->names('admin.users');
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::resource('vip-plans', VipPlanController::class)->except(['create', 'edit', 'show'])->names('admin.vip-plans');
    Route::resource('transactions', AdminTransactionCrudController::class)->only(['index', 'destroy'])->names('admin.transactions');

    Route::post('deposit/{transaction}/approve', [AdminTransactionController::class, 'approveDeposit'])->name('admin.deposit.approve');
    Route::post('deposit/{transaction}/reject', [AdminTransactionController::class, 'rejectDeposit'])->name('admin.deposit.reject');
    Route::post('withdrawal/{transaction}/approve', [AdminTransactionController::class, 'approveWithdrawal'])->name('admin.withdrawal.approve');
    Route::post('withdrawal/{transaction}/reject', [AdminTransactionController::class, 'rejectWithdrawal'])->name('admin.withdrawal.reject');
});

Route::middleware(['auth', 'is_client'])->prefix('dashboard')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/deposit', [ClientTransactionController::class, 'showDepositForm'])->name('client.deposit.form');
    Route::post('/deposit', [ClientTransactionController::class, 'createDeposit'])->name('client.deposit');
    Route::get('/withdraw', [ClientTransactionController::class, 'showWithdrawForm'])->name('client.withdraw.form');
    Route::post('/withdraw', [ClientTransactionController::class, 'createWithdrawal'])->name('client.withdraw');
    Route::get('/team', [ClientDashboardController::class, 'team'])->name('client.team');
    Route::get('/vip', [ClientDashboardController::class, 'showVipPlans'])->name('client.vip');
    Route::get('/referral', [ClientDashboardController::class, 'team'])->name('client.referral');
    Route::get('/support', fn () => redirect('https://wa.me/25700000000?text=Bonjour%20GoldenRise%20Invest'))->name('client.support');
    Route::get('/active-vip', [ClientDashboardController::class, 'showMyVips'])->name('client.active-vip');
    Route::get('/statistics', [ClientDashboardController::class, 'index'])->name('client.statistics');
    Route::get('/bonus', [ClientDashboardController::class, 'index'])->name('client.bonus');
    Route::get('/news', [ClientDashboardController::class, 'index'])->name('client.news');
    Route::get('/education', [ClientDashboardController::class, 'index'])->name('client.education');
    Route::get('/settings', [ClientDashboardController::class, 'index'])->name('client.settings');
    Route::get('/vip-plans', [ClientDashboardController::class, 'showVipPlans'])->name('client.vip-plans');
    Route::post('/vip-plans/{vipPlan}/invest', [ClientDashboardController::class, 'investVipPlan'])->name('client.vip-plans.invest');
    Route::post('/claim', [ClientDashboardController::class, 'claimDailyGain'])->name('client.claim');
    Route::post('/investments/{investment}/claim', [ClientDashboardController::class, 'claimInvestmentGains'])->name('client.investment.claim');
    
    // Notifications
    Route::get('/notifications', [ClientNotificationController::class, 'index'])->name('client.notifications');
    Route::post('/notifications/{notification}/read', [ClientNotificationController::class, 'markAsRead'])->name('client.notification.read');
    Route::post('/notifications/read-all', [ClientNotificationController::class, 'markAllAsRead'])->name('client.notifications.read-all');
    Route::post('/notifications/{notification}/delete', [ClientNotificationController::class, 'delete'])->name('client.notification.delete');
});
