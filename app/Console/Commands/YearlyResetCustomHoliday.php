<?php

namespace App\Console\Commands;

use App\Models\SiteSettings;
use Illuminate\Console\Command;

class YearlyResetCustomHoliday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetcustomholiday:yearly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $d = SiteSettings::find(1);

        $d->custom_holiday_dates = NULL;

        $d->save();
    }
}
