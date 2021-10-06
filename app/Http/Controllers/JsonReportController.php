<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class JsonReportController extends Controller
{
    public function totalCases() {
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
        ->count();

        $totalRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
        ->count();

        //Bilangin pati current reinfection sa total ng recovered
        $totalRecovered += Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('reinfected', 1)
        ->count();
        
        $totalDeaths = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Died')
        ->count();

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

        $newRecovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '>=', date('Y-m-d', strtotime('-10 Days')))
        ->whereDate('outcomeRecovDate', date('Y-m-d'))
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
        ->count();

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
        ->count();

        array_push($arr, [
            'totalActiveCases' => $totalActiveCases,
            'totalRecovered' => $totalRecovered,
            'totalDeaths' => $totalDeaths,
            'totalCases' => $totalCasesCount,
            'newActive' => $newActive,
            'lateActive' => $lateActive,
            'newRecovered' => $newRecovered,
            'lateRecovered' => $lateRecovered,
            'newDeaths' => $newDeaths,
        ]);

        return response()->json($arr);
    }

    public function dailyNewCases() {
        
    }

    public function casesDistribution() {
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
            ->count(),
        ]);

        return response()->json($arr);
    }

    public function brgyCases() {
        $brgyArray = collect();

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        $totalConfirmed = 0;
        $totalActive = 0;
        $totalDeaths = 0;
        $totalRecoveries = 0;

        foreach($brgyList as $brgy) {
            $brgyConfirmedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
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
            ->count();

            $brgyDeathCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
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
            ->count();
            
            $totalConfirmed += $brgyConfirmedCount;
            $totalActive += $brgyActiveCount;
            $totalDeaths += $brgyDeathCount;
            $totalRecoveries += $brgyRecoveryCount;

            $brgyArray->push([
                'brgyName' => $brgy->brgyName,
                'numOfConfirmedCases' => $brgyConfirmedCount,
                'numOfActiveCases' => $brgyActiveCount,
                'numOfDeaths' => $brgyDeathCount,
                'numOfRecoveries' => $brgyRecoveryCount,
            ]);
        }

        $brgyArray->push([
            'brgyName' => 'TOTAL',
            'numOfConfirmedCases' => $totalConfirmed,
            'numOfActiveCases' => $totalActive,
            'numOfDeaths' => $totalDeaths,
            'numOfRecoveries' => $totalRecoveries,
        ]);
        
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
            ->count(),
        ]);

        return response()->json($arr);
    }
}
