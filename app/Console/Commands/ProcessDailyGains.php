<?php

namespace App\Console\Commands;

use App\Models\DailyClaim;
use App\Models\Investment;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessDailyGains extends Command
{
    protected $signature = 'process:daily-gains';
    protected $description = 'Expire les VIP échus et crédite automatiquement les gains journaliers dus (24h depuis la création ou le dernier accrual) directement au Main Balance.';

    public function handle(): int
    {
        \Log::info('process:daily-gains exécuté à ' . now());

        $now = Carbon::now();
        $expiredCount = 0;
        $accrualCount = 0;

        Investment::where('status', 'active')
            ->chunkById(100, function ($investments) use ($now, &$expiredCount, &$accrualCount) {
                foreach ($investments as $investment) {
                    // 1. Expiration prioritaire : on arrête l'accrual immédiatement
                    if ($now->gte($investment->expires_at)) {
                        $investment->markExpired();
                        $expiredCount++;

                        Notification::create([
                            'user_id' => $investment->user_id,
                            'type' => 'vip_expired',
                            'title' => 'Plan VIP expiré',
                            'message' => "Votre plan VIP {$investment->vipPlan->name} est arrivé à expiration.",
                        ]);

                        continue;
                    }

                    // 2. Rattrapage des cycles de 24h dus, sans dépasser l'expiration
                    while ($now->gte($investment->nextAccrualDue())) {
                        if ($now->gte($investment->expires_at)) {
                            $investment->markExpired();
                            $expiredCount++;

                            Notification::create([
                                'user_id' => $investment->user_id,
                                'type' => 'vip_expired',
                                'title' => 'Plan VIP expiré',
                                'message' => "Votre plan VIP {$investment->vipPlan->name} est arrivé à expiration.",
                            ]);

                            break;
                        }

                        $nextDue = $investment->nextAccrualDue();
                        $gain = $investment->daily_gain;

                        DB::transaction(function () use ($investment, $gain, $nextDue) {
                            // Créditer directement le Main Balance (withdrawable_balance)
                            DB::table('users')
                                ->where('id', $investment->user_id)
                                ->increment('withdrawable_balance', $gain);

                            // Historique : total_claimed reflète maintenant le total crédité automatiquement
                            $investment->total_claimed += $gain;
                            $investment->last_accrual_at = $nextDue;
                            $investment->save();

                            // Garder une trace dans daily_claims (utilisé aussi pour la règle de retrait)
                            DailyClaim::create([
                                'user_id' => $investment->user_id,
                                'investment_id' => $investment->id,
                                'amount' => $gain,
                                'claimed_at' => $nextDue,
                            ]);
                        });

                        $accrualCount++;

                        Notification::create([
                            'user_id' => $investment->user_id,
                            'type' => 'daily_gain',
                            'title' => 'Gain journalier crédité',
                            'message' => "Votre gain journalier de {$gain} FBU a été automatiquement crédité à votre Main Balance.",
                        ]);
                    }
                }
            });

        $this->info("Traitement terminé : {$accrualCount} accrual(s), {$expiredCount} expiration(s).");

        return self::SUCCESS;
    }
}