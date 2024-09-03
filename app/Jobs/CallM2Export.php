<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\ExportJobs;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Models\SyndromicRecords;
use App\Models\FhsisTbdotsMorbidity;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use OpenSpout\Common\Entity\Style\Style;
use App\Http\Controllers\PIDSRController;
use Rap2hpoutre\FastExcel\SheetCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CallM2Export implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 90000;

    protected $user_id;
    protected $task_id;
    protected $year;
    protected $month;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $task_id, $year, $month)
    {
        $this->user_id = $user_id;
        $this->task_id = $task_id;
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $year = $this->year;
        $month = $this->month;

        $start = Carbon::createFromDate($year, $month, 01)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 01)->endOfMonth();

        //Morbidity Report Created August 2024

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        $final_arr = [];

        //Start of EDCS M2 Collection
        foreach($brgy_list as $b) {
            /*
            'Afp',
            'Measles',
            'Meningo',
            'Nt',
            'Rabies',
            'Hfmd',

            'Abd',
            'Ames',
            'Hepatitis',
            'Chikv',
            'Cholera',
            'Dengue',
            'Diph',
            'Influenza',
            'Leptospirosis',
            'Nnt',
            'Pert',
            'Rotavirus',
            'Typhoid',
            'SevereAcuteRespiratoryInfection',
            */

            
            foreach(PIDSRController::listDiseasesTables() as $d) {
                $modelClass = "App\\Models\\$d";

                $disease_title = PIDSRController::edcsGetIcd10Code($d);

                if($d == 'SevereAcuteRespiratoryInfection') {
                    $col_muncity = 'muncity';
                    $col_brgy = 'barangay';
                    $col_year = 'year';
                    $col_mmonth = 'morbidity_month';

                    $col_sex = 'sex';
                    $col_ageday = 'age_days';
                    $col_agemonth = 'age_months';
                    $col_ageyear = 'age_years';
                }
                else {
                    $col_muncity = 'Muncity';
                    $col_brgy = 'Barangay';
                    $col_year = 'Year';
                    $col_mmonth = 'MorbidityMonth';

                    $col_sex = 'Sex';
                    $col_ageday = 'AgeDays';
                    $col_agemonth = 'AgeMons';
                    $col_ageyear = 'AgeYears';
                }

                $base = $modelClass::where('enabled', 1)
                ->where('match_casedef', 1)
                ->where($col_muncity, $b->city->cityName)
                ->where($col_brgy, $b->brgyName)
                ->where($col_year, $start->format('Y'))
                ->where($col_mmonth, $start->format('n'));

                if((clone $base)->count() != 0) {
                    $age1_base = (clone $base)->whereBetween($col_ageday, [0,6]);
                    $age2_base = (clone $base)->whereBetween($col_ageday, [7,28]);
                    $age3_base = (clone $base)->where($col_ageday, '>=', 29)->where($col_agemonth, '<=', 11);
                    $age4_base = (clone $base)->whereBetween($col_ageyear, [1,4]);
                    $age5_base = (clone $base)->whereBetween($col_ageyear, [5,9]);
                    $age6_base = (clone $base)->whereBetween($col_ageyear, [10,14]);
                    $age7_base = (clone $base)->whereBetween($col_ageyear, [15,19]);
                    $age8_base = (clone $base)->whereBetween($col_ageyear, [20,24]);
                    $age9_base = (clone $base)->whereBetween($col_ageyear, [25,29]);
                    $age10_base = (clone $base)->whereBetween($col_ageyear, [30,34]);
                    $age11_base = (clone $base)->whereBetween($col_ageyear, [35,39]);
                    $age12_base = (clone $base)->whereBetween($col_ageyear, [40,44]);
                    $age13_base = (clone $base)->whereBetween($col_ageyear, [45,49]);
                    $age14_base = (clone $base)->whereBetween($col_ageyear, [50,54]);
                    $age15_base = (clone $base)->whereBetween($col_ageyear, [55,59]);
                    $age16_base = (clone $base)->whereBetween($col_ageyear, [60,64]);
                    $age17_base = (clone $base)->whereBetween($col_ageyear, [65,69]);
                    $age18_base = (clone $base)->where($col_ageyear, '>=', 70);

                    $age1_m = (clone $age1_base)->where($col_sex, 'M')->count();
                    $age1_f = (clone $age1_base)->where($col_sex, 'F')->count();
                    $age2_m = (clone $age2_base)->where($col_sex, 'M')->count();
                    $age2_f = (clone $age2_base)->where($col_sex, 'F')->count();
                    $age3_m = (clone $age3_base)->where($col_sex, 'M')->count();
                    $age3_f = (clone $age3_base)->where($col_sex, 'F')->count();
                    $age4_m = (clone $age4_base)->where($col_sex, 'M')->count();
                    $age4_f = (clone $age4_base)->where($col_sex, 'F')->count();
                    $age5_m = (clone $age5_base)->where($col_sex, 'M')->count();
                    $age5_f = (clone $age5_base)->where($col_sex, 'F')->count();
                    $age6_m = (clone $age6_base)->where($col_sex, 'M')->count();
                    $age6_f = (clone $age6_base)->where($col_sex, 'F')->count();
                    $age7_m = (clone $age7_base)->where($col_sex, 'M')->count();
                    $age7_f = (clone $age7_base)->where($col_sex, 'F')->count();
                    $age8_m = (clone $age8_base)->where($col_sex, 'M')->count();
                    $age8_f = (clone $age8_base)->where($col_sex, 'F')->count();
                    $age9_m = (clone $age9_base)->where($col_sex, 'M')->count();
                    $age9_f = (clone $age9_base)->where($col_sex, 'F')->count();
                    $age10_m = (clone $age10_base)->where($col_sex, 'M')->count();
                    $age10_f = (clone $age10_base)->where($col_sex, 'F')->count();
                    $age11_m = (clone $age11_base)->where($col_sex, 'M')->count();
                    $age11_f = (clone $age11_base)->where($col_sex, 'F')->count();
                    $age12_m = (clone $age12_base)->where($col_sex, 'M')->count();
                    $age12_f = (clone $age12_base)->where($col_sex, 'F')->count();
                    $age13_m = (clone $age13_base)->where($col_sex, 'M')->count();
                    $age13_f = (clone $age13_base)->where($col_sex, 'F')->count();
                    $age14_m = (clone $age14_base)->where($col_sex, 'M')->count();
                    $age14_f = (clone $age14_base)->where($col_sex, 'F')->count();
                    $age15_m = (clone $age15_base)->where($col_sex, 'M')->count();
                    $age15_f = (clone $age15_base)->where($col_sex, 'F')->count();
                    $age16_m = (clone $age16_base)->where($col_sex, 'M')->count();
                    $age16_f = (clone $age16_base)->where($col_sex, 'F')->count();
                    $age17_m = (clone $age17_base)->where($col_sex, 'M')->count();
                    $age17_f = (clone $age17_base)->where($col_sex, 'F')->count();
                    $age18_m = (clone $age18_base)->where($col_sex, 'M')->count();
                    $age18_f = (clone $age18_base)->where($col_sex, 'F')->count();

                    $under1_m = 0;
                    $under1_f = 0;
                    $above65_m = 0;
                    $above65_f = 0;

                    $final_arr[] = [
                        'REG_CODE' => 'REGION IV-A (CALABARZON)',
                        'PROV_CODE' => 'CAVITE',
                        'MUN_CODE' => 'GENERAL TRIAS',
                        'BGY_CODE' => $b->brgyNameFhsis,
                        'DATE' => $start->format('m/d/y'),
                        'DISEASE' => $disease_title,
                        'UNDER1_M' => $under1_m,
                        'UNDER1_F' => $under1_f,
                        '1_4_M' => $age4_m,
                        '1_4_F' => $age4_f,
                        '5_9_M' => $age5_m,
                        '5_9_F' => $age5_f,
                        '10_14_M' => $age6_m,
                        '10_14_F' => $age6_f,
                        '15_19_M' => $age7_m,
                        '15_19_F' => $age7_f,
                        '20_24_M' => $age8_m,
                        '20_24_F' => $age8_f,
                        '25_29_M' => $age9_m,
                        '25_29_F' => $age9_f,
                        '30_34_M' => $age10_m,
                        '30_34_F' => $age10_f,
                        '35_39_M' => $age11_m,
                        '35_39_F' => $age11_f,
                        '40_44_M' => $age12_m,
                        '40_44_F' => $age12_f,
                        '45_49_M' => $age13_m,
                        '45_49_F' => $age13_f,
                        '50_54_M' => $age14_m,
                        '50_54_F' => $age14_f,
                        '55_59_M' => $age15_m,
                        '55_59_F' => $age15_f,
                        '60_64_M' => $age16_m,
                        '60_64_F' => $age16_f,
                        '65ABOVE_M' => $above65_m,
                        '65ABOVE_F' => $above65_f,
                        '65_69_M' => $age17_m,
                        '65_69_F' => $age17_f,
                        '70ABOVE_M' => $age18_m,
                        '70ABOVE_F' => $age18_f,
                        '0_6DAYS_M' => $age1_m,
                        '0_6DAYS_F' => $age1_f,
                        '7_28DAYS_M' => $age2_m,
                        '7_28DAYS_F' => $age2_f,
                        '29DAYS_11MOS_M' => $age3_m,
                        '29DAYS_11MOS_F' => $age3_f,
                    ];
                }
            }
        }
        
        /*
        End of EDCS M2 Counting
        */

        //Start of TB DOTS M2 Collection
        
        $tb_array = [
            'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
            'A16.1 Tuberculosis of lung, bacteriological and histological examination not done',
            'A16.0 Tuberculosis of lung, bacteriologically and histologically negative',
            'A18 Tuberculosis of other organs',
        ];

        foreach($brgy_list as $b) {
            foreach($tb_array as $tb) {
                $col_name = 'xpert_result';
                if($tb == 'A15.0 Tuberculosis of lung, confirmed by sputum microscopy with or without culture') {
                    $col_search = 'MTB Detected';
                }
                else if($tb == 'A16.1 Tuberculosis of lung, bacteriological and histological examination not done') {
                    $col_search = 'Not Done';
                }
                else if($tb == 'A16.0 Tuberculosis of lung, bacteriologically and histologically negative') {
                    $col_search = 'MTB Not Detected';
                }
                else if($tb == 'A18 Tuberculosis of other organs') {
                    $col_name = 'ana_site';
                    $col_search = 'EP';
                }

                $base = FhsisTbdotsMorbidity::where('brgy', $b->brgyName)
                ->whereBetween('date_started_tx', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->where($col_name, $col_search);

                if((clone $base)->count() != 0) {
                    $age1_base = (clone $base)->whereBetween('age_days', [0,6]);
                    $age2_base = (clone $base)->whereBetween('age_days', [7,28]);
                    $age3_base = (clone $base)->where('age_days', '>=', 29)->where('age_months', '<=', 11);
                    $age4_base = (clone $base)->whereBetween('age', [1,4]);
                    $age5_base = (clone $base)->whereBetween('age', [5,9]);
                    $age6_base = (clone $base)->whereBetween('age', [10,14]);
                    $age7_base = (clone $base)->whereBetween('age', [15,19]);
                    $age8_base = (clone $base)->whereBetween('age', [20,24]);
                    $age9_base = (clone $base)->whereBetween('age', [25,29]);
                    $age10_base = (clone $base)->whereBetween('age', [30,34]);
                    $age11_base = (clone $base)->whereBetween('age', [35,39]);
                    $age12_base = (clone $base)->whereBetween('age', [40,44]);
                    $age13_base = (clone $base)->whereBetween('age', [45,49]);
                    $age14_base = (clone $base)->whereBetween('age', [50,54]);
                    $age15_base = (clone $base)->whereBetween('age', [55,59]);
                    $age16_base = (clone $base)->whereBetween('age', [60,64]);
                    $age17_base = (clone $base)->whereBetween('age', [65,69]);
                    $age18_base = (clone $base)->where('age', '>=', 70);

                    $age1_m = (clone $age1_base)->where('sex', 'M')->count();
                    $age1_f = (clone $age1_base)->where('sex', 'F')->count();
                    $age2_m = (clone $age2_base)->where('sex', 'M')->count();
                    $age2_f = (clone $age2_base)->where('sex', 'F')->count();
                    $age3_m = (clone $age3_base)->where('sex', 'M')->count();
                    $age3_f = (clone $age3_base)->where('sex', 'F')->count();
                    $age4_m = (clone $age4_base)->where('sex', 'M')->count();
                    $age4_f = (clone $age4_base)->where('sex', 'F')->count();
                    $age5_m = (clone $age5_base)->where('sex', 'M')->count();
                    $age5_f = (clone $age5_base)->where('sex', 'F')->count();
                    $age6_m = (clone $age6_base)->where('sex', 'M')->count();
                    $age6_f = (clone $age6_base)->where('sex', 'F')->count();
                    $age7_m = (clone $age7_base)->where('sex', 'M')->count();
                    $age7_f = (clone $age7_base)->where('sex', 'F')->count();
                    $age8_m = (clone $age8_base)->where('sex', 'M')->count();
                    $age8_f = (clone $age8_base)->where('sex', 'F')->count();
                    $age9_m = (clone $age9_base)->where('sex', 'M')->count();
                    $age9_f = (clone $age9_base)->where('sex', 'F')->count();
                    $age10_m = (clone $age10_base)->where('sex', 'M')->count();
                    $age10_f = (clone $age10_base)->where('sex', 'F')->count();
                    $age11_m = (clone $age11_base)->where('sex', 'M')->count();
                    $age11_f = (clone $age11_base)->where('sex', 'F')->count();
                    $age12_m = (clone $age12_base)->where('sex', 'M')->count();
                    $age12_f = (clone $age12_base)->where('sex', 'F')->count();
                    $age13_m = (clone $age13_base)->where('sex', 'M')->count();
                    $age13_f = (clone $age13_base)->where('sex', 'F')->count();
                    $age14_m = (clone $age14_base)->where('sex', 'M')->count();
                    $age14_f = (clone $age14_base)->where('sex', 'F')->count();
                    $age15_m = (clone $age15_base)->where('sex', 'M')->count();
                    $age15_f = (clone $age15_base)->where('sex', 'F')->count();
                    $age16_m = (clone $age16_base)->where('sex', 'M')->count();
                    $age16_f = (clone $age16_base)->where('sex', 'F')->count();
                    $age17_m = (clone $age17_base)->where('sex', 'M')->count();
                    $age17_f = (clone $age17_base)->where('sex', 'F')->count();
                    $age18_m = (clone $age18_base)->where('sex', 'M')->count();
                    $age18_f = (clone $age18_base)->where('sex', 'F')->count();
                    
                    $under1_m = 0;
                    $under1_f = 0;
                    $above65_m = 0;
                    $above65_f = 0;

                    $final_arr[] = [
                        'REG_CODE' => 'REGION IV-A (CALABARZON)',
                        'PROV_CODE' => 'CAVITE',
                        'MUN_CODE' => 'GENERAL TRIAS',
                        'BGY_CODE' => $b->brgyNameFhsis,
                        'DATE' => $start->format('m/d/y'),
                        'DISEASE' => $tb,
                        'UNDER1_M' => $under1_m,
                        'UNDER1_F' => $under1_f,
                        '1_4_M' => $age4_m,
                        '1_4_F' => $age4_f,
                        '5_9_M' => $age5_m,
                        '5_9_F' => $age5_f,
                        '10_14_M' => $age6_m,
                        '10_14_F' => $age6_f,
                        '15_19_M' => $age7_m,
                        '15_19_F' => $age7_f,
                        '20_24_M' => $age8_m,
                        '20_24_F' => $age8_f,
                        '25_29_M' => $age9_m,
                        '25_29_F' => $age9_f,
                        '30_34_M' => $age10_m,
                        '30_34_F' => $age10_f,
                        '35_39_M' => $age11_m,
                        '35_39_F' => $age11_f,
                        '40_44_M' => $age12_m,
                        '40_44_F' => $age12_f,
                        '45_49_M' => $age13_m,
                        '45_49_F' => $age13_f,
                        '50_54_M' => $age14_m,
                        '50_54_F' => $age14_f,
                        '55_59_M' => $age15_m,
                        '55_59_F' => $age15_f,
                        '60_64_M' => $age16_m,
                        '60_64_F' => $age16_f,
                        '65ABOVE_M' => $above65_m,
                        '65ABOVE_F' => $above65_f,
                        '65_69_M' => $age17_m,
                        '65_69_F' => $age17_f,
                        '70ABOVE_M' => $age18_m,
                        '70ABOVE_F' => $age18_f,
                        '0_6DAYS_M' => $age1_m,
                        '0_6DAYS_F' => $age1_f,
                        '7_28DAYS_M' => $age2_m,
                        '7_28DAYS_F' => $age2_f,
                        '29DAYS_11MOS_M' => $age3_m,
                        '29DAYS_11MOS_F' => $age3_f,
                    ];
                }
            }
        }

        /*
        End of EDCS M2 Counting
        */

        //Start of OPD M2 Fetching
        //Fetch mga hindi blank na diagnosis
        $fetch_diags = SyndromicRecords::where('status', 'approved')
        ->whereNotNull('main_diagnosis')
        ->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);

        if((clone $fetch_diags)->exists()) {
            $diag_array = [];

            foreach((clone $fetch_diags)->groupBy('main_diagnosis')->pluck('main_diagnosis')->toArray() as $d) {
                $separate = explode("|", $d);

                foreach($separate as $single) {
                    if(!in_array($single, $diag_array)) {
                        $diag_array[] = $single;
                    }
                }
            }

            foreach($brgy_list as $b) {
                foreach($diag_array as $diag) {
                    $base = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                        $q->where('address_brgy_text', $b->brgyName);
                    })
                    ->whereBetween('consultation_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->where('main_diagnosis', 'LIKE', "%$diag%");

                    if((clone $base)->exists()) {
                        $age1_base = (clone $base)->whereBetween('age_days', [0,6]);
                        $age2_base = (clone $base)->whereBetween('age_days', [7,28]);
                        $age3_base = (clone $base)->where('age_days', '>=', 29)->where('age_months', '<=', 11);
                        $age4_base = (clone $base)->whereBetween('age_years', [1,4]);
                        $age5_base = (clone $base)->whereBetween('age_years', [5,9]);
                        $age6_base = (clone $base)->whereBetween('age_years', [10,14]);
                        $age7_base = (clone $base)->whereBetween('age_years', [15,19]);
                        $age8_base = (clone $base)->whereBetween('age_years', [20,24]);
                        $age9_base = (clone $base)->whereBetween('age_years', [25,29]);
                        $age10_base = (clone $base)->whereBetween('age_years', [30,34]);
                        $age11_base = (clone $base)->whereBetween('age_years', [35,39]);
                        $age12_base = (clone $base)->whereBetween('age_years', [40,44]);
                        $age13_base = (clone $base)->whereBetween('age_years', [45,49]);
                        $age14_base = (clone $base)->whereBetween('age_years', [50,54]);
                        $age15_base = (clone $base)->whereBetween('age_years', [55,59]);
                        $age16_base = (clone $base)->whereBetween('age_years', [60,64]);
                        $age17_base = (clone $base)->whereBetween('age_years', [65,69]);
                        $age18_base = (clone $base)->where('age_years', '>=', 70);

                        $age1_m = (clone $age1_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age1_f = (clone $age1_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age2_m = (clone $age2_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age2_f = (clone $age2_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age3_m = (clone $age3_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age3_f = (clone $age3_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age4_m = (clone $age4_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age4_f = (clone $age4_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age5_m = (clone $age5_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age5_f = (clone $age5_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age6_m = (clone $age6_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age6_f = (clone $age6_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age7_m = (clone $age7_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age7_f = (clone $age7_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age8_m = (clone $age8_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age8_f = (clone $age8_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age9_m = (clone $age9_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age9_f = (clone $age9_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age10_m = (clone $age10_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age10_f = (clone $age10_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age11_m = (clone $age11_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age11_f = (clone $age11_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age12_m = (clone $age12_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age12_f = (clone $age12_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age13_m = (clone $age13_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age13_f = (clone $age13_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age14_m = (clone $age14_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age14_f = (clone $age14_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age15_m = (clone $age15_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age15_f = (clone $age15_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age16_m = (clone $age16_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age16_f = (clone $age16_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age17_m = (clone $age17_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age17_f = (clone $age17_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();
                        $age18_m = (clone $age18_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'MALE');
                        })->count();
                        $age18_f = (clone $age18_base)->whereHas('syndromic_patient', function ($q) {
                            $q->where('gender', 'FEMALE');
                        })->count();

                        $under1_m = 0;
                        $under1_f = 0;
                        $above65_m = 0;
                        $above65_f = 0;

                        $final_arr[] = [
                            'REG_CODE' => 'REGION IV-A (CALABARZON)',
                            'PROV_CODE' => 'CAVITE',
                            'MUN_CODE' => 'GENERAL TRIAS',
                            'BGY_CODE' => $b->brgyNameFhsis,
                            'DATE' => $start->format('m/d/y'),
                            'DISEASE' => $diag,
                            'UNDER1_M' => $under1_m,
                            'UNDER1_F' => $under1_f,
                            '1_4_M' => $age4_m,
                            '1_4_F' => $age4_f,
                            '5_9_M' => $age5_m,
                            '5_9_F' => $age5_f,
                            '10_14_M' => $age6_m,
                            '10_14_F' => $age6_f,
                            '15_19_M' => $age7_m,
                            '15_19_F' => $age7_f,
                            '20_24_M' => $age8_m,
                            '20_24_F' => $age8_f,
                            '25_29_M' => $age9_m,
                            '25_29_F' => $age9_f,
                            '30_34_M' => $age10_m,
                            '30_34_F' => $age10_f,
                            '35_39_M' => $age11_m,
                            '35_39_F' => $age11_f,
                            '40_44_M' => $age12_m,
                            '40_44_F' => $age12_f,
                            '45_49_M' => $age13_m,
                            '45_49_F' => $age13_f,
                            '50_54_M' => $age14_m,
                            '50_54_F' => $age14_f,
                            '55_59_M' => $age15_m,
                            '55_59_F' => $age15_f,
                            '60_64_M' => $age16_m,
                            '60_64_F' => $age16_f,
                            '65ABOVE_M' => $above65_m,
                            '65ABOVE_F' => $above65_f,
                            '65_69_M' => $age17_m,
                            '65_69_F' => $age17_f,
                            '70ABOVE_M' => $age18_m,
                            '70ABOVE_F' => $age18_f,
                            '0_6DAYS_M' => $age1_m,
                            '0_6DAYS_F' => $age1_f,
                            '7_28DAYS_M' => $age2_m,
                            '7_28DAYS_F' => $age2_f,
                            '29DAYS_11MOS_M' => $age3_m,
                            '29DAYS_11MOS_F' => $age3_f,
                        ];
                    }
                }
            }
        }

        $sheets = new SheetCollection([
            'M2 BHS' => $final_arr,
        ]);

        $header_style = (new Style())->setFontBold();
        $rows_style = (new Style())->setShouldWrapText();

        /*
        return $exp = (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('FHSIS_IMPORT_M2 BHS_'.$start->format('M_Y').'.xlsx');
        */

        $filename = 'FHSIS_IMPORT_M2 BHS_'.$start->format('M_Y').'_'.Str::random(5).'.xlsx';

        $exp = (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->export(storage_path('export_jobs/'.$filename));

        $job_update = ExportJobs::where('id', $this->task_id)->update([
            'status' => 'completed',
            'filename' => $filename,
            'date_finished' => date('Y-m-d H:i:s'),
        ]);
    }
}
