<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use App\Models\Records;
use Carbon\CarbonPeriod;
use App\Models\DailyCases;
use Illuminate\Http\Request;
use App\Models\MorbidityWeek;
use Illuminate\Support\Facades\DB;

class JsonReportController extends Controller
{
    public function __construct() {
        DB::setDefaultConnection('mysqlforjson');
    }
    
    public function totalCases() {
        $arr = [];

        $totalCasesCount = 0;
        $totalCasesCount_partialVaccinated = 0;
        $totalCasesCount_fullyVaccinated = 0;

        $dcdata = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($dcdata)) {
            $dcdata = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        $totalActiveCases = $dcdata->total_active;

        /*
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
        */

        $totalCasesCount += $totalActiveCases;

        /*
        $totalActive_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        $totalActive_partialVaccinated = $dcdata->total_active_halfvax;

        $totalCasesCount_partialVaccinated += $totalActive_partialVaccinated;

        /*
        $totalActive_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalActive_fullyVaccinated += $totalActive_fullyVaccinated_janssen;
        */

        $totalActive_fullyVaccinated = $dcdata->total_active_fullvax;

        $totalCasesCount_fullyVaccinated += $totalActive_fullyVaccinated;

        /*
        $totalRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        $totalRecovered = $dcdata->total_recoveries;

        $totalCasesCount += $totalRecovered;

        //Bilangin pati current reinfection sa total ng recovered
        /*
        $reinfect_hidden_count = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('reinfected', 1)
        ->where('outcomeCondition', '!=', 'Died')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalRecovered += $reinfect_hidden_count;
        */

        /*
        $totalRecovered_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        $totalRecovered_partialVaccinated = $dcdata->total_recoveries_halfvax;

        $totalCasesCount_partialVaccinated += $totalRecovered_partialVaccinated;

        /*
        $totalRecovered_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalRecovered_fullyVaccinated += $totalRecovered_fullyVaccinated_janssen;
        */

        $totalRecovered_fullyVaccinated = $dcdata->total_recoveries_fullvax;

        $totalCasesCount_fullyVaccinated += $totalRecovered_fullyVaccinated;
        
        /*
        $totalDeaths = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Died')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        $totalDeaths = $dcdata->total_deaths;
        
        $totalCasesCount += $totalDeaths;

        /*
        $totalDeath_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Died')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        $totalDeath_partialVaccinated = $dcdata->total_deaths_halfvax;

        $totalCasesCount_partialVaccinated += $totalDeath_partialVaccinated;

        /*
        $totalDeath_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Died')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalDeath_fullyVaccinated += $totalDeath_fullyVaccinated_janssen;
        */

        $totalDeath_fullyVaccinated = $dcdata->total_deaths_fullvax;

        $totalCasesCount_fullyVaccinated += $totalDeath_fullyVaccinated;

        /*
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
        */
        
        $newActive = $dcdata->new_cases;

        /*
        $newActiveCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();
        */

        $newActiveCount_partialVaccinated = $dcdata->new_cases_halfvax;

        /*
        $newActiveCount_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();

        $newActiveCount_fullyVaccinated += $newActiveCount_fullyVaccinated_janssen;
        */
        
        $newActiveCount_fullyVaccinated = $dcdata->new_cases_fullvax;

        /*
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
        */

        $lateActive = $dcdata->late_cases;

        $lateActiveCount_partialVaccinated = $dcdata->late_cases_halfvax;

        $lateActiveCount_fullyVaccinated = $dcdata->late_cases_fullvax;

        /*
        $lateActiveCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();

        $lateActiveCount_fullyVaccinated += $lateActiveCount_fullyVaccinated_janssen;
        */

        /*
        $newRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();
        */

        $newRecovered = $dcdata->new_recoveries;

        $newRecoveredCount_partialVaccinated = $dcdata->new_recoveries_halfvax;

        $newRecoveredCount_fullyVaccinated = $dcdata->new_recoveries_fullvax;

        /*
        $newRecoveredCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;
        */

        /*
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
        */

        $lateRecovered = $dcdata->late_recoveries;

        $lateRecoveredCount_partialVaccinated = $dcdata->late_recoveries_halfvax;

        $lateRecoveredCount_fullyVaccinated = $dcdata->late_recoveries_fullvax;

        /*
        $lateRecoveredCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;
        */
        
        /*
        Old Formula

        $newRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $newRecoveredCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $newRecoveredCount_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $newRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;

        $lateRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->count();

        $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;
        */

        /*
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
        */

        $newDeaths = $dcdata->new_deaths;

        /*
        Total Cases Old Formula
        $totalCasesCount = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalCasesCount_partialVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereNull('records.vaccinationDate2')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalCasesCount_fullyVaccinated = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate2')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        
        $totalCasesCount_fullyVaccinated_janssen = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate1')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalCasesCount_fullyVaccinated += $totalCasesCount_fullyVaccinated_janssen;
        */

        //Reinfection
        /*
        Old Fetching Method
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
        */

        $totalActiveReinfection = $dcdata->reinfection_active;

        $totalRecoveredReinfection = $dcdata->reinfection_recovered;

        $totalDeathReinfection = $dcdata->reinfection_deaths;

        $grand_total_reinfection = $totalActiveReinfection + $totalRecoveredReinfection + $totalDeathReinfection;

        array_push($arr, [
            'totalActiveCases' => $totalActiveCases,
            'total_active_unvaccinated' => $dcdata->total_active_unvaccinated,
            'totalActive_partialVaccinated' => $totalActive_partialVaccinated,
            'totalActive_fullyVaccinated' => $totalActive_fullyVaccinated,
            'total_active_booster' => $dcdata->total_active_booster,
            'totalRecovered' => $totalRecovered,
            'total_recoveries_unvaccinated' => $dcdata->total_recoveries_unvaccinated,
            'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
            'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
            'total_recoveries_booster' => $dcdata->total_recoveries_booster,
            'totalDeaths' => $totalDeaths,
            'total_deaths_unvaccinated' => $dcdata->total_deaths_unvaccinated,
            'totalDeath_partialVaccinated' => $totalDeath_partialVaccinated,
            'totalDeath_fullyVaccinated' => $totalDeath_fullyVaccinated,
            'total_deaths_booster' => $dcdata->total_deaths_booster,
            'totalCases' => $totalCasesCount,
            'totalCases_partialVaccinated' => $totalCasesCount_partialVaccinated,
            'totalCases_fullyVaccinated' => $totalCasesCount_fullyVaccinated,
            'newActive' => $newActive,
            'newActiveCount_partialVaccinated' => $newActiveCount_partialVaccinated,
            'newActiveCount_fullyVaccinated' => $newActiveCount_fullyVaccinated,
            'lateActive' => $lateActive,
            'lateActiveCount_partialVaccinated' => $lateActiveCount_partialVaccinated,
            'lateActiveCount_fullyVaccinated' => $lateActiveCount_fullyVaccinated,
            'newRecovered' => $newRecovered,
            'newRecoveredCount_partialVaccinated' => $newRecoveredCount_partialVaccinated,
            'newRecoveredCount_fullyVaccinated' => $newRecoveredCount_fullyVaccinated,
            'lateRecovered' => $lateRecovered,
            'lateRecoveredCount_partialVaccinated' => $lateRecoveredCount_partialVaccinated,
            'lateRecoveredCount_fullyVaccinated' => $lateRecoveredCount_fullyVaccinated,
            'newDeaths' => $newDeaths,
            'totalActiveReinfection' => $totalActiveReinfection,
            'totalRecoveredReinfection' => $totalRecoveredReinfection,
            'totalDeathReinfection' => $totalDeathReinfection,
            'grand_total_reinfection' => $grand_total_reinfection,
        ]);

        return response()->json($arr);
    }

    public function dailyNewCases() {
        
    }

    public function weeklyCasesDist() {

    }

    public function lastYearCasesDist() {
        $arr = [];

        $period = CarbonPeriod::create(date('Y-01-01', strtotime('-1 Year')), date('Y-12-31', strtotime('-1 Year')));
        foreach($period as $date) {
            $dailyCasesCount = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();

            array_push($arr, [
                'fDate' => $date->format('m/d/Y'),
                'dailyCasesCount' => $dailyCasesCount,
            ]);
        }

        return response()->json($arr);
    }

    public function currentYearCasesDist() {
        ini_set('max_execution_time', 600);
        
        $arr = [];

        //$period = CarbonPeriod::create(date('Y-01-01'), date('Y-m-d')) WHOLE YEAR; 
        $period = CarbonPeriod::create(date('Y-01-01'), date('Y-m-d'));
        foreach($period as $date) {
            $dailyCasesCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();
    
            /*
            $totalRecovered = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();
    
            //Bilangin pati current reinfection sa total ng recovered
            $totalRecovered += Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('reinfected', 1)
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();
            
            $totalDeaths = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();
            */

            array_push($arr, [
                'fDate' => $date->format('m/d/Y'),
                'dailyCasesCount' => $dailyCasesCount,
                /*'recoveredCount' => $totalRecovered,
                'deathCount' => $totalDeaths,*/
            ]);
        }

        return response()->json($arr);
    }

    public function casesDistribution() {
        /*
        ini_set('max_execution_time', 600);
        
        $arr = [];

        $period = CarbonPeriod::create('2021-01-01', date('Y-m-d'));

        $activeCount = 0;

        foreach ($period as $date) {
            $currentActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->toDateString())
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->where('reinfected', 0)
            ->count();
            
            $currentRecoveredCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->toDateString())
            ->where('outcomeCondition', 'Recovered')
            ->where('reinfected', 0)
            ->count();

            $currentDiedCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->toDateString())
            ->where('outcomeCondition', 'Died')
            ->count();

            array_push($arr, [
                'date' => $date->toDateString(),
                'activeConfirmedCases' => ($currentActiveCount + $activeCount) - ($currentRecoveredCount + $currentDiedCount),
                'recoveredCases' => $currentRecoveredCount,
                'deathCases' => $currentDiedCount,
            ]);

            $activeCount += $currentActiveCount - $currentRecoveredCount - $currentDiedCount;
        }

        return response()->json($arr);
        */
    }

    public function facilityCount() {
        $arr = [];

        $dcdata = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($dcdata)) {
            $dcdata = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        /*
        array_push($arr, [
            'facilityCount' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 6)
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
            'hqCount' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 3)
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
            'hospitalCount' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->whereIn('dispoType', [1,2,5])
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);
        */

        array_push($arr, [
            'facilityCount' => $dcdata->facility_one_count,
            'hqCount' => $dcdata->hq_count,
            'hospitalCount' => $dcdata->hospital_count,
        ]);

        return response()->json($arr);
    }

    public function brgyCases() {
        sleep(20);
        $brgyArray = collect();

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        foreach($brgyList as $brgy) {
            $brgyConfirmedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $brgyActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $brgyNewlyEncoded = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->count();

            $brgyDeathCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            /*
            $brgyRecoveryCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
            */

            $brgyRecoveryCount = $brgyConfirmedCount - $brgyDeathCount;

            //Reinfection Count
            /*
            $brgyRecoveryCount += Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('reinfected', 1)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
            */

            /*
            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('isPresentOnSwabDay', 0)
                ->orwhereNull('isPresentOnSwabDay');
            })
            ->where('caseClassification', 'Suspect')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            })
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            })
            ->count();
            */

            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('isPresentOnSwabDay', 0)
                ->orwhereNull('isPresentOnSwabDay');
            })
            ->where('caseClassification', 'Suspect')
            ->where('outcomeCondition', 'Active')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
            ->count();

            $brgyArray->push([
                'brgyName' => $brgy->brgyName,
                'numOfConfirmedCases' => $brgyConfirmedCount,
                'numOfActiveCases' => $brgyActiveCount,
                'numOfReportedToday' => $brgyNewlyEncoded,
                'numOfDeaths' => $brgyDeathCount,
                'numOfRecoveries' => $brgyRecoveryCount,
                'numOfSuspected' => $brgySuspectedCount,
                'numOfProbable' => $brgyProbableCount,
            ]);
        }
        
        return response()->json($brgyArray);
    }

    public function genderBreakdown() {
        $arr = [];

        $dcdata = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($dcdata)) {
            $dcdata = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        /*
        $male = Forms::with('records')
        ->whereHas('records', function($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->where('gender', 'MALE');
        })->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $female = Forms::with('records')
        ->whereHas('records', function($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->where('gender', 'FEMALE');
        })->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        */

        array_push($arr, [
            'gender' => 'MALE',
            'count' => $dcdata->active_male_count,
        ]);

        array_push($arr, [
            'gender' => 'FEMALE',
            'count' => $dcdata->active_female_count,
        ]);

        return response()->json($arr);
    }

    public function conditionBreakdown() {
        $arr = [];

        $dcdata = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($dcdata)) {
            $dcdata = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        array_push($arr, [
            'status' => 'ASYMPTOMATIC',
            'count' => $dcdata->active_asymptomatic_count,
        ]);
        
        array_push($arr, [
            'status' => 'MILD',
            'count' => $dcdata->active_mild_with_comorbid_count + $dcdata->active_mild_without_comorbid_count,
        ]);

        array_push($arr, [
            'status' => 'MODERATE',
            'count' => $dcdata->active_moderate_count,
        ]);
        
        array_push($arr, [
            'status' => 'SEVERE',
            'count' => $dcdata->active_severe_count,
        ]);

        array_push($arr, [
            'status' => 'CRITICAL',
            'count' => $dcdata->active_critical_count,
        ]);

        /*
        array_push($arr, [
            'status' => 'ASYMPTOMATIC',
            'count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('healthStatus', 'Asymptomatic')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);
        
        array_push($arr, [
            'status' => 'MILD',
            'count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('healthStatus', 'Mild')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);

        array_push($arr, [
            'status' => 'MODERATE',
            'count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('healthStatus', 'Moderate')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);
        
        array_push($arr, [
            'status' => 'SEVERE',
            'count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('healthStatus', 'Severe')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);

        array_push($arr, [
            'status' => 'CRITICAL',
            'count' => Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('healthStatus', 'Critical')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);
        */

        return response()->json($arr);
    }

    public function ageDistribution() {
        $arr = collect();

        $dcdata = DailyCases::whereDate('set_date', date('Y-m-d'))
        ->where('type', '4PM')
        ->first();

        if(!($dcdata)) {
            $dcdata = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->first();
        }

        /*
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

        $arr->push([
            'bracket' => '0 - 17 YO',
            'count' => $active_agegroup1_count,
        ]);
        $arr->push([
            'bracket' => '18 - 25 YO',
            'count' => $active_agegroup2_count,
        ]);
        $arr->push([
            'bracket' => '26 - 35 YO',
            'count' => $active_agegroup3_count,
        ]);
        $arr->push([
            'bracket' => '36 - 45 YO',
            'count' => $active_agegroup4_count,
        ]);
        $arr->push([
            'bracket' => '46 - 59 YO',
            'count' => $active_agegroup5_count,
        ]);
        $arr->push([
            'bracket' => '60 YO & UP',
            'count' => $active_agegroup6_count,
        ]);
        */

        $arr->push([
            'bracket' => '0 - 17 YO',
            'count' => $dcdata->active_agegroup1_count,
        ]);
        $arr->push([
            'bracket' => '18 - 25 YO',
            'count' => $dcdata->active_agegroup2_count,
        ]);
        $arr->push([
            'bracket' => '26 - 35 YO',
            'count' => $dcdata->active_agegroup3_count,
        ]);
        $arr->push([
            'bracket' => '36 - 45 YO',
            'count' => $dcdata->active_agegroup4_count,
        ]);
        $arr->push([
            'bracket' => '46 - 59 YO',
            'count' => $dcdata->active_agegroup5_count,
        ]);
        $arr->push([
            'bracket' => '60 YO & UP',
            'count' => $dcdata->active_agegroup6_count,
        ]);

        return response()->json($arr);
    }

    public function workDistribution() {
        $arr = collect();

        $group = Records::select('natureOfWork')->distinct('natureOfWork')->get();
        $group = $group->pluck('natureOfWork');

        foreach($group as $data) {
            $count = Forms::with('records')
            ->whereHas('records', function ($q) use ($data) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.natureOfWork', $data);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
            
            if($count != 0) {
                $arr->push([
                    'title' => !is_null($data) ? $data : 'NON-WORKING',
                    'count' => $count,
                ]);
            }
        }

        return response()->json($arr);
    }

    public function activeVaccineList() {
        sleep(10);
        $arr = collect();

        $group = Records::select('vaccinationName1')->distinct('vaccinationName1')->get();
        $group = $group->pluck('vaccinationName1');

        foreach($group as $data) {
            if($data != 'JANSSEN') {
                $partialCount = Forms::with('records')
                ->whereHas('records', function ($q) use ($data) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereNull('records.vaccinationDate2')
                    ->where('records.vaccinationName1', $data);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $fullCount = Forms::with('records')
                ->whereHas('records', function ($q) use ($data) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate2')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', $data);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
            }
            else {
                $partialCount = 0;

                $fullCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
            }

            if($partialCount != 0 || $fullCount != 0) {
                $arr->push([
                    'vaccineName' => $data,
                    'partialCount' => $partialCount,
                    'fullCount' => $fullCount,
                ]);
            }
        }
        
        return response()->json($arr);
    }

    public function currentDate() {
        $arr = collect();
        $arr->push([
            'currentDate' => date('m/d/Y'),
        ]);

        return response()->json($arr);
    }

    public function mwly() {
        $arr = collect();

        $d = MorbidityWeek::where('year', date('Y', strtotime('-1 Year')))->first();

        if(isset($d->mw53)) {
            $max = 53;
        }
        else {
            $max = 52;
        }

        for ($i=1;$i<=$max;$i++) {
            if($i <= 9) {
                $tstr = '0'.$i;
            }
            else {
                $tstr = $i;
            }
            $arr->push([
                'title' => 'MW'.$tstr,
                'count' => $d['mw'.$i] ?? null,
            ]);
        }

        return response()->json($arr);
    }

    public function mwcy() {
        $arr = collect();

        $d = MorbidityWeek::where('year', '2022')->first();

        $max = date('W');

        for ($i=1;$i<=$max;$i++) {
            if($i <= 9) {
                $tstr = '0'.$i;
            }
            else {
                $tstr = $i;
            }

            $arr->push([
                'title' => 'MW'.$tstr,
                '' => $d['mw'.$i] ?? null,
                ''
            ]);
        }

        return response()->json($arr);
    }

    public function mwcombine() {
        $arr = collect();

        $d1 = MorbidityWeek::where('year', date('Y'))->first();
        $d2 = MorbidityWeek::where('year', date('Y', strtotime('-1 Year')))->first();
        $d3 = MorbidityWeek::where('year', date('Y', strtotime('-2 Year')))->first();

        $max = 53;

        for ($i=1;$i<=$max;$i++) {
            if($i <= 9) {
                $tstr = '0'.$i;
            }
            else {
                $tstr = $i;
            }

            $arr->push([
                'title' => 'MW'.$tstr,
                'y'.date('Y') => $d1['mw'.$i] ?? null,
                'y'.date('Y', strtotime('-1 Year')) => $d2['mw'.$i] ?? null,
                'y'.date('Y', strtotime('-2 Years')) => $d3['mw'.$i] ?? null,
            ]);
        }

        return response()->json($arr);
    }
}
