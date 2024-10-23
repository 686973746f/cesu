<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\PIDSRController;
use App\Models\EdcsWeeklySubmissionChecker;

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
            'M.V. SANTIAGO MEDICAL CENTER',
        ];
        
        $currentDate = Carbon::now();

        $checkYear = Carbon::now()->subWeek(1)->format('Y');
        $checkWeek = Carbon::now()->subWeek(1)->format('W');

        $previous_week = $currentDate->format('W') - 2;
        $current_week = $currentDate->format('W') - 1;

        /*
        for($i=1; $i < date('W'); $i++) {
            
        }
        */

        //Re-verify Submission Last Week
        /*

        Old Loop
        for($k = $previous_week; $k <= $current_week; $k++) {
            
        }
        */

        foreach($facilities_array as $facility_name) {
            $total_count = 0;

            $abd_count = 0;
            $afp_count = 0;
            $ames_count = 0;
            $hepa_count = 0;
            $chikv_count = 0;
            $cholera_count = 0;
            $dengue_count = 0;
            $diph_count = 0;
            $hfmd_count = 0;
            $ili_count = 0;
            $lepto_count = 0;
            $measles_count = 0;
            $meningo_count = 0;
            $nt_count = 0;
            $nnt_count = 0;
            $pert_count = 0;
            $rabies_count = 0;
            $rota_count = 0;
            $sari_count = 0;
            $typhoid_count = 0;

            foreach(PIDSRController::listDiseasesTables() as $disease) {
                $modelClass = "App\\Models\\$disease";

                if($disease == 'SevereAcuteRespiratoryInfection') {
                    $model_count = $modelClass::where('facility_name', $facility_name)
                    ->where('year',  $checkYear)
                    ->where('encoded_mw', $checkWeek)
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $total_count += $model_count;

                    $sari_count += $model_count;
                }
                else {
                    $model_count = $modelClass::where('NameOfDru', $facility_name)
                    ->where('Year', $checkYear)
                    ->where('encoded_mw', $checkWeek)
                    ->where('enabled', 1)
                    ->where('match_casedef', 1)
                    ->count();

                    $total_count += $model_count;

                    if($disease == 'Afp') {
                        $afp_count += $model_count;
                    }
                    else if($disease == 'Measles') {
                        $measles_count += $model_count;
                    }
                    else if($disease == 'Meningo') {
                        $meningo_count += $model_count;
                    }
                    else if($disease == 'Nt') {
                        $nt_count += $model_count;
                    }
                    else if($disease == 'Rabies') {
                        $rabies_count += $model_count;
                    }
                    else if($disease == 'Hfmd') {
                        $hfmd_count += $model_count;
                    }
                    else if($disease == 'Abd') {
                        $abd_count += $model_count;
                    }
                    else if($disease == 'Ames') {
                        $ames_count += $model_count;
                    }
                    else if($disease == 'Hepatitis') {
                        $hepa_count += $model_count;
                    }
                    else if($disease == 'Chikv') {
                        $chikv_count += $model_count;
                    }
                    else if($disease == 'Cholera') {
                        $cholera_count += $model_count;
                    }
                    else if($disease == 'Dengue') {
                        $dengue_count += $model_count;
                    }
                    else if($disease == 'Diph') {
                        $diph_count += $model_count;
                    }
                    else if($disease == 'Influenza') {
                        $ili_count += $model_count;
                    }
                    else if($disease == 'Leptospirosis') {
                        $lepto_count += $model_count;
                    }
                    else if($disease == 'Nnt') {
                        $nnt_count += $model_count;
                    }
                    else if($disease == 'Pert') {
                        $pert_count += $model_count;
                    }
                    else if($disease == 'Rotavirus') {
                        $rota_count += $model_count;
                    }
                    else if($disease == 'Typhoid') {
                        $typhoid_count += $model_count;
                    }
                }
            }

            if($total_count > 0) {
                $status = 'SUBMITTED';
            }
            else {
                //$status = 'ZERO CASE';
                $status = 'NO SUBMISSION';
            }

            $check = EdcsWeeklySubmissionChecker::where('facility_name', $facility_name)
            ->where('year', $checkYear)
            ->where('week', $checkWeek);

            $d = (clone $check)->first();

            if($d) {
                if($d->type == 'AUTO' && is_null($d->waive_status)) {
                    $u = $check->update([
                        'status' => $status,

                        'abd_count' => $abd_count,
                        'afp_count' => $afp_count,
                        'ames_count' => $ames_count,
                        'hepa_count' => $hepa_count,
                        'chikv_count' => $chikv_count,
                        'cholera_count' => $cholera_count,
                        'dengue_count' => $dengue_count,
                        'diph_count' => $diph_count,
                        'hfmd_count' => $hfmd_count,
                        'ili_count' => $ili_count,
                        'lepto_count' => $lepto_count,
                        'measles_count' => $measles_count,
                        'meningo_count' => $meningo_count,
                        'nt_count' => $nt_count,
                        'nnt_count' => $nnt_count,
                        'pert_count' => $pert_count,
                        'rabies_count' => $rabies_count,
                        'rota_count' => $rota_count,
                        'sari_count' => $sari_count,
                        'typhoid_count' => $typhoid_count,
                    ]);
                }
            }
            else {
                $c = EdcsWeeklySubmissionChecker::create([
                    'facility_name' => $facility_name,
                    'year' => $checkYear,
                    'week' => $checkWeek,
                    'status' => $status,
                    'type' => 'AUTO',

                    'abd_count' => $abd_count,
                    'afp_count' => $afp_count,
                    'ames_count' => $ames_count,
                    'hepa_count' => $hepa_count,
                    'chikv_count' => $chikv_count,
                    'cholera_count' => $cholera_count,
                    'dengue_count' => $dengue_count,
                    'diph_count' => $diph_count,
                    'hfmd_count' => $hfmd_count,
                    'ili_count' => $ili_count,
                    'lepto_count' => $lepto_count,
                    'measles_count' => $measles_count,
                    'meningo_count' => $meningo_count,
                    'nt_count' => $nt_count,
                    'nnt_count' => $nnt_count,
                    'pert_count' => $pert_count,
                    'rabies_count' => $rabies_count,
                    'rota_count' => $rota_count,
                    'sari_count' => $sari_count,
                    'typhoid_count' => $typhoid_count,
                ]);
            }
        }
    }
}
