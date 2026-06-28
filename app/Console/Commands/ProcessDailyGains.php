<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProcessDailyGains extends Command
{
    protected $signature = 'process:daily-gains';
    protected $description = 'Ajoute le gain journalier aux investments actifs et expire les plans échus.';

    public function handle(): int
    {
        $now = Carbon::now();

        Investment::where('status', 'active')
            ->where('expires_at', '<=', $now)
            ->get()
            ->each(function (Investment $investment) {
                $investment->status = 'expired';
                $investment->save();

                Notification::create([
                    'user_id' => $investment->user_id,
                    'type' => 'vip_expired',
                    'title' => 'Plan VIP expiré',
                    'message' => "Votre plan VIP {$investment->vipPlan->name} est arrivé à expiration.",
                ]);
            });

        Investment::where('status', 'active')
            ->where('expires_at', '>', $now)
            ->get()
            ->each(function (Investment $investment) {
                $investment->accumulated_gains += $investment->daily_gain;
                $investment->save();

                Notification::create([
                    'user_id' => $investment->user_id,
                    'type' => 'daily_gain',
                    'title' => 'Gain journalier disponible',
                    'message' => "Votre gain journalier de {$investment->daily_gain} FBU est disponible.",
                ]);
            });

        $this->info('Daily gains processed.');

        return self::SUCCESS;
    }
}
