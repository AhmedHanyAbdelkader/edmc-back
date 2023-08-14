<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ManagesFrequencies;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\commands\UpdateDates;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */


    protected $commands = [
        \App\Console\Commands\UpdateDates::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('update:dates')->daily(); // Adjust the frequency as needed


        $schedule->command('updateDates:dates')->daily();
        $schedule->command('updateDates:weaklydates')->weeklyOn(6, 2, 0);
        $schedule->command('updateDates:monthlydates')->monthly();

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
