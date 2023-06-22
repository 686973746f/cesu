<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Console\Command;

class CovidFormsUpsertNewStaticAge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covidformsupsertnewstaticage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updater';

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
        $batchSize = 1000;

        Forms::chunk($batchSize, function ($rows) {
            foreach ($rows as $row) {
                $birthdate = Carbon::parse($row->records->bdate);
                $currentDate = Carbon::parse($row->dateReported);

                $row->age_years = $birthdate->diffInYears($currentDate);
                $row->age_months = $birthdate->diffInMonths($currentDate);
                $row->age_days = $birthdate->diffInDays($currentDate);

                $row->save();
            }
        });
    }
}
