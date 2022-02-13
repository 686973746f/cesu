<?php

namespace App\Console\Commands;

use App\Models\Forms;
use App\Models\DailyCases;
use Illuminate\Console\Command;

class DailyCasesLogging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailycaseslogging:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log Cases on 1pm and 4pm Daily';

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
        if(date('H') == 13 || date('H') == 16) {
            if(date('H') == 13) {
                $type = '1PM';
            }
            else if(date('H') == 16) {
                $type = '4PM';
            }

            $check = DailyCases::whereDate('set_date', date('Y-m-d'))
            ->where('type', $type)
            ->first();

            if(!($check)) {
                $totalActiveCases = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalDeaths = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $total_all_suspected_probable_cases = Forms::where('isPresentOnSwabDay', 1)->count();

                $newActive = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActive = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newDeaths = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('outcomeDeathDate', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                })->orWhere(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('morbidityMonth', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                })->count();

                $active_asymptomatic_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Asymptomatic')
                ->count();

                $active_mild_with_comorbid_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Mild')
                ->where('COMO', '!=', 'None')
                ->count();

                $active_mild_without_comorbid_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Mild')
                ->where('COMO', 'None')
                ->count();

                $active_moderate_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Moderate')
                ->count();
                
                $active_severe_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Severe')
                ->count();

                $active_critical_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Critical')
                ->count();

                $facility_one_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('dispoType', 6)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $facility_two_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('dispoType', 7)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hq_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('dispoType', 3)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hospital_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->whereIn('dispoType', [1,2,5])
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_male_count = Forms::with('records')
                ->whereHas('records', function($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('gender', 'MALE');
                })->where('status', 'approved')
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_female_count = Forms::with('records')
                ->whereHas('records', function($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('gender', 'FEMALE');
                })->where('status', 'approved')
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup1_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) <= 17');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup2_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 18 AND 25');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup3_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 26 AND 35');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup4_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 36 AND 45');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup5_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 46 AND 59');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup6_count = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 60');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActiveReinfection = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('reinfected', 1)
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecoveredReinfection = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('reinfected', 1)
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeathReinfection = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('reinfected', 1)
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $data = DailyCases::create([
                    'set_date' => date('Y-m-d'),
                    'type' => $type,
                    'total_active' => $totalActiveCases,
                    'total_recoveries' => $totalRecovered,
                    'total_deaths' => $totalDeaths,
                    'new_cases' => $newActive,
                    'late_cases' => $lateActive,
                    'new_recoveries' => $newRecovered,
                    'late_recoveries' => $lateRecovered,
                    'new_deaths' => $newDeaths,
                    'total_all_confirmed_cases' => $totalActiveCases + $totalRecovered + $totalDeaths,
                    'total_all_suspected_probable_cases' => $total_all_suspected_probable_cases,
                    'facility_one_count' => $facility_one_count,
                    'facility_two_count' => $facility_two_count,
                    'hq_count' => $hq_count,
                    'hospital_count' => $hospital_count,
                    'active_asymptomatic_count' => $active_asymptomatic_count,
                    'active_mild_with_comorbid_count' => $active_mild_with_comorbid_count,
                    'active_mild_without_comorbid_count' => $active_mild_without_comorbid_count,
                    'active_moderate_count' => $active_moderate_count,
                    'active_severe_count' => $active_severe_count,
                    'active_critical_count' => $active_critical_count,
                    'active_male_count' => $active_male_count,
                    'active_female_count' => $active_female_count,
                    'active_agegroup1_count' => $active_agegroup1_count,
                    'active_agegroup2_count' => $active_agegroup2_count,
                    'active_agegroup3_count' => $active_agegroup3_count,
                    'active_agegroup4_count' => $active_agegroup4_count,
                    'active_agegroup5_count' => $active_agegroup5_count,
                    'active_agegroup6_count' => $active_agegroup6_count,
                    'reinfection_active' => $totalActiveReinfection,
                    'reinfection_recovered' => $totalRecoveredReinfection,
                    'reinfection_deaths' => $totalDeathReinfection,
                    'reinfection_total' => $totalActiveReinfection + $totalRecoveredReinfection + $totalDeathReinfection,
                ]);
            }
        }
    }
}
