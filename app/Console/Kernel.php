<?php

namespace App\Console;

use App\Console\Commands\ProcessDailyGains;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ProcessDailyGains::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Traiter les gains journaliers pour les investments actifs
        // ⚠️ IMPORTANT: Ce scheduler nécessite un cron job système qui appelle:
        //    * * * * * cd /path/to/goldenrise-invest && php artisan schedule:run >> /dev/null 2>&1
        // En développement, tester avec: php artisan schedule:work
        $schedule->command('process:daily-gains')
            ->daily()
            ->at('00:00')  // Exécuter à minuit chaque jour
            ->withoutOverlapping()
            ->onFailure(function () {
                // Envoyer une alerte si le job échoue
                \Log::error('ProcessDailyGains job failed');
            });
    }
}
