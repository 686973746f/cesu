<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Carbon\CarbonPeriod;

class ReportV2Controller extends Controller
{
    public function viewDashboard() {
        $load = sys_getloadavg();

        if($load[0] >= 20) {
            return redirect()
            ->route('home')
            ->with('status', 'Server too busy. Please try again after a few minutes.')
            ->with('statustype', 'warning');
        }

        if(auth()->user()->isCesuAccount()) {
            function activeConfirmedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function recoveredGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function deathGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function facilityGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('dispoType', 6)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function hqGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->where('dispoType', 3)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function otherFacilityGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                })
                ->whereIn('dispoType', [1,2,5])
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }

            //Counters
            $activeconfirmed_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count();

            $recovered_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->count();

            $death_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->count();
        }
        else if(auth()->user()->isBrgyAccount()) {
            function activeConfirmedGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function recoveredGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function deathGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function facilityGenerator() {
                foreach (Forms::with('records')
                ->whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                })
                ->where('dispoType', 6)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function hqGenerator() {
                foreach (Forms::with('records')
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
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }
    
            function otherFacilityGenerator() {
                foreach (Forms::with('records')
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
                ->orderby('morbidityMonth', 'asc')->cursor() as $data) {
                    yield $data;
                }
            }

            //Counters
            $activeconfirmed_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count();

            $recovered_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->count();

            $death_count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                ->where('records.address_city', auth()->user()->brgy->city->cityName)
                ->where('records.address_brgy', auth()->user()->brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->count();
        }

        $activeconfirmed = activeConfirmedGenerator();
        $recovered = recoveredGenerator();
        $death = deathGenerator();
        $facility = facilityGenerator();
        $hq = hqGenerator();
        $otherfacility = otherFacilityGenerator();

        return view('reportv2_dashboard', [
            'activeconfirmed_list' => $activeconfirmed,
            'activeconfirmed_count' => $activeconfirmed_count,
            'recovered_list' => $recovered,
            'recovered_count' => $recovered_count,
            'death_list' => $death,
            'death_count' => $death_count,
            'facility_list' => $facility,
            'hq_list' => $hq,
            'otherfacility_list' => $otherfacility,
        ]);
    }

    public function viewCtReport() {
        $arr = [];

        $brgy = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        /*
        $currentCTCount = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->where('outcomeCondition', 'Active')
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->where('reinfected', 0)
            ->count();
        */

        $primaryCount = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 1);

        $secondaryCount = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 2);

        $tertiaryCount = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 3);

        $suspectedCount = Forms::where('status', 'approved')
        ->where('ptype', '!=', 'CLOSE CONTACT')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('caseClassification', 'Suspect');

        $probableCount = Forms::where('status', 'approved')
        ->where('ptype', '!=', 'CLOSE CONTACT')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('caseClassification', 'Probable');

        $grandTotalContactTraced =
        ((clone $primaryCount)->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count() +
        (clone $secondaryCount)->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count() +
        (clone $tertiaryCount)->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count() +
        (clone $suspectedCount)->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count() +
        (clone $probableCount)->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count());

        $activeCasesCount = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        foreach($brgy as $b) {
            $primaryCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('pType', 'CLOSE CONTACT')
            ->where('ccType', 1);

            $secondaryCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('pType', 'CLOSE CONTACT')
            ->where('ccType', 2);;

            $tertiaryCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('pType', 'CLOSE CONTACT')
            ->where('ccType', 3);;

            $suspectedCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('ptype', '!=', 'CLOSE CONTACT')
            ->where('caseClassification', 'Suspect');

            $probableCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('ptype', '!=', 'CLOSE CONTACT')
            ->where('caseClassification', 'Probable');

            if(request()->input('getDate')) {
                $primaryCount = $primaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $secondaryCount = $secondaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $suspectedCount = $suspectedCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $probableCount = $probableCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();
            }
            else {
                $primaryCount = $primaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $secondaryCount = $secondaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $suspectedCount = $suspectedCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $probableCount = $probableCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();
            }

            array_push ($arr, [
                'brgyName' => $b->brgyName,
                'primaryCount' => $primaryCount,
                'secondaryCount' => $secondaryCount,
                'tertiaryCount' => $tertiaryCount,
                'suspectedCount' => $suspectedCount,
                'probableCount' => $probableCount,
            ]);
        }

        $period = CarbonPeriod::create(date('Y-m-01'), date('Y-m-d'));
        $arr_summary = [];

        foreach($period as $date) {
            $currentActiveCount = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')
            ->where('reinfected', 0)
            ->count();

            $currentCTCount = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->where('outcomeCondition', 'Active')
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->where('reinfected', 0)
            ->count();

            array_push($arr_summary, [
                'date' => $date->format('Y-m-d'),
                'numActive' => $currentActiveCount,
                'numCT' => $currentCTCount,
            ]);
        }

        return view('report_ct', [
            'list' => $arr,
            'totalPrimary' => 0,
            'totalSecondary' => 0,
            'totalTertiary' => 0,
            'totalSuspected' => 0,
            'totalProbable' => 0,
            'activeCasesCount' => $activeCasesCount,
            'grandTotalContactTraced' => $grandTotalContactTraced,
            'arr_summary' => $arr_summary,
        ]);
    }
}
