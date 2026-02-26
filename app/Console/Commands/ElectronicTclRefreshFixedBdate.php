<?php

namespace App\Console\Commands;

use App\Models\InhouseChildCare;
use App\Models\InhouseChildNutrition;
use App\Models\InhouseFamilyPlanning;
use App\Models\InhouseMaternalCare;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ElectronicTclRefreshFixedBdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etcl:refresh_fixed_bdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh fixed birthdate for electronic TCL records';

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
        $list = InhouseFamilyPlanning::get();

        foreach($list as $item) {
            $birthdate = Carbon::parse($item->patient->bdate);
            $currentDate = Carbon::parse($item->registration_date);

            $get_ageyears = $birthdate->diffInYears($currentDate);
            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);
            
            $item->age_years = $get_ageyears;
            $item->age_months = $get_agemonths;
            $item->age_days = $get_agedays;

            $item->save();
        }

        $list = InhouseMaternalCare::get();

        foreach($list as $item) {
            $birthdate = Carbon::parse($item->patient->bdate);
            $currentDate = Carbon::parse($item->registration_date);

            $get_ageyears = $birthdate->diffInYears($currentDate);
            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);
            
            $item->age_years = $get_ageyears;
            $item->age_months = $get_agemonths;
            $item->age_days = $get_agedays;

            $item->save();
        }
    }
}
