<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\AbtcPatient;
use Illuminate\Console\Command;
use App\Models\AbtcBakunaRecords;

class AbtcAgeRecounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abtcagerecounter';

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
        $list = AbtcBakunaRecords::whereHas('patient', function ($q) {
            $q->whereNotNull('bdate');
        })->whereYear('created_at', 2024)
        ->get();

        foreach($list as $l) {
            $birthdate = Carbon::parse($l->patient->bdate);
            $currentDate = Carbon::parse($l->bite_date);

            $get_ageyears = $birthdate->diffInYears($currentDate);
            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);

            $l->age_years = $get_ageyears;
            $l->age_months = $get_agemonths;
            $l->age_days = $get_agedays;

            if($l->isDirty()) {
                $l->save();
            }
        }
    }
}
