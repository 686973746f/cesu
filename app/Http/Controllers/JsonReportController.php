<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use App\Models\Records;
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
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

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
            ->whereRaw('DATE_ADD(records.vaccinationDate2, INTERVAL 14 DAY) >= CURDATE()')
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
            ->whereRaw('DATE_ADD(records.vaccinationDate1, INTERVAL 14 DAY) >= CURDATE()')
            ->where('records.vaccinationName1', 'JANSSEN');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Recovered')
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        $totalRecovered_fullyVaccinated += $totalRecovered_fullyVaccinated_janssen;

        //Bilangin pati current reinfection sa total ng recovered
        $totalRecovered += Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('reinfected', 1)
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
        ->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
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

        array_push($arr, [
            'totalActiveCases' => $totalActiveCases,
            'totalRecovered' => $totalRecovered,
            'totalRecovered_partialVaccinated' => $totalRecovered_partialVaccinated,
            'totalRecovered_fullyVaccinated' => $totalRecovered_fullyVaccinated,
            'totalDeaths' => $totalDeaths,
            'totalCases' => $totalCasesCount,
            'totalCases_partialVaccinated' => $totalCasesCount_partialVaccinated,
            'totalCases_fullyVaccinated' => $totalCasesCount_fullyVaccinated,
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

    public function weeklyCasesDist() {

    }

    public function currentYearCasesDist() {
        ini_set('max_execution_time', 600);
        
        $arr = [];

        $period = CarbonPeriod::create(date('Y-01-01'), date('Y-m-d')); 




        
        
        foreach($period as $date) {
            $totalActiveCases = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('reinfected', 0)
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();
    
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

            array_push($arr, [
                'fDate' => $date->format('m/d/Y'),
                'activeCount' => $totalActiveCases,
                'recoveredCount' => $totalRecovered,
                'deathCount' => $totalDeaths,
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
            'bracket' => '0 YR - 17 YRS',
            'count' => $ageBracket1,
        ]);
        $arr->push([
            'bracket' => '18 YRS - 25 YRS',
            'count' => $ageBracket2,
        ]);
        $arr->push([
            'bracket' => '26 YRS - 35 YRS',
            'count' => $ageBracket3,
        ]);
        $arr->push([
            'bracket' => '36 YRS - 45 YRS',
            'count' => $ageBracket4,
        ]);
        $arr->push([
            'bracket' => '46 YRS - 59 YRS',
            'count' => $ageBracket5,
        ]);
        $arr->push([
            'bracket' => '60 YRS - UP',
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
}
