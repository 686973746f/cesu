<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('autorecoveredactivecases:daily')->dailyAt('06:00')->evenInMaintenanceMode();
        $schedule->command('dailycaseslogging:daily')->dailyAt('13:00')->evenInMaintenanceMode();
        $schedule->command('dailycaseslogging:daily')->dailyAt('16:00')->evenInMaintenanceMode();
        $schedule->command('autoemailreport:daily')->dailyAt('00:49')->evenInMaintenanceMode();
        //$schedule->command('autoemailreport:daily')->dailyAt('16:10')->evenInMaintenanceMode();

        //$schedule->command('test:everyminute')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
