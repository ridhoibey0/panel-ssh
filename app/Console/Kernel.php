<?php

namespace App\Console;

use App\Jobs\CheckBlanaceAndExpiredJobs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new CheckBlanaceAndExpiredJobs())->everyTwoMinutes()->withoutOverlapping();
        $schedule->command('trial-limit:reset')
             ->dailyAt('00:00');
        // $schedule->command('queue:work --queue=high,default')->everyMinute()->withoutOverlapping();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
