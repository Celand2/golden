<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProcessDailyGains extends Command
{
    protected $signature = 'process:daily-gains';
    protected $description = 'Expire les VIP échus et accumule les gains journaliers dus (24h depuis la création ou le dernier accrual).';

    public function handle(): int
    {
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

                        $investment->accumulated_gains += $investment->daily_gain;
                        $investment->last_accrual_at = $nextDue;
                        $investment->save();
                        $accrualCount++;

                        Notification::create([
                            'user_id' => $investment->user_id,
                            'type' => 'daily_gain',
                            'title' => 'Gain journalier disponible',
                            'message' => "Votre gain journalier de {$investment->daily_gain} FBU est disponible.",
                        ]);
                    }
                }
            });

        $this->info("Traitement terminé : {$accrualCount} accrual(s), {$expiredCount} expiration(s).");

        return self::SUCCESS;
    }
}