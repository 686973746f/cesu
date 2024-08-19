<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\FhsisTbdotsMorbidity;

class TbdotsAgeRecounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tbdotsagerecounter';

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
        $list = FhsisTbdotsMorbidity::get();

        foreach($list as $l) {
            $birthdate = Carbon::parse($l->bdate);
            $currentDate = Carbon::parse($l->date_started_tx);

            $get_agemonths = $birthdate->diffInMonths($currentDate);
            $get_agedays = $birthdate->diffInDays($currentDate);

            $l->age_months = $get_agemonths;
            $l->age_days = $get_agedays;

            if($l->isDirty()) {
                $l->save();
            }
        }
    }
}
