<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use App\Models\Records;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JsonReportController extends Controller
{
    public function __construct() {
        DB::setDefaultConnection('mysqlforjson');
    }

    public function totalCases() {
        sleep(20);
        $arr = [];

        $totalActiveCases = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalActive_fullyVaccinated += $totalActive_fullyVaccinated_janssen;

        $totalRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //Bilangin pati current reinfection sa total ng recovered
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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalRecovered_fullyVaccinated += $totalRecovered_fullyVaccinated_janssen;
        
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
            ->where('records.vaccinationName1', '!=', 'JANSSEN');
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
        ->where('reinfected', 0)
        ->count();

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
        ->where('reinfected', 0)
        ->count();

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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->count();

        $newActiveCount_fullyVaccinated += $newActiveCount_fullyVaccinated_janssen;

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
        ->where('reinfected', 0)
        ->count();

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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->count();

        $lateActiveCount_fullyVaccinated += $lateActiveCount_fullyVaccinated_janssen;

        $newRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->count();

        $lateRecoveredCount = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->count();

        $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;

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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalCasesCount_fullyVaccinated += $totalCasesCount_fullyVaccinated_janssen;

        //Reinfection
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

        $grand_total_reinfection = $reinfect_hidden_count;

        array_push($arr, [
            'totalActiveCases' => $totalActiveCases,
            'totalActive_partialVaccinated' => $totalActive_partialVaccinated,
            'totalActive_fullyVaccinated' => $totalActive_fullyVaccinated,
            'totalRecovered' => $totalRecovered,
            'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
            'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
            'totalDeaths' => $totalDeaths,
            'totalDeath_partialVaccinated' => $totalDeath_partialVaccinated,
            'totalDeath_fullyVaccinated' => $totalDeath_fullyVaccinated,
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
            'grand_total_reinfection' => $grand_total_reinfection,
        ]);

        return response()->json($arr);
    }

    public function dailyNewCases() {
        
    }

    public function weeklyCasesDist() {

    }

    public function currentYearCasesDist() {
        ini_set('max_execution_time', 600);
        sleep(40); //try to make this 20 next time
        
        $arr = [];

        //$period = CarbonPeriod::create(date('Y-01-01'), date('Y-m-d')) WHOLE YEAR; 
        $period = CarbonPeriod::create(date('Y-m-d', strtotime('-6 Months')), date('Y-m-d')); //6 MONTHS
        foreach($period as $date) {
            $dailyCasesCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('reinfected', 0)
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

    public function lastYearCasesDist() {

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
            ->where('reinfected', 0)
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
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
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
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
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

            $brgyRecoveryCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            //Reinfection Count
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

            $brgyArray->push([
                'brgyName' => $brgy->brgyName,
                'numOfConfirmedCases' => $brgyConfirmedCount,
                'numOfActiveCases' => $brgyActiveCount,
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

        $male = Forms::with('records')
        ->whereHas('records', function($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->where('gender', 'MALE');
        })->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->where('reinfected', 0)
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
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        array_push($arr, [
            'gender' => 'MALE',
            'count' => $male,
        ]);

        array_push($arr, [
            'gender' => 'FEMALE',
            'count' => $female,
        ]);

        return response()->json($arr);
    }

    public function conditionBreakdown() {
        $arr = [];

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
            ->where('reinfected', 0)
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
            ->where('reinfected', 0)
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
            ->where('reinfected', 0)
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
            ->where('reinfected', 0)
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
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count(),
        ]);

        return response()->json($arr);
    }

    public function ageDistribution() {
        sleep(10);
        $arr = collect();

        //Fetch Current Active Cases Only
        function recordsGenerator() {
            foreach(Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->cursor() as $user) {
                yield $user;
            }
        }

        $activelist = recordsGenerator();

        $ageBracket1 = 0;
        $ageBracket2 = 0;
        $ageBracket3 = 0;
        $ageBracket4 = 0;
        $ageBracket5 = 0;
        $ageBracket6 = 0;

        foreach($activelist as $item) {
            if($item->records->getAgeInt() >= 0 && $item->records->getAgeInt() <= 17) {
                $ageBracket1++;
            }
            else if($item->records->getAgeInt() >= 18 && $item->records->getAgeInt() <= 25) {
                $ageBracket2++;
            }
            else if($item->records->getAgeInt() >= 26 && $item->records->getAgeInt() <= 35) {
                $ageBracket3++;
            }
            else if($item->records->getAgeInt() >= 36 && $item->records->getAgeInt() <= 45) {
                $ageBracket4++;
            }
            else if($item->records->getAgeInt() >= 46 && $item->records->getAgeInt() <= 59) {
                $ageBracket5++;
            }
            else if($item->records->getAgeInt() >= 60) {
                $ageBracket6++;
            }
        }

        $arr->push([
            'bracket' => '0 - 17 YO',
            'count' => $ageBracket1,
        ]);
        $arr->push([
            'bracket' => '18 - 25 YO',
            'count' => $ageBracket2,
        ]);
        $arr->push([
            'bracket' => '26 - 35 YO',
            'count' => $ageBracket3,
        ]);
        $arr->push([
            'bracket' => '36 - 45 YO',
            'count' => $ageBracket4,
        ]);
        $arr->push([
            'bracket' => '46 - 59 YO',
            'count' => $ageBracket5,
        ]);
        $arr->push([
            'bracket' => '60 YO & UP',
            'count' => $ageBracket6,
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
            ->where('reinfected', 0)
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
                ->where('reinfected', 0)
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
                ->where('reinfected', 0)
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
                ->where('reinfected', 0)
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
}
