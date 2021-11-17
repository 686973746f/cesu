<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\City;
use App\Models\Forms;
use App\Exports\DOHExport;
use App\Exports\FormsExport;
use Illuminate\Http\Request;
use App\Exports\SitReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class ReportController extends Controller
{
    public function index() {
        if(auth()->user()->isCesuAccount()) {
            $activeCount = Forms::with('records')
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

            $recoveredCount = Forms::with('records')
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
            $recoveredCount += Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('reinfected', 1)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

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

            $deathCount = Forms::with('records')
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

            $newActiveCount = Forms::with('records')
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

            $lateActiveCount = Forms::with('records')
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

            $newRecoveredCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
            ->whereDate('outcomeRecovDate', date('Y-m-d'))
            ->where('outcomeCondition', 'Recovered')
            ->where('reinfected', 0)
            ->where('dispoType', '!=', 6)
            ->count();
            
            $newRecoveredCount_facility = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('outcomeRecovDate', date('Y-m-d'))
            ->where('outcomeCondition', 'Recovered')
            ->where('reinfected', 0)
            ->where('dispoType', 6)
            ->count();

            $newRecoveredCount += $newRecoveredCount_facility;

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
            ->where('dispoType', '!=', 6)
            ->count();

            $newRecoveredCount_partialVaccinated_facility = Forms::with('records')
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
            ->where('reinfected', 0)
            ->where('dispoType', 6)
            ->count();

            $newRecoveredCount_partialVaccinated += $newRecoveredCount_partialVaccinated_facility;

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
            ->where('dispoType', '!=', 6)
            ->count();

            $newRecoveredCount_fullyVaccinated_facility = Forms::with('records')
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
            ->where('reinfected', 0)
            ->where('dispoType', 6)
            ->count();

            $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_facility;

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
            ->where('dispoType', '!=', 6)
            ->count();

            $newRecoveredCount_fullyVaccinated_janssen_facility = Forms::with('records')
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
            ->where('reinfected', 0)
            ->where('dispoType', 6)
            ->count();

            $newRecoveredCount_fullyVaccinated_janssen += $newRecoveredCount_fullyVaccinated_janssen_facility;

            $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;

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
            ->where('dispoType', '!=', 6)
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
            ->where('dispoType', '!=', 6)
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
            ->where('dispoType', '!=', 6)
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
            ->where('dispoType', '!=', 6)
            ->count();

            $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;

            $newDeathCount = Forms::with('records')
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

            $facilityCount = Forms::with('records')
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

            $hqCount = Forms::with('records')
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
            ->count();

            $hospitalCount = Forms::with('records')
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
            ->count();

            //Barangay Counter
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
                    'name' => $brgy->brgyName,
                    'confirmed' => $brgyConfirmedCount,
                    'active' => $brgyActiveCount,
                    'deaths' => $brgyDeathCount,
                    'recoveries' => $brgyRecoveryCount,
                    'suspected' => $brgySuspectedCount,
                    'probable' => $brgyProbableCount,
                ]);
            }

            return view('report_select', [
                'activeCount' => $activeCount,
                'totalActive_partialVaccinated' => $totalActive_partialVaccinated,
                'totalActive_fullyVaccinated' => $totalActive_fullyVaccinated,
                'recoveredCount' => $recoveredCount,
                'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
                'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
                'deathCount' => $deathCount,
                'totalDeath_partialVaccinated' => $totalDeath_partialVaccinated,
                'totalDeath_fullyVaccinated' => $totalDeath_fullyVaccinated,
                'newActiveCount' => $newActiveCount,
                'newActiveCount_partialVaccinated' => $newActiveCount_partialVaccinated,
                'newActiveCount_fullyVaccinated' => $newActiveCount_fullyVaccinated,
                'lateActiveCount' => $lateActiveCount,
                'lateActiveCount_partialVaccinated' => $lateActiveCount_partialVaccinated,
                'lateActiveCount_fullyVaccinated' => $lateActiveCount_fullyVaccinated,
                'newRecoveredCount' => $newRecoveredCount,
                'newRecoveredCount_partialVaccinated' => $newRecoveredCount_partialVaccinated,
                'newRecoveredCount_fullyVaccinated' => $newRecoveredCount_fullyVaccinated,
                'lateRecoveredCount' => $lateRecoveredCount,
                'lateRecoveredCount_partialVaccinated' => $lateRecoveredCount_partialVaccinated,
                'lateRecoveredCount_fullyVaccinated' => $lateRecoveredCount_fullyVaccinated,
                'newDeathCount' => $newDeathCount,
                'totalCasesCount' => $totalCasesCount,
                'totalCases_partialVaccinated' => $totalCasesCount_partialVaccinated,
                'totalCases_fullyVaccinated' => $totalCasesCount_fullyVaccinated,
                'facilityCount' => $facilityCount,
                'hqCount' => $hqCount,
                'hospitalCount' => $hospitalCount,
                'brgylist' => $brgyArray,
            ]);
        }
        else {
            if(auth()->user()->isBrgyAccount() && auth()->user()->brgy->displayInList == 1) {
                $activeCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $recoveredCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                //Bilangin pati current reinfection sa total ng recovered
                $recoveredCount += Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('reinfected', 1)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $deathCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->whereNotNull('records.vaccinationDate1')
                    ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                    ->where('records.vaccinationName1', 'JANSSEN');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated += $totalDeath_fullyVaccinated_janssen;

                $newActiveCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $lateActiveCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $newRecoveredCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('reinfected', 0)
                ->count();

                $newRecoveredCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $lateRecoveredCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('reinfected', 0)
                ->count();

                $lateRecoveredCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $newDeathCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalCasesCount_partialVaccinated = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
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

                $facilityCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('dispoType', 6)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hqCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('dispoType', 3)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hospitalCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->whereIn('dispoType', [1,2,5])
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                //Barangay Counter
                $brgyArray = collect();

                $brgyList = Brgy::where('displayInList', 1)
                ->where('city_id', 1)
                ->where('id', auth()->user()->brgy_id)
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
                        'name' => $brgy->brgyName,
                        'confirmed' => $brgyConfirmedCount,
                        'active' => $brgyActiveCount,
                        'deaths' => $brgyDeathCount,
                        'recoveries' => $brgyRecoveryCount,
                        'suspected' => $brgySuspectedCount,
                        'probable' => $brgyProbableCount,
                    ]);
                }

                return view('report_select', [
                    'activeCount' => $activeCount,
                    'totalActive_partialVaccinated' => $totalActive_partialVaccinated,
                    'totalActive_fullyVaccinated' => $totalActive_fullyVaccinated,
                    'recoveredCount' => $recoveredCount,
                    'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
                    'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
                    'deathCount' => $deathCount,
                    'totalDeath_partialVaccinated' => $totalDeath_partialVaccinated,
                    'totalDeath_fullyVaccinated' => $totalDeath_fullyVaccinated,
                    'newActiveCount' => $newActiveCount,
                    'newActiveCount_partialVaccinated' => $newActiveCount_partialVaccinated,
                    'newActiveCount_fullyVaccinated' => $newActiveCount_fullyVaccinated,
                    'lateActiveCount' => $lateActiveCount,
                    'lateActiveCount_partialVaccinated' => $lateActiveCount_partialVaccinated,
                    'lateActiveCount_fullyVaccinated' => $lateActiveCount_fullyVaccinated,
                    'newRecoveredCount' => $newRecoveredCount,
                    'newRecoveredCount_partialVaccinated' => $newRecoveredCount_partialVaccinated,
                    'newRecoveredCount_fullyVaccinated' => $newRecoveredCount_fullyVaccinated,
                    'lateRecoveredCount' => $lateRecoveredCount,
                    'lateRecoveredCount_partialVaccinated' => $lateRecoveredCount_partialVaccinated,
                    'lateRecoveredCount_fullyVaccinated' => $lateRecoveredCount_fullyVaccinated,
                    'newDeathCount' => $newDeathCount,
                    'totalCasesCount' => $totalCasesCount,
                    'totalCases_partialVaccinated' => $totalCasesCount_partialVaccinated,
                    'totalCases_fullyVaccinated' => $totalCasesCount_fullyVaccinated,
                    'facilityCount' => $facilityCount,
                    'hqCount' => $hqCount,
                    'hospitalCount' => $hospitalCount,
                    'brgylist' => $brgyArray,
                ]);
            }
            else if(auth()->user()->isCompanyAccount()) {
                return redirect()->route('home')
                ->with('status', 'You are not allowed to do that.')
                ->with('statustype', 'warning');
            }
            else {
                return redirect()->route('home')
                ->with('status', 'You are not allowed to do that.')
                ->with('statustype', 'warning');
            }
        }
    }

    public function viewDaily() {
        /*
        $list = Forms::all();
        $brgy = Brgy::all();

        $listToday = Forms::where(function ($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->get();

        $notPresent = Forms::where(function ($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where(function ($query) {
            $query->where('isPresentOnSwabDay', 0)
            ->orWhereNull('isPresentOnSwabDay');
        })->get();

        return view('report_daily', [
            'listToday' => $listToday,
            'notPresent' => $notPresent,
            'list' => $list,
            'brgy_list' => $brgy
        ]);
        */
    }

    public function viewClustering($city_id, $brgy_id) {
        $city_data = City::findOrFail($city_id);
        $brgy_data = Brgy::findOrFail($brgy_id);

        $clustered_forms = Forms::where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereHas('records', function ($query) use ($brgy_data, $city_data){
            $query->where('records.address_brgy', $brgy_data->brgyName)
            ->where('records.address_city', $city_data->cityName);
        })->get();

        return view('report_clustering', [
            'clustered_forms' => $clustered_forms,
            'brgy_name' => $brgy_data->brgyName,
        ]);
    }

    public function viewSituational() {
        /*
        $forms = Forms::all();
        $brgy = Brgy::all();

        $formstotal = $forms->count();
        $formsActiveTotal = $forms->where('outcomeCondition', 'Active')->count();
        $formsConfirmedTotal = $forms->where('caseClassification', 'Confirmed')->count();
        $formsActiveConfirmedTotal = Forms::where('outcomeCondition', 'Active')->where('caseClassification', 'Confirmed')->count();
        $recoveryCount = $forms->where('outcomeCondition', 'Recovered')->count();
        $fatalityCount = $forms->where('outcomeCondition', 'Died')->count();
        $positiveCount = $forms->where('caseClassification', 'Confirmed')->count();
        $hqCount = $forms->where('dispositionType', 3)->where('outcomeCondition', 'Active')->where('caseClassification', 'Confirmed')->count();

        return view('report_situational', [
            'list' => $forms,
            'brgy_list' => $brgy,
            'formstotal' => $formstotal,
            'formsActiveTotal' => $formsActiveTotal,
            'formsConfirmedTotal' => $formsConfirmedTotal,
            'formsActiveConfirmedTotal' => $formsActiveConfirmedTotal,
            'recoveryCount' => $recoveryCount,
            'fatalityCount' => $fatalityCount,
            'positiveCount' => $positiveCount,
            'hqCount' => $hqCount,
            'recRate' => round(($recoveryCount / $formsActiveTotal) * 100, 2),
            'fatRate' => round(($fatalityCount / $formsActiveTotal) * 100, 2),
            'posRate' => round(($positiveCount / $formstotal) * 100, 2),
            'hqRate' => round(($hqCount / $formsActiveConfirmedTotal) * 100, 2),
        ]);
        */
    }

    public function viewSituationalv2() {
        /*
        $brgyList = Brgy::where('city_id', 1) //dapat mai-automate ang city id soon base sa system settings
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        $formsList = Forms::with('records')->get();

        return view('situationalv2_index', [
            'brgyList' => $brgyList,
            'formsList' => $formsList,
        ]);
        */
    }

    public function printSituationalv2() {
        return (new SitReportExport)->download('invoices.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function makeAllSuspected() {
        $query = Forms::where(function($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where('isPresentOnSwabDay', 0)
        ->update(['caseClassification' => 'Suspect']);

        return redirect()->action([ReportController::class, 'index'])->with('status', 'All patients who were absent for today were moved in SUSPECTED Case.')->with('statustype', 'success');
    }

    public function dohExportAll() {
        if(auth()->user()->isCesuAccount()) {
            function suspectedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
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
                ->orderby('morbidityMonth', 'asc')
                ->cursor() as $user) {
                    yield $user;
                }
            }
    
            function probableGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Probable')
                ->where('outcomeCondition', 'Active')
                ->where(function ($q) {
                    $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                    ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
                })
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
    
            function confirmedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
    
            function negativeGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Non-COVID-19 Case')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
        }
        else if(auth()->user()->isBrgyAccount()) {
            function suspectedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
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
                ->orderby('morbidityMonth', 'asc')
                ->cursor() as $user) {
                    yield $user;
                }
            }
    
            function probableGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Probable')
                ->where('outcomeCondition', 'Active')
                ->where(function ($q) {
                    $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                    ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
                })
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
    
            function confirmedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
    
            function negativeGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Non-COVID-19 Case')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->orderby('morbidityMonth', 'asc')->cursor() as $user) {
                    yield $user;
                }
            }
        }

        $sheets = new SheetCollection([
            'Suspected' => suspectedGenerator(),
            'Probable' => probableGenerator(),
            'Confirmed' => confirmedGenerator(),
            'Negative' => negativeGenerator(),
        ]);

        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        return (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('GENTRI_COVID19_DATABASE_'.date('Ymd').'.xlsx', function ($form) {
            $arr_sas = explode(",", $form->SAS);
            $arr_othersas = explode(",", $form->SASOtherRemarks);
            $arr_como = explode(",", $form->COMO);

            if(is_null($form->testType2)) {
                $testType = $form->testType1;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected1));
                $testReleased = (!is_null($form->testDateReleased1)) ? date('m/d/Y', strtotime($form->testDateReleased1)) : 'N/A';
                $testResult = $form->testResult1;
            }
            else {
                //ilalagay sa unahan yung pangalawang swab dahil mas bago ito
                $testType = $form->testType2;
                $testDate = date('m/d/Y', strtotime($form->testDateCollected2));
                $testReleased = (!is_null($form->testDateReleased2)) ? date('m/d/Y', strtotime($form->testDateReleased2)) : 'N/A';
                $testResult = $form->testResult2;
            }

            if($form->dispoType == 1) {
                //HOSPITAL
                $dispo = 'ADMITTED';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 2) {
                //OTHER ISOLATION FACILITY
                $dispo = 'ADMITTED';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 3) {
                //HOME QUARANTINE
                $dispo = 'HOME QUARANTINE';
                $dispoName = "N/A";
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 4) {
                //DISCHARGED TO HOME
                $dispo = 'DISCHARGED';
                $dispoName = "N/A";
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 5) {
                //OTHERS
                $dispo = 'ADMITTED';
                $dispoName = ($form->dispoName) ? mb_strtoupper($form->dispoName) : 'N/A';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else if($form->dispoType == 6) {
                //GENTRI ISOLATION FACILITY #1 (SANTIAGO OVAL)
                $dispo = 'ADMITTED';
                $dispoName = 'GENERAL TRIAS ISOLATION FACILITY';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }

            if($form->outcomeCondition == 'Recovered') {
                if($form->dispoType == 3) {
                    $dispo = 'CLEARED';
                }
                else {
                    $dispo = 'DISCHARGED';
                }
            }

            //Vaccination Facility
            if(!is_null($form->records->vaccinationDate2)) {
                if($form->records->vaccinationFacility2) {
                    $vFacility = $form->records->vaccinationFacility2;
                }
                else {
                    $vFacility = 'N/A';
                }
            }
            else {
                if($form->records->vaccinationFacility1) {
                    $vFacility = $form->records->vaccinationFacility1;
                }
                else {
                    $vFacility = 'N/A';
                }
            }

            //Remarks
            if($form->reinfected == 1) {
                if(!is_null($form->remarks)) {
                    $remarks = 'REINFECTED | '.$form->remarks;
                }
                else {
                    $remarks = 'REINFECTED';
                }
            }
            else {
                if(!is_null($form->remarks)) {
                    $remarks = $form->remarks;
                }
                else {
                    $remarks = 'N/A';
                }
            }

            return [
                'MM (Morbidity Month)' => date('m/d/Y', strtotime($form->morbidityMonth)),
                'MW (Morbidity Week' => Carbon::parse($form->morbidityMonth)->format('W'),
                'DATE REPORTED' => date('m/d/Y', strtotime($form->dateReported)),
                'DRU' => $form->drunit,
                'REGION OF DRU' => $form->drregion,
                'MUNCITY OF DRU' => $form->drprovince,
                'LAST NAME' => $form->records->lname,
                'FIRST NAME' => $form->records->fname,
                'MIDDLE NAME' => (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
                'DOB' => date('m/d/Y', strtotime($form->records->bdate)),
                'AGE (AGE IN YEARS)' => $form->records->getAge(),
                'SEX(M/F)' => substr($form->records->gender,0,1),
                'NATIONALITY' => $form->records->nationality,
                'REGION' => 'IV A',
                'PROVINCE/HUC' => $form->records->address_province,
                'MUNICIPALITY/CITY' => $form->records->address_city,
                'BARANGAY' => $form->records->address_brgy,
                'HOUSE N. AND STREET OR NEAREST LANDMARK' => $form->records->address_houseno.', '.$form->records->address_street,
                'CONTACT N.' => ($form->records->mobile != '09190664324') ? $form->records->mobile : 'N/A',
                'OCCUPATION' => (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
                'HEALTHCARE WORKER(Y/N)' => ($form->isHealthCareWorker == 1) ? 'Y' : 'N',
                'PLACE OF WORK' => ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
                'SEVERITY OF THE CASE (ASYMTOMATIC,MILD,MODERATE,SEVERE,CRITICAL)' => $form->healthStatus,
                'PREGNANT (Y/N)' => ($form->records->isPregnant == 1) ? 'Y' : 'N',
                'ONSET OF ILLNESS' => (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
                'FEVER(Y/N)' => (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
                'COUGH (Y/N)' => (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
                'COLDS (Y/N)' => (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas)) ? 'Y' : 'N',
                'DOB (Y/N)' => (in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
                'LOSS OF SMELL (Y/N)' => (in_array('Anosmia (Loss of Smell)', $arr_sas)) ? 'Y' : 'N',
                'LOSS OF TASTE (Y/N)' => (in_array('Ageusia (Loss of Taste)', $arr_sas)) ? 'Y' : 'N',
                'SORETHROAT (Y/N)' => (in_array('Sore throat', $arr_sas)) ? 'Y' : 'N',
                'DIARRHEA (Y/N)' => (in_array('Diarrhea', $arr_sas)) ? 'Y' : 'N',
                'OTHER SYMPTOMS' => (!is_null($form->SASOtherRemarks)) ? mb_strtoupper($form->SASOtherRemarks) : 'N/A',
                'W. COMORBIDITY (Y/N)' => ($form->COMO != 'None') ? 'Y' : 'N',
                'COMORBIDITY (HYPERTENSIVE, DIABETIC, WITH HEART PROBLEM, AND OTHERS)' => ($form->COMO != 'None') ? $form->COMO : 'N/A',
                'DATE OF SPECIMEN COLLECTION' => $testDate,
                'ANTIGEN (POSITIVE/NEGATIVE)' => ($testType == 'ANTIGEN') ? $testResult : 'N/A',
                'PCR(POSITIVE/NEGATIVE)' => ($testType == 'OPS' || $testType == 'NPS' || $testType == 'OPS AND NPS') ? $testResult : 'N/A',
                'RDT(+IGG, +IGM,NEGATIVE)' => ($testType == 'ANTIBODY') ? $testResult : 'N/A',
                'CLASSIFICATION (CONFIRMED,SUSPECTED,PROBABLE,FOR VALIDATION)' => $form->caseClassification,
                'QUARANTINE STATUS (ADMITTED,HOME QUARANTINE,TTMF,CLEARED,DISCHARGED)' => $dispo,
                'NAME OF FACILITY (FOR FACILITY QUARANTINE AND ADMITTED)' => $dispoName,
                'DATE START OF QUARANTINE' => $dispoDate,
                'DATE COMPLETED QUARANTINE (FOR HOME AND FACILITY QUARANTINE)' => ($form->dispoType == 4) ? $dispoDate : 'N/A',
                'OUTCOME(ALIVE/RECOVERED/DIED)' => $form->outcomeCondition,
                'DATE RECOVERED' => ($form->outcomeCondition == 'Recovered') ? date('m/d/Y', strtotime($form->outcomeRecovDate)) : 'N/A',
                'DATE DIED' => ($form->outcomeCondition == 'Died') ? date('m/d/Y', strtotime($form->outcomeDeathDate)) : 'N/A',
                'CAUSE OF DEATH' => ($form->outcomeCondition == 'Died') ? mb_strtoupper($form->deathImmeCause) : 'N/A',
                'W. TRAVEL HISTORY(Y/N)' => ($form->expoitem2 == 1) ? 'Y' : 'N',
                'PLACE OF TRAVEL' => (!is_null($form->placevisited)) ? $form->placevisited : 'N/A',
                'DATE OF TRAVEL' => (!is_null($form->localDateDepart1)) ? date('m/d/Y', strtotime($form->localDateDepart1)) : 'N/A',
                'LSI (Y/N)' => ($form->isLSI == 1) ? 'Y' : 'N',
                'ADDRESS(LSI)' => ($form->isLSI == 1) ? $form->LSICity : 'N/A',
                'OFW(Y/N)' => ($form->isOFW == 1 && $form->ofwType == 1) ? 'Y': 'N',
                'PLACE OF ORIGIN (OFW)' => ($form->isOFW == 1 && $form->ofwType == 1) ? $form->OFWCountyOfOrigin : 'N/A',
                'DATE OF ARRIVAL (OFW)' => "N/A", //OFW DATE OF ARRIVAL, WALA NAMANG GANITO SA CIF DATABASE ROWS,
                'AUTHORIZED PERSON OUTSIDE RESIDENCE (Y/N)' => ($form->isLSI == 1 && $form->lsiType == 0) ? 'Y' : 'N',
                'LOCAL/IMPORTED CASE' => "UNKNOWN",
                'RETURNING OVERSEAS FILIPINO (Y/N)' => ($form->isOFW == 1 && $form->ofwType == 2) ? 'Y': 'N',
                'REMARKS' => $remarks,
                'VACCINATED (Y/N)' => (!is_null($form->records->vaccinationDate1)) ? 'Y' : 'N',
                'VACCINE' => (!is_null($form->records->vaccinationDate1)) ? $form->records->vaccinationName1 : 'N/A',
                '1ST DOSE (ACTUAL DATE)' => (!is_null($form->records->vaccinationDate1)) ? date('m/d/Y', strtotime($form->records->vaccinationDate1)) : 'N/A',
                '2ND DOSE (ACTUAL DATE)' => (!is_null($form->records->vaccinationDate2)) ? date('m/d/Y', strtotime($form->records->vaccinationDate2)) : 'N/A',
                'VACCINATION FACILITY' => $vFacility,
                'YEAR' => date('Y', strtotime($form->dateReported)),
            ];
        });
    }

    public function dilgExportAll() {
        $sheets = new SheetCollection([
            'General Trias City' => Brgy::where('displayInList', 1)->orderBy('brgyName', 'ASC')->get(),
        ]);

        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        return (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('DILG_'.date('Ymd').'.xlsx', function ($form) {
            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($form) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $form->brgyName);
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
            ->whereHas('records', function ($q) use ($form) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $form->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            })
            ->count();

            $brgyActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($form) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $form->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
            
            return [
                'Code' => $form->dilgCustCode,
                'Province' => 'Cavite',
                'City/Municipality' => 'General Trias City',
                'Barangay' => $form->brgyName,
                '' => '',
                'Estimated Population' => number_format($form->estimatedPopulation),
                'Suspected' => number_format($brgySuspectedCount),
                'Probable' => number_format($brgyProbableCount),
                'Confirmed' => number_format($brgyActiveCount),
                '' => '',
                'Name of Respondent' => 'RONALD A. MOJICA',
                'Office/Designation' => 'DILG GEN.TRI, CAVITE / CLGOO',
            ];
        });
    }

    public function reportExport(Request $request) {
        $request->validate([
            'eStartDate' => 'required|date|before:tomorrow',
            'eEndDate' => 'required|date|before:tomorrow',
            'rType' => 'required',
        ]);

        if($request->rType == "DOH") {
            $query = Forms::where(function ($q) use ($request) {
                $q->whereBetween('testDateCollected1', [$request->eStartDate, $request->eEndDate])
                ->orWhereBetween('testDateCollected2', [$request->eStartDate, $request->eEndDate]);
            })
            ->orderBy('testDateCollected1', 'ASC')
            ->orderBy('testDateCollected2', 'ASC')
            ->pluck('id')->toArray();

            return Excel::download(new DOHExport($query), 'DOH_Excel_'.date('m_d_Y').'.xlsx');
        }
        else {
            $query = Forms::where(function ($q) use ($request) {
                $q->whereBetween('testDateCollected1', [$request->eStartDate, $request->eEndDate])
                ->orWhereBetween('testDateCollected2', [$request->eStartDate, $request->eEndDate]);
            })
            ->orderBy('testDateCollected1', 'ASC')
            ->orderBy('testDateCollected2', 'ASC')
            ->pluck('id')->toArray();

            return Excel::download(new FormsExport($query), 'CIF_ALL_'.date("m_d_Y").'.xlsx');
        }
    }
}
