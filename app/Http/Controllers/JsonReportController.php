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
            ->whereDate('morbidityMonth', '>', date('Y-m-d', strtotime('-14 Days')))
            ->count();

            $brgyProbableCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('isPresentOnSwabDay', 1)
            ->where('caseClassification', 'Probable')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '>', date('Y-m-d', strtotime('-14 Days')))
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
