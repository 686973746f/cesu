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

                $totalActive_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_fullyVaccinated += $totalActive_fullyVaccinated_janssen;

                $totalActive_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_unvaccinated = $totalActiveCases - $totalActive_partialVaccinated - $totalActive_fullyVaccinated - $totalActive_booster;

                $totalRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_fullyVaccinated += $totalRecovered_fullyVaccinated_janssen;

                $totalRecovered_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalRecovered_unvaccinated = $totalRecovered - $totalRecovered_partialVaccinated - $totalRecovered_fullyVaccinated - $totalRecovered_booster;
                
                $totalDeaths = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated += $totalDeath_fullyVaccinated_janssen;

                $totalDeath_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalDeath_unvaccinated = $totalDeaths - $totalDeath_partialVaccinated - $totalDeath_fullyVaccinated - $totalDeath_booster;
                
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

                $newActiveCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_fullyVaccinated += $newActiveCount_fullyVaccinated_janssen;

                $newActiveCount_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_unvaccinated = $newActive - $newActiveCount_partialVaccinated - $newActiveCount_fullyVaccinated - $newActiveCount_booster;

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

                $lateActiveCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_fullyVaccinated += $lateActiveCount_fullyVaccinated_janssen;

                $lateActiveCount_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_unvaccinated = $lateActive - $lateActiveCount_partialVaccinated - $lateActiveCount_fullyVaccinated - $lateActiveCount_booster;

                $newRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;

                $newRecoveredCount_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_unvaccinated = $newRecovered - $newRecoveredCount_partialVaccinated - $newRecoveredCount_fullyVaccinated - $newRecoveredCount_booster;

                $lateRecovered = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-1 Day')), date('Y-m-d')]) //before ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-1 Day')), date('Y-m-d')]) //before ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', '!=', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-1 Day')), date('Y-m-d')]) //before ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN')
                    ->whereNull('records.vaccinationDate3');
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-1 Day')), date('Y-m-d')]) //before ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;

                $lateRecoveredCount_booster = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate3')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-1 Day')), date('Y-m-d')]) //before ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_unvaccinated = $lateRecovered - $lateRecoveredCount_partialVaccinated - $lateRecoveredCount_fullyVaccinated - $lateRecoveredCount_booster;

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
                    'total_active_unvaccinated' => $totalActive_unvaccinated,
                    'total_active_halfvax' => $totalActive_partialVaccinated,
                    'total_active_fullvax' => $totalActive_fullyVaccinated,
                    'total_active_booster' => $totalActive_booster,
                    'total_recoveries' => $totalRecovered,
                    'total_recoveries_unvaccinated' => $totalRecovered_unvaccinated,
                    'total_recoveries_halfvax' => $totalRecovered_partialVaccinated,
                    'total_recoveries_fullvax' => $totalRecovered_fullyVaccinated,
                    'total_recoveries_booster' => $totalRecovered_booster,
                    'total_deaths' => $totalDeaths,
                    'total_deaths_unvaccinated' => $totalDeath_unvaccinated,
                    'total_deaths_halfvax' => $totalDeath_partialVaccinated,
                    'total_deaths_fullvax' => $totalDeath_fullyVaccinated,
                    'total_deaths_booster' => $totalDeath_booster,
                    'new_cases' => $newActive,
                    'new_cases_unvaccinated' => $newActiveCount_unvaccinated,
                    'new_cases_halfvax' => $newActiveCount_partialVaccinated,
                    'new_cases_fullvax' => $newActiveCount_fullyVaccinated,
                    'new_cases_booster' => $newActiveCount_booster,
                    'late_cases' => $lateActive,
                    'late_cases_unvaccinated' => $lateActiveCount_unvaccinated,
                    'late_cases_halfvax' => $lateActiveCount_partialVaccinated,
                    'late_cases_fullvax' => $lateActiveCount_fullyVaccinated,
                    'late_cases_booster' => $lateActiveCount_booster,
                    'new_recoveries' => $newRecovered,
                    'new_recoveries_unvaccinated' => $newRecoveredCount_unvaccinated,
                    'new_recoveries_halfvax' => $newRecoveredCount_partialVaccinated,
                    'new_recoveries_fullvax' => $newRecoveredCount_fullyVaccinated,
                    'new_recoveries_booster' => $newRecoveredCount_booster,
                    'late_recoveries' => $lateRecovered,
                    'late_recoveries_unvaccinated' => $lateRecoveredCount_unvaccinated,
                    'late_recoveries_halfvax' => $lateRecoveredCount_partialVaccinated,
                    'late_recoveries_fullvax' => $lateRecoveredCount_fullyVaccinated,
                    'late_recoveries_booster' => $lateRecoveredCount_booster,
                    'new_deaths' => $newDeaths,
                    'new_deaths_unvaccinated' => 0, //NOT BEING FETCHED AND RECORDED
                    'new_deaths_halfvax' => 0, //NOT BEING FETCHED AND RECORDED
                    'new_deaths_fullvax' => 0, //NOT BEING FETCHED AND RECORDED
                    'new_deaths_booster' => 0, //NOT BEING FETCHED AND RECORDED
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
