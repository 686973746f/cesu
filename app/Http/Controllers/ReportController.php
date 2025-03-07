<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\City;
use App\Models\Forms;
use App\Models\Records;
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
        if(time() >= strtotime('16:00') && time() <= strtotime('16:15')) {
            return redirect()->route('home')
            ->with('status', 'Daily Report currently generating on background. Please go back after 04:15PM.')
            ->with('statustype', 'warning');
        }

        $totalCasesCount = 0;
        $totalCasesCount_partialVaccinated = 0;
        $totalCasesCount_fullyVaccinated = 0;
        $totalCasesCount_boostered = 0;

        if(auth()->user()->isCesuAccount()) {
            if(auth()->user()->canExportReport != 1) {
                return redirect()->route('home')
                ->with('status', 'You are not allowed to do that.')
                ->with('statustype', 'warning');
            }

            $activeCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $activeCount_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $activeCount_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');
            
            $totalActive_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalActive_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalActive_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalActive_booster = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate3')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
            })
            ->where('status', 'approved');

            $recoveredCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $recoveredCount_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $recoveredCount_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            //Bilangin pati current reinfection sa total ng recovered
            /*
            $recoveredCount += Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('reinfected', 1)
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
            */

            $totalRecovered_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalRecovered_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalRecovered_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalRecovered_booster = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate3')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
            })
            ->where('status', 'approved');

            $deathCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $deathCount_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $deathCount_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            $totalDeath_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalDeath_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalDeath_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN')
                ->whereNull('records.vaccinationDate3');
            })
            ->where('status', 'approved');

            $totalDeath_booster = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate3')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
            })
            ->where('status', 'approved');

            $newActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $newActiveCount_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newActiveCount_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newActiveCount_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateActiveCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $lateActiveCount_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateActiveCount_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateActiveCount_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');
            
            $newRecoveredCount_facility = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $newRecoveredCount_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount_partialVaccinated_facility = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount_fullyVaccinated_facility = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newRecoveredCount_fullyVaccinated_janssen_facility = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateRecoveredCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $lateRecoveredCount_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateRecoveredCount_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $lateRecoveredCount_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');

            $newDeathCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });

            /*
            $totalCasesCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $totalCasesCount_partialVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereNull('records.vaccinationDate2')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');

            $totalCasesCount_fullyVaccinated = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate2')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', '!=', 'JANSSEN');
            })
            ->where('status', 'approved');
            
            $totalCasesCount_fullyVaccinated_janssen = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereNotNull('records.vaccinationDate1')
                ->whereRaw('DATE(DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY)) <= CURDATE()')
                ->where('records.vaccinationName1', 'JANSSEN');
            })
            ->where('status', 'approved');
            */

            $facilityCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 6)
            ->where('status', 'approved');

            $facilityTwoCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 7)
            ->where('status', 'approved');

            $hqCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('dispoType', 3)
            ->where('status', 'approved');

            $hospitalCount = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->whereIn('dispoType', [1,2,5])
            ->where('status', 'approved');

            //Breakdown
            $active_asymptomatic_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $active_mild_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $active_moderate_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');
            
            $active_severe_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $active_critical_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            //Age Group
            $active_agegroup1_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) <= 17')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup1_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) <= 17')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            $active_agegroup2_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 18 AND 25')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup2_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 18 AND 25')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            $active_agegroup3_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 26 AND 35')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup3_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 26 AND 35')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');
            
            $active_agegroup4_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 36 AND 45')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup4_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 36 AND 45')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            $active_agegroup5_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 46 AND 59')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup5_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN 46 AND 59')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            $active_agegroup6_count_male = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 60')
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved');

            $active_agegroup6_count_female = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 60')
                ->where('records.gender', 'FEMALE');
            })
            ->where('status', 'approved');

            //Work Distribution
            $workdistribution_array = [];

            $wdgroup = Records::select('natureOfWork')->distinct('natureOfWork')->get();
            $wdgroup = $wdgroup->pluck('natureOfWork');

            //Vaccines
            $vc_array = [];

            $vcgroup = Records::select('vaccinationName1')->distinct('vaccinationName1')->get();
            $vcgroup = $vcgroup->pluck('vaccinationName1');

            //Reinfection
            $totalActiveReinfection = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $totalRecoveredReinfection = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            $totalDeathReinfection = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved');

            if(request()->input('start_date') && request()->input('end_date') || request()->input('t2_end_date')) {
                if(request()->input('filter_type') == 2) {
                    $sDate = '2020-01-01'; //Covid Started 2020
                    $eDate = request()->input('t2_end_date');
                }
                else {
                    $sDate = request()->input('start_date');
                    $eDate = request()->input('end_date');
                }

                $toggleFilterReport = 1;

                $activeCount = $activeCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $activeCount_male = $activeCount_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                
                $activeCount_female = $activeCount_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalActive_partialVaccinated = $totalActive_partialVaccinated->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalActive_fullyVaccinated = $totalActive_fullyVaccinated->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalActive_fullyVaccinated_janssen = $totalActive_fullyVaccinated_janssen->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalActive_booster = $totalActive_booster->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $deathCount = $deathCount->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $deathCount_male = $deathCount_male->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $deathCount_female = $deathCount_female->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalDeath_partialVaccinated = $totalDeath_partialVaccinated->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalDeath_fullyVaccinated = $totalDeath_fullyVaccinated->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalDeath_fullyVaccinated_janssen = $totalDeath_fullyVaccinated_janssen->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalDeath_booster = $totalDeath_booster->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                //Recovered Count is Equals to Zero kasi lahat ay nakalagay sa Active when Filtering via Date
                //Update 09082022: I don't think so

                $recoveredCount = $activeCount - $deathCount;

                $recoveredCount_male = $activeCount_male - $deathCount_male;
                
                $recoveredCount_female = $activeCount_female - $deathCount_female;

                $totalRecovered_partialVaccinated = $totalActive_partialVaccinated - $totalDeath_partialVaccinated;

                $totalRecovered_fullyVaccinated = $totalActive_fullyVaccinated - $totalDeath_fullyVaccinated;

                $totalRecovered_fullyVaccinated_janssen = $totalActive_fullyVaccinated_janssen - $totalDeath_fullyVaccinated_janssen;

                $totalRecovered_booster = $totalActive_booster - $totalDeath_booster;

                //New Cases Count Not Counted when Filtering
                $newActiveCount = 0;

                $newActiveCount_partialVaccinated = 0;

                $newActiveCount_fullyVaccinated = 0;

                $newActiveCount_fullyVaccinated_janssen = 0;

                $lateActiveCount = 0;

                $lateActiveCount_partialVaccinated = 0;

                $lateActiveCount_fullyVaccinated = 0;

                $lateActiveCount_fullyVaccinated_janssen = 0;

                $newRecoveredCount = 0;

                $newRecoveredCount_facility = 0;

                $newRecoveredCount_partialVaccinated = 0;

                $newRecoveredCount_partialVaccinated_facility = 0;

                $newRecoveredCount_fullyVaccinated = 0;

                $newRecoveredCount_fullyVaccinated_facility = 0;

                $newRecoveredCount_fullyVaccinated_janssen = 0;

                $newRecoveredCount_fullyVaccinated_janssen_facility = 0;

                $lateRecoveredCount = 0;

                $lateRecoveredCount_partialVaccinated = 0;

                $lateRecoveredCount_fullyVaccinated = 0;

                $lateRecoveredCount_fullyVaccinated_janssen = 0;

                $newDeathCount = 0;

                /*
                $totalCasesCount = $totalCasesCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalCasesCount_partialVaccinated = $totalCasesCount_partialVaccinated->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalCasesCount_fullyVaccinated = $totalCasesCount_fullyVaccinated->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalCasesCount_fullyVaccinated_janssen = $totalCasesCount_fullyVaccinated_janssen->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                */

                $facilityCount = $facilityCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $facilityTwoCount = $facilityTwoCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                
                $hqCount = $hqCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $hospitalCount = $hospitalCount->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_asymptomatic_count = $active_asymptomatic_count->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->where('healthStatus', 'Asymptomatic')
                ->count();
                
                $active_mild_count = $active_mild_count->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->where('healthStatus', 'Mild')
                ->count();

                $active_moderate_count = $active_moderate_count->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->where('healthStatus', 'Moderate')
                ->count();

                $active_severe_count = $active_severe_count->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->where('healthStatus', 'Severe')
                ->count();
                
                $active_critical_count = $active_critical_count->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->where('healthStatus', 'Critical')
                ->count();

                $active_agegroup1_count_male = $active_agegroup1_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup1_count_female = $active_agegroup1_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup2_count_male = $active_agegroup2_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup2_count_female = $active_agegroup2_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup3_count_male = $active_agegroup3_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup3_count_female = $active_agegroup3_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup4_count_male = $active_agegroup4_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                
                $active_agegroup4_count_female = $active_agegroup4_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup5_count_male = $active_agegroup5_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup5_count_female = $active_agegroup5_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                
                $active_agegroup6_count_male = $active_agegroup6_count_male->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $active_agegroup6_count_female = $active_agegroup6_count_female->where('caseClassification', 'Confirmed')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                foreach($wdgroup as $data) {
                    $count = Forms::with('records')
                    ->whereHas('records', function ($q) use ($data) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS')
                        ->where('records.natureOfWork', $data);
                    })
                    ->where('status', 'approved')
                    ->where('caseClassification', 'Confirmed')
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->count();
                    
                    if($count != 0) {
                        array_push($workdistribution_array, [
                            'title' => !is_null($data) ? $data : 'NON-WORKING',
                            'count' => $count,
                        ]);
                    }
                }

                foreach($vcgroup as $data) {
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
                        ->whereBetween('morbidityMonth', [$sDate, $eDate])
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
                        ->whereBetween('morbidityMonth', [$sDate, $eDate])
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
                        ->whereBetween('morbidityMonth', [$sDate, $eDate])
                        ->count();
                    }
        
                    if($partialCount != 0 || $fullCount != 0) {
                        array_push($vc_array, [
                            'vaccineName' => $data,
                            'partialCount' => $partialCount,
                            'fullCount' => $fullCount,
                        ]);
                    }
                }

                $totalActiveReinfection = $totalActiveReinfection->where('reinfected', 1)
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();
                
                $totalDeathReinfection = $totalDeathReinfection->where('reinfected', 1)
                ->where('outcomeCondition', 'Died')
                ->whereBetween('morbidityMonth', [$sDate, $eDate])
                ->count();

                $totalRecoveredReinfection = $totalActiveReinfection - $totalDeathReinfection;
            }
            else {
                $toggleFilterReport = 0;

                $activeCount = $activeCount->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $activeCount_male = $activeCount_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $activeCount_female = $activeCount_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_partialVaccinated = $totalActive_partialVaccinated->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_fullyVaccinated = $totalActive_fullyVaccinated->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_fullyVaccinated_janssen = $totalActive_fullyVaccinated_janssen->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalActive_booster = $totalActive_booster->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $recoveredCount = $recoveredCount->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $recoveredCount_male = $recoveredCount_male->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $recoveredCount_female = $recoveredCount_female->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_partialVaccinated = $totalRecovered_partialVaccinated->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_fullyVaccinated = $totalRecovered_fullyVaccinated->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_fullyVaccinated_janssen = $totalRecovered_fullyVaccinated_janssen->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalRecovered_booster = $totalRecovered_booster->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $deathCount = $deathCount->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $deathCount_male = $deathCount_male->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $deathCount_female = $deathCount_female->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_partialVaccinated = $totalDeath_partialVaccinated->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated = $totalDeath_fullyVaccinated->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeath_fullyVaccinated_janssen = $totalDeath_fullyVaccinated_janssen->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalDeath_booster = $totalDeath_booster->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $newActiveCount = $newActiveCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_partialVaccinated = $newActiveCount_partialVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_fullyVaccinated = $newActiveCount_fullyVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newActiveCount_fullyVaccinated_janssen = $newActiveCount_fullyVaccinated_janssen->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount = $lateActiveCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_partialVaccinated = $lateActiveCount_partialVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_fullyVaccinated = $lateActiveCount_fullyVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $lateActiveCount_fullyVaccinated_janssen = $lateActiveCount_fullyVaccinated_janssen->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->count();

                $newRecoveredCount = $newRecoveredCount->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_partialVaccinated = $newRecoveredCount_partialVaccinated->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();
                
                $newRecoveredCount_fullyVaccinated = $newRecoveredCount_fullyVaccinated->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_fullyVaccinated_janssen = $newRecoveredCount_fullyVaccinated_janssen->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount = $lateRecoveredCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_partialVaccinated = $lateRecoveredCount_partialVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated = $lateRecoveredCount_fullyVaccinated->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated_janssen = $lateRecoveredCount_fullyVaccinated_janssen->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                /*

                Old Formula

                $newRecoveredCount = $newRecoveredCount->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $newRecoveredCount_facility = $newRecoveredCount_facility->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', 6)
                ->count();

                $newRecoveredCount_partialVaccinated = $newRecoveredCount_partialVaccinated->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $newRecoveredCount_partialVaccinated_facility = $newRecoveredCount_partialVaccinated_facility->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', 6)
                ->count();

                $newRecoveredCount_fullyVaccinated = $newRecoveredCount_fullyVaccinated->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $newRecoveredCount_fullyVaccinated_facility = $newRecoveredCount_fullyVaccinated_facility->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', 6)
                ->count();

                $newRecoveredCount_fullyVaccinated_janssen = $newRecoveredCount_fullyVaccinated_janssen->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $newRecoveredCount_fullyVaccinated_janssen_facility = $newRecoveredCount_fullyVaccinated_janssen_facility->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', 6)
                ->count();

                $lateRecoveredCount = $lateRecoveredCount->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $lateRecoveredCount_partialVaccinated = $lateRecoveredCount_partialVaccinated->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $lateRecoveredCount_fullyVaccinated = $lateRecoveredCount_fullyVaccinated->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();

                $lateRecoveredCount_fullyVaccinated_janssen = $lateRecoveredCount_fullyVaccinated_janssen->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6)
                ->count();
                */

                $newDeathCount = $newDeathCount->where(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('outcomeDeathDate', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                })->orWhere(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('morbidityMonth', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                })->count();

                /*
                $totalCasesCount = $totalCasesCount->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalCasesCount_partialVaccinated = $totalCasesCount_partialVaccinated->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalCasesCount_fullyVaccinated = $totalCasesCount_fullyVaccinated->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalCasesCount_fullyVaccinated_janssen = $totalCasesCount_fullyVaccinated_janssen->where('caseClassification', 'Confirmed')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                */

                $facilityCount = $facilityCount->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $facilityTwoCount = $facilityTwoCount->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hqCount = $hqCount->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $hospitalCount = $hospitalCount->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_asymptomatic_count = $active_asymptomatic_count->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Asymptomatic')
                ->count();
                
                $active_mild_count = $active_mild_count->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Mild')
                ->count();

                $active_moderate_count = $active_moderate_count->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Moderate')
                ->count();

                $active_severe_count = $active_severe_count->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Severe')
                ->count();
                
                $active_critical_count = $active_critical_count->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('healthStatus', 'Critical')
                ->count();

                $active_agegroup1_count_male = $active_agegroup1_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup1_count_female = $active_agegroup1_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup2_count_male = $active_agegroup2_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup2_count_female = $active_agegroup2_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup3_count_male = $active_agegroup3_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup3_count_female = $active_agegroup3_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup4_count_male = $active_agegroup4_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $active_agegroup4_count_female = $active_agegroup4_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup5_count_male = $active_agegroup5_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup5_count_female = $active_agegroup5_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $active_agegroup6_count_male = $active_agegroup6_count_male->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $active_agegroup6_count_female = $active_agegroup6_count_female->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                foreach($wdgroup as $data) {
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
                        array_push($workdistribution_array, [
                            'title' => !is_null($data) ? $data : 'NON-WORKING',
                            'count' => $count,
                        ]);
                    }
                }

                foreach($vcgroup as $data) {
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
                        array_push($vc_array, [
                            'vaccineName' => $data,
                            'partialCount' => $partialCount,
                            'fullCount' => $fullCount,
                        ]);
                    }
                }

                $totalActiveReinfection = $totalActiveReinfection->where('reinfected', 1)
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                
                $totalRecoveredReinfection = $totalRecoveredReinfection->where('reinfected', 1)
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalDeathReinfection = $totalDeathReinfection->where('reinfected', 1)
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
            }
            
            $totalActive_fullyVaccinated += $totalActive_fullyVaccinated_janssen;

            $totalActive_unvaccinated = $activeCount - $totalActive_partialVaccinated - $totalActive_fullyVaccinated - $totalActive_booster;

            $totalRecovered_fullyVaccinated += $totalRecovered_fullyVaccinated_janssen;

            $totalRecovered_unvaccinated = $recoveredCount - $totalRecovered_partialVaccinated - $totalRecovered_fullyVaccinated - $totalRecovered_booster;

            $totalDeath_fullyVaccinated += $totalDeath_fullyVaccinated_janssen;

            $totalDeath_unvaccinated = $deathCount - $totalDeath_partialVaccinated - $totalDeath_fullyVaccinated - $totalDeath_booster;

            $newActiveCount_fullyVaccinated += $newActiveCount_fullyVaccinated_janssen;

            $lateActiveCount_fullyVaccinated += $lateActiveCount_fullyVaccinated_janssen;

            //$newRecoveredCount += $newRecoveredCount_facility;

            //$newRecoveredCount_partialVaccinated += $newRecoveredCount_partialVaccinated_facility;

            //$newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_facility;

            //$newRecoveredCount_fullyVaccinated_janssen += $newRecoveredCount_fullyVaccinated_janssen_facility;

            $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;

            $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;

            //New Total Cases Counter
            if($toggleFilterReport == 0) {
                $totalCasesCount += $activeCount + $recoveredCount + $deathCount;

                $totalCasesCount_partialVaccinated += $totalActive_partialVaccinated + $totalRecovered_partialVaccinated + $totalDeath_partialVaccinated;

                $totalCasesCount_fullyVaccinated += $totalActive_fullyVaccinated + $totalRecovered_fullyVaccinated + $totalDeath_fullyVaccinated;
            }
            else {
                $totalCasesCount += $activeCount;

                $totalCasesCount_partialVaccinated += $totalActive_partialVaccinated;

                $totalCasesCount_fullyVaccinated += $totalActive_fullyVaccinated;
            }

            //$totalCasesCount_fullyVaccinated += $totalCasesCount_fullyVaccinated_janssen;

            $allCasesCount = Forms::where('isPresentOnSwabDay', 1)->count();

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
                ->where('status', 'approved');

                $brgyActiveCount = Forms::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.address_brgy', $brgy->brgyName);
                })
                ->where('status', 'approved');

                $brgyDeathCount = Forms::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.address_brgy', $brgy->brgyName);
                })
                ->where('status', 'approved');

                $brgyRecoveryCount = 0;

                /*
                $brgyRecoveryCount = Forms::with('records')
                ->whereHas('records', function ($q) use ($brgy) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.address_brgy', $brgy->brgyName);
                })
                ->where('status', 'approved');
                */

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

                if(request()->input('start_date') && request()->input('end_date') || request()->input('filter_type')) {
                    $brgyConfirmedCount = $brgyConfirmedCount->where('caseClassification', 'Confirmed')
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->count();

                    $brgyActiveCount = $brgyActiveCount->where('caseClassification', 'Confirmed')
                    ->where('outcomeCondition', 'Active')
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->count();

                    $brgyDeathCount = $brgyDeathCount->where('outcomeCondition', 'Died')
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->count();

                    //Recovery Count Not Included
                    $brgyRecoveryCount = $brgyConfirmedCount - $brgyDeathCount;

                    $brgySuspectedCount = Forms::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS')
                        ->where('records.address_brgy', $brgy->brgyName);
                    })
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->where('status', 'approved')
                    ->where('caseClassification', 'Suspect')
                    ->where('outcomeCondition', 'Active')
                    ->count();

                    $brgyProbableCount = Forms::with('records')
                    ->whereHas('records', function ($q) use ($brgy) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS')
                        ->where('records.address_brgy', $brgy->brgyName);
                    })
                    ->whereBetween('morbidityMonth', [$sDate, $eDate])
                    ->where('status', 'approved')
                    ->where('caseClassification', 'Probable')
                    ->where('outcomeCondition', 'Active')
                    ->count();
                }
                else {
                    $brgyConfirmedCount = $brgyConfirmedCount->where('caseClassification', 'Confirmed')
                    ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                    ->count();

                    $brgyActiveCount = $brgyActiveCount->where('caseClassification', 'Confirmed')
                    ->where('outcomeCondition', 'Active')
                    ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                    ->count();

                    $brgyDeathCount = $brgyDeathCount->where('outcomeCondition', 'Died')
                    ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                    ->count();

                    $brgyRecoveryCount = $brgyConfirmedCount - $brgyDeathCount;
                    
                    /*
                    $brgyRecoveryCount = $brgyRecoveryCount->where('outcomeCondition', 'Recovered')
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
                }

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
        }
        else {
            if(auth()->user()->isBrgyAccount() && auth()->user()->brgy->displayInList == 1) {
                $toggleFilterReport = 0;
                
                $activeCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $activeCount_male = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $activeCount_female = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
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
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $recoveredCount_male = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $recoveredCount_female = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'FEMALE');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                //Bilangin pati current reinfection sa total ng recovered
                /*
                $recoveredCount += Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();
                */

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

                $deathCount_male = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'MALE');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $deathCount_female = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName)
                    ->where('records.gender', 'FEMALE');
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
                ->count();

                $lateActiveCount_fullyVaccinated += $lateActiveCount_fullyVaccinated_janssen;

                $newRecoveredCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $newRecoveredCount_fullyVaccinated += $newRecoveredCount_fullyVaccinated_janssen;

                $lateRecoveredCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
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
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->count();

                $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;

                /*

                Old Formula

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
                ->count();

                $lateRecoveredCount_fullyVaccinated += $lateRecoveredCount_fullyVaccinated_janssen;
                */

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

                $totalCasesCount = $activeCount + $recoveredCount + $deathCount;

                $totalCasesCount_partialVaccinated = $totalActive_partialVaccinated + $totalRecovered_partialVaccinated + $totalDeath_partialVaccinated;

                $totalCasesCount_fullyVaccinated = $totalActive_fullyVaccinated + $totalRecovered_fullyVaccinated + $totalDeath_fullyVaccinated;
                /*
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
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $totalCasesCount_fullyVaccinated += $totalCasesCount_fullyVaccinated_janssen;
                */

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

                $facilityTwoCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('dispoType', 7)
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
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->count();

                $allCasesCount = Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('isPresentOnSwabDay', 1)->count();

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
                    ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                    ->count();

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
                        'name' => $brgy->brgyName,
                        'confirmed' => $brgyConfirmedCount,
                        'active' => $brgyActiveCount,
                        'deaths' => $brgyDeathCount,
                        'recoveries' => $brgyRecoveryCount,
                        'suspected' => $brgySuspectedCount,
                        'probable' => $brgyProbableCount,
                    ]);
                }
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

        return view('report_select', [
            'activeCount' => $activeCount,
            'activeCount_male' => $activeCount_male,
            'activeCount_female' => $activeCount_female,
            'totalActive_partialVaccinated' => $totalActive_partialVaccinated,
            'totalActive_fullyVaccinated' => $totalActive_fullyVaccinated,
            'totalActive_booster' => $totalActive_booster,
            'totalActive_unvaccinated' => $totalActive_unvaccinated,
            'recoveredCount' => $recoveredCount,
            'recoveredCount_male' => $recoveredCount_male,
            'recoveredCount_female' => $recoveredCount_female,
            'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
            'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
            'totalRecovered_booster' => $totalRecovered_booster,
            'totalRecovered_unvaccinated' => $totalRecovered_unvaccinated,
            'deathCount' => $deathCount,
            'deathCount_male' => $deathCount_male,
            'deathCount_female' => $deathCount_female,
            'totalDeath_partialVaccinated' => $totalDeath_partialVaccinated,
            'totalDeath_fullyVaccinated' => $totalDeath_fullyVaccinated,
            'totalDeath_booster' => $totalDeath_booster,
            'totalDeath_unvaccinated' => $totalDeath_unvaccinated,
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
            'facilityTwoCount' => $facilityTwoCount,
            'hqCount' => $hqCount,
            'hospitalCount' => $hospitalCount,
            'brgylist' => $brgyArray,
            'allCasesCount' => $allCasesCount,
            'toggleFilterReport' => $toggleFilterReport,
            'active_asymptomatic_count' => $active_asymptomatic_count,
            'active_mild_count' => $active_mild_count,
            'active_moderate_count' => $active_moderate_count,
            'active_severe_count' => $active_severe_count,
            'active_critical_count' => $active_critical_count,
            'active_agegroup1_count_male' => $active_agegroup1_count_male,
            'active_agegroup1_count_female' => $active_agegroup1_count_female,
            'active_agegroup2_count_male' => $active_agegroup2_count_male,
            'active_agegroup2_count_female' => $active_agegroup2_count_female,
            'active_agegroup3_count_male' => $active_agegroup3_count_male,
            'active_agegroup3_count_female' => $active_agegroup3_count_female,
            'active_agegroup4_count_male' => $active_agegroup4_count_male,
            'active_agegroup4_count_female' => $active_agegroup4_count_female,
            'active_agegroup5_count_male' => $active_agegroup5_count_male,
            'active_agegroup5_count_female' => $active_agegroup5_count_female,
            'active_agegroup6_count_male' => $active_agegroup6_count_male,
            'active_agegroup6_count_female' => $active_agegroup6_count_female,
            'workdistribution_array' => $workdistribution_array,
            'vc_array' => $vc_array,
            'totalActiveReinfection' => $totalActiveReinfection,
            'totalRecoveredReinfection' => $totalRecoveredReinfection,
            'totalDeathReinfection' => $totalDeathReinfection,
        ]);
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

        return view('report_clustering_view', [
            'clustered_forms' => $clustered_forms,
            'brgy_data' => $brgy_data,
            'city_data' => $city_data,
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

    public function dohExportAll(Request $request) {
        ini_set('max_execution_time', 900);

        if(auth()->user()->canExportReport != 1) {
            return redirect()->route('home')
            ->with('status', 'You are not allowed to do that.')
            ->with('statustype', 'warning');
        }
        
        $year = $request->yearSelected;

        $suspectedQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where(function ($q) {
            $q->where('isPresentOnSwabDay', 0)
            ->orwhereNull('isPresentOnSwabDay');
        })
        ->where('caseClassification', 'Suspect')
        ->where('outcomeCondition', 'Active')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')]);

        $probableQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Probable')
        ->where('outcomeCondition', 'Active')
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')]);

        /*
        if($year && $year == date('Y')) {
            $suspectedQuery = $suspectedQuery->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            });

            $probableQuery = $probableQuery->where(function ($q) {
                $q->whereBetween('testDateCollected1', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
                ->orWhereBetween('testDateCollected2', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')]);
            });
        }
        */

        /*
        $confirmedQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'));
        */

        $confirmedQuery_previous = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where(function ($q) {
            $q->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->orWhereYear('morbidityMonth', date('Y', strtotime('-1 Year')));
        });

        $confirmedQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y'));

        $negativeQuery = Forms::with('records')
        ->where('status', 'approved')
        ->where('caseClassification', 'Non-COVID-19 Case')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

        if(auth()->user()->isCesuAccount()) {
            $suspectedQuery = $suspectedQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });

            $probableQuery = $probableQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });

            $confirmedQuery_previous = $confirmedQuery_previous->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });

            $confirmedQuery = $confirmedQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });

            $negativeQuery = $negativeQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            });
        }
        else if(auth()->user()->isBrgyAccount()) {
            $suspectedQuery = $suspectedQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            });

            $probableQuery = $probableQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            });

            $confirmedQuery_previous = $confirmedQuery_previous->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            });

            $confirmedQuery = $confirmedQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            });

            $negativeQuery = $negativeQuery->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            });
        }

        if($year) {
            $suspectedQuery = $suspectedQuery->whereYear('morbidityMonth', $year)->orderby('morbidityMonth', 'asc');
            $probableQuery = $probableQuery->whereYear('morbidityMonth', $year)->orderby('morbidityMonth', 'asc');
            $confirmedQuery_previous = $confirmedQuery_previous->whereYear('morbidityMonth', $year)->orderby('morbidityMonth', 'asc');
            $confirmedQuery = $confirmedQuery->whereYear('morbidityMonth', $year)->orderby('morbidityMonth', 'asc');
            $negativeQuery = $negativeQuery->whereYear('morbidityMonth', $year)->orderby('morbidityMonth', 'asc');

            if($year == date('Y')) {
                $fName = 'GENTRI_COVID19_DATABASE_'.date('Y_m_d').'.xlsx';
            }
            else {
                $fName = 'GENTRI_COVID19_DATABASE_'.$year.'.xlsx';
            }
        }
        else {
            $suspectedQuery = $suspectedQuery->orderby('morbidityMonth', 'asc');
            $probableQuery = $probableQuery->orderby('morbidityMonth', 'asc');
            $confirmedQuery_previous = $confirmedQuery_previous->orderby('morbidityMonth', 'asc');
            $confirmedQuery = $confirmedQuery->orderby('morbidityMonth', 'asc');
            $negativeQuery = $negativeQuery->orderby('morbidityMonth', 'asc');

            $fName = 'GENTRI_COVID19_DATABASE_'.date('Y_m_d').'.xlsx';
        }
        
        function suspectedGenerator($suspectedQuery) {
            foreach ($suspectedQuery->cursor() as $user) {
                yield $user;
            }
        }

        function probableGenerator($probableQuery) {
            foreach ($probableQuery->cursor() as $user) {
                yield $user;
            }
        }

        function confirmedPreviousGenerator($confirmedPreviousQuery) {
            foreach ($confirmedPreviousQuery->cursor() as $user) {
                yield $user;
            }
        }

        function confirmedGenerator($confirmedQuery) {
            foreach ($confirmedQuery->cursor() as $user) {
                yield $user;
            }
        }

        function negativeGenerator($negativeQuery) {
            foreach ($negativeQuery->cursor() as $user) {
                yield $user;
            }
        }
        
        $sheets = new SheetCollection([
            'Suspected' => suspectedGenerator($suspectedQuery),
            'Probable' => probableGenerator($probableQuery),
            ''.date('Y', strtotime('-2 Years')).'-'.date('Y',strtotime('-1 Year')).' Confirmed' => confirmedPreviousGenerator($confirmedQuery_previous),
            ''.date('Y').' Confirmed' => confirmedGenerator($confirmedQuery),
            'Negative' => negativeGenerator($negativeQuery),
        ]);

        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        return (new FastExcel($sheets))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download($fName, function ($form) {
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
            else if ($form->dispoType == 7) {
                $dispo = 'ADMITTED';
                $dispoName = 'GENERAL TRIAS ISOLATION FACILITY #2';
                $dispoDate = date('m/d/Y', strtotime($form->dispoDate));
            }
            else {
                $dispo = 'UNKNOWN';
                $dispoName = "N/A";
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
                'MM (Morbidity Month)' => mb_strtoupper(date('F', strtotime($form->morbidityMonth))),
                'MW (Morbidity Week)' => Carbon::parse($form->morbidityMonth)->format('W'),
                'DATE REPORTED' => date('m/d/Y', strtotime($form->dateReported)),
                'DRU' => $form->drunit,
                'REGION OF DRU' => $form->drregion,
                'MUNCITY OF DRU' => $form->drprovince,
                'LAST NAME' => $form->records->lname,
                'FIRST NAME' => $form->records->fname,
                'MIDDLE NAME' => (!is_null($form->records->mname)) ? $form->records->mname : "N/A",
                'DOB' => date('m/d/Y', strtotime($form->records->bdate)),
                'AGE (AGE IN YEARS)' => $form->records->getAgeInt(),
                'SEX(M/F)' => substr($form->records->gender,0,1),
                'NATIONALITY' => $form->records->nationality,
                'REGION' => 'IV A',
                'PROVINCE/HUC' => $form->records->address_province,
                'MUNICIPALITY/CITY' => $form->records->address_city,
                'BARANGAY' => $form->records->address_brgy,
                'HOUSE N. AND STREET OR NEAREST LANDMARK' => $form->records->address_houseno.', '.$form->records->address_street,
                'CONTACT N.' => ($form->records->mobile != '09190664324') ? $form->records->mobile : 'N/A',
                'OCCUPATION' => (!is_null($form->records->occupation)) ? $form->records->occupation : "N/A",
                'HEALTHCARE WORKER(Y/N)' => ($form->recods->isHCW == 1) ? 'Y' : 'N',
                'PLACE OF WORK' => ($form->isHealthCareWorker == 1) ? $form->healthCareCompanyLocation : 'N/A',
                'SEVERITY OF THE CASE (ASYMTOMATIC,MILD,MODERATE,SEVERE,CRITICAL)' => $form->healthStatus,
                'PREGNANT (Y/N)' => ($form->records->isPregnant == 1) ? 'Y' : 'N',
                'ONSET OF ILLNESS' => (!is_null($form->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($form->dateOnsetOfIllness)) : 'N/A',
                'FEVER(Y/N)' => (in_array('Fever', $arr_sas)) ? 'Y' : 'N',
                'COUGH (Y/N)' => (in_array('Cough', $arr_sas)) ? 'Y' : 'N',
                'COLDS (Y/N)' => (in_array('COLDS', $arr_othersas) || in_array('COLD', $arr_othersas)) ? 'Y' : 'N',
                'DOB (Y/N)' => (in_array('Dyspnea', $arr_sas) || in_array('DOB', $arr_othersas) || in_array('DIFFICULTY IN BREATHING', $arr_othersas) || in_array('NAHIHIRAPANG HUMINGA', $arr_othersas)) ? 'Y' : 'N',
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
                '1ST DOSE (DATE)' => (!is_null($form->records->vaccinationDate1)) ? date('m/d/Y', strtotime($form->records->vaccinationDate1)) : 'N/A',
                '2ND DOSE (DATE)' => (!is_null($form->records->vaccinationDate2)) ? date('m/d/Y', strtotime($form->records->vaccinationDate2)) : 'N/A',
                'NAME OF FACILITY' => $vFacility,
                'YEAR' => date('Y', strtotime($form->morbidityMonth)),
                '1ST BOOSTER NAME' => (!is_null($form->records->vaccinationDate3)) ? $form->records->vaccinationName3 : 'N/A',
                '1ST BOOSTER DATE' => (!is_null($form->records->vaccinationDate3)) ? $form->records->vaccinationDate3 : 'N/A',
                '2ND BOOSTER NAME' => (!is_null($form->records->vaccinationDate4)) ? $form->records->vaccinationName4 : 'N/A',
                '2ND BOOSTER DATE' => (!is_null($form->records->vaccinationDate4)) ? $form->records->vaccinationDate4 : 'N/A',
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
            /*
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
            */

            $brgySuspectedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
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
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $form->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-7 Days')), date('Y-m-d')])
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
