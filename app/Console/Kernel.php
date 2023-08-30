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
        $schedule->command('automw:daily')->dailyAt('16:03')->evenInMaintenanceMode();
        $schedule->command('autoemailreport:daily')->dailyAt('16:06')->evenInMaintenanceMode();
        $schedule->command('ayudaemail:daily')->dailyAt('16:09')->evenInMaintenanceMode();
        $schedule->command('autoemailcoviddatabase:daily')->dailyAt('16:15')->evenInMaintenanceMode();
        $schedule->command('autosendencoderstats:daily')->dailyAt('16:40')->evenInMaintenanceMode();
        $schedule->command('autoemailctreport:daily')->dailyAt('16:45')->evenInMaintenanceMode();
        $schedule->command('compositemeasurev2:on15and30')->monthlyOn(15, '16:50');
        $schedule->command('compositemeasurev2:on15and30')->monthlyOn(date('t'), '16:50');
        $schedule->command('pidsrwndr:weekly')->weeklyOn(2, '11:00')->evenInMaintenanceMode();
        $schedule->command('covidvaccinelinelistimporter:weekly')->weeklyOn(1, '22:00')->evenInMaintenanceMode();

        $schedule->command('fhsism2autosender:monthly')->monthlyOn(date('t'), '17:00');
        $schedule->command('autotkc:daily')->dailyAt('16:40')->evenInMaintenanceMode();
        //$schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        
        //$schedule->command('autoemailcompositemeasure:on15and30')->monthlyOn(15, '16:05');
        //$schedule->command('autoemailcompositemeasure:on15and30')->monthlyOn(date('t'), '16:05');

        //$schedule->command('test:everyminute')->everyMinute();

        //$schedule->command('pharmacylog:weekly')->weeklyOn(7, '21:00')->evenInMaintenanceMode();
        //$schedule->command('pharmacylog:monthly')->monthlyOn(date('t'), '22:00')->evenInMaintenanceMode();
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
