<?php

namespace App\Console\Commands;

use App\Http\Controllers\PIDSRController;
use App\Models\EdcsWeeklySubmissionChecker;
use Illuminate\Console\Command;

class EdcsWeeklySubmissionFinalize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcsweeklysubmitfinalize';

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
        $facilities_array = [
            'CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER, INC.',
            'CITY OF GENERAL TRIAS MEDICARE HOSPITAL',
            'DIVINE GRACE MEDICAL CENTER',
            //'GENERAL TRIAS CITY HEALTH OFFICE',
            'GENERAL TRIAS MATERNITY AND PEDIATRIC HOSPITAL',
            'GENTRI MEDICAL CENTER AND HOSPITAL, INC.',
            'MAMA RACHEL HOSPITAL OF MERCY',
        ];

        for($i=1; $i < date('W'); $i++) {
            foreach($facilities_array as $facility_name) {
                $total_count = 0;

                foreach(PIDSRController::listDiseasesTables() as $disease) {
                    $modelClass = "App\\Models\\$disease";

                    if($disease == 'SevereAcuteRespiratoryInfection') {
                        $total_count += $modelClass::where('facility_name', $facility_name)
                        ->where('year', '2024')
                        ->where('morbidity_week', $i)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }
                    else {
                        $total_count += $modelClass::where('NameOfDru', $facility_name)
                        ->where('Year', '2024')
                        ->where('MorbidityWeek', $i)
                        ->where('enabled', 1)
                        ->where('match_casedef', 1)
                        ->count();
                    }
                }

                if($total_count > 0) {
                    $status = 'SUBMITTED';
                }
                else {
                    $status = 'ZERO CASE';
                }

                $c = EdcsWeeklySubmissionChecker::create([
                    'facility_name' => $facility_name,
                    'year' => '2024',
                    'week' => $i,
                    'status' => $status,
                ]);
            }
        }
    }
}
