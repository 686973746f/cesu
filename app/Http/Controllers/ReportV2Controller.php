<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Carbon\CarbonPeriod;

class ReportV2Controller extends Controller
{
    public function viewDashboard() {
        if(request()->input('getOption')) {
            $opt = request()->input('getOption');

            if(auth()->user()->isCesuAccount()) {
                $initial_query = Forms::whereHas('records', function ($q) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS');
                });
            }
            else {
                $initial_query = Forms::whereHas('records', function ($q) {
                    $q->where('records.address_province', auth()->user()->brgy->city->province->provinceName)
                    ->where('records.address_city', auth()->user()->brgy->city->cityName)
                    ->where('records.address_brgy', auth()->user()->brgy->brgyName);
                });
            }
            
            if($opt == 1) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereBetween('dateReported', [date('Y-m-d', strtotime('-2 Days')), date('Y-m-d')])
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->where('reinfected', 0);

                $getListName = 'List of Newly Reported Active Cases';
            }
            else if($opt == 2) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed')
                ->where('reinfected', 0);

                $getListName = 'List of Late Reported Active Cases';
            }
            else if($opt == 3) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
                ->where('reinfected', 0)
                ->where(function ($q) {
                    $q->where(function ($r) {
                        $r->whereBetween('morbidityMonth', [date('Y-m-d', strtotime('-10 Days')), date('Y-m-d')])
                        ->whereDate('outcomeRecovDate', date('Y-m-d'))
                        ->where('dispoType', '!=', 6);
                    })
                    ->orWhere(function ($s) {
                        $s->whereDate('outcomeRecovDate', date('Y-m-d'))
                        ->where('outcomeCondition', 'Recovered')
                        ->where('dispoType', 6);
                    });
                });

                $getListName = 'List of Newly Reported Recovered Cases';
            }
            else if($opt == 4) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('reinfected', 0)
                ->where('dispoType', '!=', 6);

                $getListName = 'List of Late Reported Recovered Cases';
            }
            else if($opt == 5) {
                $opt_final_query = $initial_query
                ->where(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('outcomeDeathDate', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                })->orWhere(function ($q) {
                    $q->where('status', 'approved')
                    ->whereDate('morbidityMonth', date('Y-m-d'))
                    ->where('outcomeCondition', 'Died');
                });

                $getListName = 'List of Newly Reported Death Cases';
            }
            else if($opt == 6) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Total Active Cases';
            }
            else if ($opt == 7) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where(function ($q) {
                    $q->where(function ($r) {
                        $r->where('outcomeCondition', 'Recovered')
                        ->where('reinfected', 0);
                    })
                    ->orWhere(function ($s) {
                        $s->where('reinfected', 1);
                    });
                });

                $getListName = 'List of Total Recoveries';
            }
            else if($opt == 8) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Died')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Total Deaths';
            }
            else if($opt == 9) {
                $opt_final_query = $initial_query
                ->where('dispoType', 6)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients Admitted in General Trias Ligtas COVID-19 Facility #1';
            }
            else if($opt == 10) {
                $opt_final_query = $initial_query
                ->where('dispoType', 3)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients On Strict Home Quarantine';
            }
            else if($opt == 11) {
                $opt_final_query = $initial_query
                ->whereIn('dispoType', [1,2,5])
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->where('reinfected', 0)
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients Admitted in the Hospital/Other Isolation Facility';
            }
            else {
                return abort(401);
            }

            function yielder($q) {
                foreach($q->cursor() as $data) {
                    yield $data;
                }
            }

            $getList = yielder($opt_final_query);

            return view('reportv2_dashboard', [
                'list' => $getList,
                'list_name' => $getListName,
            ]);
        }
        else {
            return abort(401);
        }
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

            $currentCT_query = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->where('outcomeCondition', 'Active')
            ->whereIn('caseClassification', ['Suspect', 'Probable', 'Non-COVID-19 Case'])
            ->where('reinfected', 0);

            $additional_counter = 0;

            if((clone $currentCT_query)->whereNotNull('ccid_list')->count() != 0) {
                foreach((clone $currentCT_query)->whereNotNull('ccid_list')->pluck('ccid_list') as $data) {
                    $additional_counter += count(explode(",", $data));
                }
            }

            array_push($arr_summary, [
                'date' => $date->format('Y-m-d'),
                'numActive' => $currentActiveCount,
                'numCT' => $currentCT_query->count() + $additional_counter,
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
