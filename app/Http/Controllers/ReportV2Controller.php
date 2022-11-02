<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\City;
use App\Models\Forms;
use Carbon\CarbonPeriod;
use App\Models\DailyCases;
use App\Models\ExposureHistory;
use App\Models\SecondaryTertiaryRecords;

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
                ->where('caseClassification', 'Confirmed');

                $getListName = 'List of Newly Reported Active Cases';
            }
            else if($opt == 2) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('dateReported', '<=', date('Y-m-d', strtotime('-3 Days')))
                ->where('outcomeCondition', 'Active')
                ->where('caseClassification', 'Confirmed');

                $getListName = 'List of Late Reported Active Cases';
            }
            else if($opt == 3) {
                /*
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->where('outcomeCondition', 'Recovered')
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
                */
                
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered');

                $getListName = 'List of Newly Reported Recovered Cases';
            }
            else if($opt == 4) {
                /*
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<', date('Y-m-d', strtotime('-10 Days')))
                ->whereDate('outcomeRecovDate', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered')
                ->where('dispoType', '!=', 6);
                */

                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', date('Y-m-d'))
                ->whereDate('outcomeRecovDate', '<', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered');

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
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Total Active Cases';
            }
            else if ($opt == 7) {
                $opt_final_query = $initial_query
                ->where('status', 'approved')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
                ->where('outcomeCondition', 'Recovered');

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

                $getListName = 'List of Patients Currently Admitted in General Trias Ligtas COVID-19 Facility #1 (Gen. Trias Sports Park (Oval), Brgy. Santiago)';
            }
            else if($opt == 10) {
                $opt_final_query = $initial_query
                ->where('dispoType', 3)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients Currently On Strict Home Quarantine';
            }
            else if($opt == 11) {
                $opt_final_query = $initial_query
                ->whereIn('dispoType', [1,2,5])
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients Currently Admitted in the Hospital/Other Isolation Facility';
            }
            else if($opt == 12) {
                $opt_final_query = $initial_query
                ->where('dispoType', 7)
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->where('outcomeCondition', 'Active')
                ->whereDate('morbidityMonth', '<=', date('Y-m-d'));

                $getListName = 'List of Patients Currently Admitted in General Trias Ligtas COVID-19 Facility #2 (Eagle Ridge, Brgy. Javalera)';
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
            $getList_count = $opt_final_query->count();

            return view('reportv2_dashboard', [
                'list' => $getList,
                'list_count' => $getList_count,
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

        /*
        $ct_primary_count = ExposureHistory::where('is_primarycc', 1)
        ->where('is_primarycc_date_set', [date('Y-m-01 00:00:00'), date('Y-m-d 13:00:00')])
        ->count();
        */

        $secondaryCount = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 2);

        /*
        $ct_secondary_count = ExposureHistory::where('is_secondarycc', 1)
        ->where('is_secondarycc_date_set', [date('Y-m-01 00:00:00'), date('Y-m-d 13:00:00')])
        ->count();
        */

        $secondarycc_count = SecondaryTertiaryRecords::where('is_secondarycc', 1)
        ->whereBetween('is_secondarycc_date_set', [date('Y-m-01 00:00:00'), date('Y-m-d 13:00:00')])
        ->count();

        $tertiaryCount = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('reinfected', 0)
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 3);

        /*
        $ct_tertiary_count = ExposureHistory::where('is_tertiarycc', 1)
        ->whereBetween('is_tertiarycc_date_set', [date('Y-m-01 00:00:00'), date('Y-m-d 13:00:00')])
        ->count();
        */

        $tertiarycc_count = SecondaryTertiaryRecords::where('is_tertiarycc', 1)
        ->whereBetween('is_tertiarycc_date', [date('Y-m-01 00:00:00'), date('Y-m-d 13:00:00')])
        ->count();

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

        $exposure_history_count = ExposureHistory::whereDate('set_date', date('Y-m-d'))
        ->count();

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
        })->whereBetween('morbidityMonth', [date('Y-m-01'), date('Y-m-d')])->count()) +
        $secondarycc_count + 
        $tertiarycc_count + 
        $exposure_history_count;

        if(date('H') >= 13) {
            //idagdag ang bilang ng confirmed cases yesterday
            $total_active_yesterday_1pm = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '1PM')
            ->pluck('total_active')
            ->first();

            $total_active_yesterday_4pm = DailyCases::whereDate('set_date', date('Y-m-d', strtotime('-1 Day')))
            ->where('type', '4PM')
            ->pluck('total_active')
            ->first();

            $today_count_query = DailyCases::whereDate('set_date', date('Y-m-d'))
            ->where('type', '1PM')
            ->pluck('total_active')
            ->first();

            $add_yesterday = $total_active_yesterday_4pm - $total_active_yesterday_1pm;
            $activeCasesCount = $today_count_query + $add_yesterday;
        }
        else {
            $activeCasesCount = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();
        }

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
            ->where('ccType', 2);

            $st_secondary_count = SecondaryTertiaryRecords::where('address_province', $b->city->province->provinceName)
            ->where('address_city', $b->city->cityName)
            ->where('address_brgy', $b->brgyName)
            ->where('is_secondarycc', 1);

            $st_secondary_count_yesterday = SecondaryTertiaryRecords::where('address_province', $b->city->province->provinceName)
            ->where('address_city', $b->city->cityName)
            ->where('address_brgy', $b->brgyName)
            ->where('is_secondarycc', 1);

            $tertiaryCount = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('address_province', $b->city->province->provinceName)
                ->where('address_city', $b->city->cityName)
                ->where('address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('pType', 'CLOSE CONTACT')
            ->where('ccType', 3);

            $st_tertiary_count = SecondaryTertiaryRecords::where('address_province', $b->city->province->provinceName)
            ->where('address_city', $b->city->cityName)
            ->where('address_brgy', $b->brgyName)
            ->where('is_tertiarycc', 1);

            $st_tertiary_count_yesterday = SecondaryTertiaryRecords::where('address_province', $b->city->province->provinceName)
            ->where('address_city', $b->city->cityName)
            ->where('address_brgy', $b->brgyName)
            ->where('is_tertiarycc', 1);

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

            /*
            $ct_primary_count = ExposureHistory::whereHas('form', function ($q) use ($b) {
                $q->whereHas('records', function ($r) use ($b) {
                    $r->where('address_province', $b->city->province->provinceName)
                    ->where('address_city', $b->city->cityName)
                    ->where('address_brgy', $b->brgyName);
                });
            })
            ->where('is_primarycc', 1);

            $ct_secondary_count = ExposureHistory::whereHas('form', function ($q) use ($b) {
                $q->whereHas('records', function ($r) use ($b) {
                    $r->where('address_province', $b->city->province->provinceName)
                    ->where('address_city', $b->city->cityName)
                    ->where('address_brgy', $b->brgyName);
                });
            })
            ->where('is_secondarycc', 1);

            $ct_tertiary_count = ExposureHistory::whereHas('form', function ($q) use ($b) {
                $q->whereHas('records', function ($r) use ($b) {
                    $r->where('address_province', $b->city->province->provinceName)
                    ->where('address_city', $b->city->cityName)
                    ->where('address_brgy', $b->brgyName);
                });
            })
            ->where('is_tertiarycc', 1);
            */

            if(request()->input('getDate')) {
                $primaryCount = $primaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $secondaryCount = $secondaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $st_secondary_count = $st_secondary_count->whereBetween('is_secondarycc_date_set', [date('Y-m-d 00:00:00', strtotime(request()->input('getDate'))), date('Y-m-d 13:00:00', strtotime(request()->input('getDate')))]) //13:00 is cutoff as per CT
                ->count();

                $st_secondary_count_yesterday = $st_secondary_count_yesterday->whereBetween('is_secondarycc_date_set', [date('Y-m-d 13:00:00', strtotime(request()->input('getDate').' -1 Day')), date('Y-m-d 23:59:59', strtotime(request()->input('getDate').' -1 Day'))])
                ->count();

                $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $st_tertiary_count = $st_tertiary_count->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 00:00:00', strtotime(request()->input('getDate'))), date('Y-m-d 13:00:00', strtotime(request()->input('getDate')))]) //13:00 is cutoff as per CT
                ->count();

                $st_tertiary_count_yesterday = $st_tertiary_count_yesterday->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 13:00:00', strtotime(request()->input('getDate').' -1 Day')), date('Y-m-d 23:59:59', strtotime(request()->input('getDate').' -1 Day'))])
                ->count();

                $suspectedCount = $suspectedCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                $probableCount = $probableCount->whereDate('morbidityMonth', request()->input('getDate'))
                ->count();

                /*
                $ct_primary_count = $ct_primary_count->whereDate('is_primarycc_date_set', request()->input('getDate'))
                ->count();

                $ct_secondary_count = $ct_primary_count->whereDate('is_secondarycc_date_set', request()->input('getDate'))
                ->count();

                $ct_tertiary_count = $ct_primary_count->whereDate('is_tertiarycc_date_set', request()->input('getDate'))
                ->count();
                */
            }
            else {
                $primaryCount = $primaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $secondaryCount = $secondaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();
                
                $st_secondary_count = $st_secondary_count->whereBetween('is_secondarycc_date_set', [date('Y-m-d 00:00:00'), date('Y-m-d 13:00:00')]) //13:00 is cutoff as per CT
                ->count();

                $st_secondary_count_yesterday = $st_secondary_count_yesterday->whereBetween('is_secondarycc_date_set', [date('Y-m-d 13:00:00', strtotime('yesterday')), date('Y-m-d 23:59:59')])
                ->count();

                $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $st_tertiary_count = $st_tertiary_count->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 00:00:00'), date('Y-m-d 13:00:00')]) //13:00 is cutoff as per CT
                ->count();

                $st_tertiary_count_yesterday = $st_tertiary_count_yesterday->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 13:00:00', strtotime('yesterday')), date('Y-m-d 23:59:59')])
                ->count();

                $suspectedCount = $suspectedCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                $probableCount = $probableCount->whereDate('morbidityMonth', date('Y-m-d'))
                ->count();

                /*
                $ct_primary_count = $ct_primary_count->whereDate('is_primarycc_date_set', date('Y-m-d'))
                ->count();

                $ct_secondary_count = $ct_secondary_count->whereDate('is_secondarycc_date_set', date('Y-m-d'))
                ->count();

                $ct_tertiary_count = $ct_tertiary_count->whereDate('is_tertiarycc_date_set', date('Y-m-d'))
                ->count();
                */
            }

            array_push ($arr, [
                'brgyName' => $b->brgyName,
                'primaryCount' => $primaryCount,
                'secondaryCount' => $secondaryCount + $st_secondary_count + $st_secondary_count_yesterday,
                'tertiaryCount' => $tertiaryCount + $st_tertiary_count + $st_tertiary_count_yesterday,
                'suspectedCount' => $suspectedCount,
                'probableCount' => $probableCount,
            ]);
        }

        //Array Push Other Cities
        $primaryCount = Forms::where(function ($q) {
            $q->whereHas('records', function ($r) {
                $r->where('address_province', '!=', 'CAVITE');
            })
            ->orWhereHas('records', function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 1);

        $secondaryCount = Forms::where(function ($q) {
            $q->whereHas('records', function ($r) {
                $r->where('address_province', '!=', 'CAVITE');
            })
            ->orWhereHas('records', function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 2);

        $st_secondary_count = SecondaryTertiaryRecords::where(function ($q) {
            $q->where('address_province', '!=', 'CAVITE')
            ->orWhere(function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('is_secondarycc', 1);

        $st_secondary_count_yesterday = SecondaryTertiaryRecords::where(function ($q) {
            $q->where('address_province', '!=', 'CAVITE')
            ->orWhere(function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('is_secondarycc', 1);

        $tertiaryCount = Forms::where(function ($q) {
            $q->whereHas('records', function ($r) {
                $r->where('address_province', '!=', 'CAVITE');
            })
            ->orWhereHas('records', function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('ccType', 3);

        $st_tertiary_count = SecondaryTertiaryRecords::where(function ($q) {
            $q->where('address_province', '!=', 'CAVITE')
            ->orWhere(function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('is_tertiarycc', 1);

        $st_tertiary_count_yesterday = SecondaryTertiaryRecords::where(function ($q) {
            $q->where('address_province', '!=', 'CAVITE')
            ->orWhere(function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('is_tertiarycc', 1);

        $suspectedCount = Forms::where(function ($q) {
            $q->whereHas('records', function ($r) {
                $r->where('address_province', '!=', 'CAVITE');
            })
            ->orWhereHas('records', function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('status', 'approved')
        ->where('ptype', '!=', 'CLOSE CONTACT')
        ->where('caseClassification', 'Suspect');

        $probableCount = Forms::where(function ($q) {
            $q->whereHas('records', function ($r) {
                $r->where('address_province', '!=', 'CAVITE');
            })
            ->orWhereHas('records', function ($r) {
                $r->where('address_province', 'CAVITE')
                ->where('address_city', '!=', 'GENERAL TRIAS');
            });
        })
        ->where('status', 'approved')
        ->where('ptype', '!=', 'CLOSE CONTACT')
        ->where('caseClassification', 'Probable');

        if(request()->input('getDate')) {
            $primaryCount = $primaryCount->whereDate('morbidityMonth', request()->input('getDate'))
            ->count();

            $secondaryCount = $secondaryCount->whereDate('morbidityMonth', request()->input('getDate'))
            ->count();

            $st_secondary_count = $st_secondary_count->whereBetween('is_secondarycc_date_set', [date('Y-m-d 00:00:00', strtotime(request()->input('getDate'))), date('Y-m-d 13:00:00', strtotime(request()->input('getDate')))]) //13:00 is cutoff as per CT
            ->count();

            $st_secondary_count_yesterday = $st_secondary_count_yesterday->whereBetween('is_secondarycc_date_set', [date('Y-m-d 13:00:00', strtotime(request()->input('getDate').' -1 Day')), date('Y-m-d 23:59:59', strtotime(request()->input('getDate').' -1 Day'))])
            ->count();

            $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', request()->input('getDate'))
            ->count();

            $st_tertiary_count = $st_tertiary_count->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 00:00:00', strtotime(request()->input('getDate'))), date('Y-m-d 13:00:00', strtotime(request()->input('getDate')))]) //13:00 is cutoff as per CT
            ->count();

            $st_tertiary_count_yesterday = $st_tertiary_count_yesterday->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 13:00:00', strtotime(request()->input('getDate').' -1 Day')), date('Y-m-d 23:59:59', strtotime(request()->input('getDate').' -1 Day'))])
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
            
            $st_secondary_count = $st_secondary_count->whereBetween('is_secondarycc_date_set', [date('Y-m-d 00:00:00'), date('Y-m-d 13:00:00')]) //13:00 is cutoff as per CT
            ->count();

            $st_secondary_count_yesterday = $st_secondary_count_yesterday->whereBetween('is_secondarycc_date_set', [date('Y-m-d 13:00:00', strtotime('yesterday')), date('Y-m-d 23:59:59')])
            ->count();

            $tertiaryCount = $tertiaryCount->whereDate('morbidityMonth', date('Y-m-d'))
            ->count();

            $st_tertiary_count = $st_tertiary_count->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 00:00:00'), date('Y-m-d 13:00:00')]) //13:00 is cutoff as per CT
            ->count();

            $st_tertiary_count_yesterday = $st_tertiary_count_yesterday->whereBetween('is_tertiarycc_date_set', [date('Y-m-d 13:00:00', strtotime('yesterday')), date('Y-m-d 23:59:59')])
            ->count();

            $suspectedCount = $suspectedCount->whereDate('morbidityMonth', date('Y-m-d'))
            ->count();

            $probableCount = $probableCount->whereDate('morbidityMonth', date('Y-m-d'))
            ->count();
        }

        array_push ($arr, [
            'brgyName' => 'OTHER CITIES',
            'primaryCount' => $primaryCount,
            'secondaryCount' => $secondaryCount + $st_secondary_count + $st_secondary_count_yesterday,
            'tertiaryCount' => $tertiaryCount + $st_tertiary_count + $st_tertiary_count_yesterday,
            'suspectedCount' => $suspectedCount,
            'probableCount' => $probableCount,
        ]);

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

            $st_count = SecondaryTertiaryRecords::where('morbidityMonth', $date->format('Y-m-d'))
            ->count();

            $additional_counter += $st_count;

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

    public function cmIndex() {
        if(date('d') <= 15) {
            $sdate = date('Y-m-01');
            $edate = date('Y-m-15');
        }
        else if(date('d') >= 16) {
            $sdate = date('Y-m-16');
            $edate = date('Y-m-t');
        }

        $lastsevendays = date('Y-m-d', strtotime('-7 Days'));

        $cc_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->count();

        $cc_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->where('dispoType', 3)
        ->count();

        $cc_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $cc_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', 'CLOSE CONTACT')
        ->where('dispoType', 1)
        ->count();

        $probable_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Probable')
        ->count();

        $probable_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Probable')
        ->where('dispoType', 3)
        ->count();

        $probable_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Probable')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $probable_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Probable')
        ->where('dispoType', 1)
        ->count();

        $suspected_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Suspect')
        ->count();

        $suspected_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Suspect')
        ->where('dispoType', 3)
        ->count();

        $suspected_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Suspect')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $suspected_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereBetween('morbidityMonth', [$lastsevendays, $edate])
        ->where('pType', '!=','CLOSE CONTACT')
        ->where('caseClassification', 'Suspect')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->count();

        $activecases_count_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_asymptomatic_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->count();
        
        $activecases_count_asymptomatic_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_asymptomatic_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_asymptomatic_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Asymptomatic')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_mild_nocomorbid_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->count();

        $activecases_count_mild_nocomorbid_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_mild_nocomorbid_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_mild_nocomorbid_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', 'None')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_mild_withcomorbid_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->count();

        $activecases_count_mild_withcomorbid_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_mild_withcomorbid_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_mild_withcomorbid_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Mild')
        ->where('COMO', '!=', 'None')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_moderate_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->count();

        $activecases_count_moderate_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_moderate_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_moderate_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Moderate')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_severe_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->count();

        $activecases_count_severe_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_severe_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_severe_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Severe')
        ->where('dispoType', 1)
        ->count();

        $activecases_count_critical_total = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->count();

        $activecases_count_critical_hq = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->where('dispoType', 3)
        ->count();

        $activecases_count_critical_ttmf = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->whereIn('dispoType', [6,7,2,5])
        ->count();

        $activecases_count_critical_hospital = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('healthStatus', 'Critical')
        ->where('dispoType', 1)
        ->count();

        return view('cm_index', [
            'cc_count_total' => $cc_count_total,
            'cc_count_hq' => $cc_count_hq,
            'cc_count_ttmf' => $cc_count_ttmf,
            'cc_count_hospital' => $cc_count_hospital,
            'probable_count_total' => $probable_count_total,
            'probable_count_hq' => $probable_count_hq,
            'probable_count_ttmf' => $probable_count_ttmf,
            'probable_count_hospital' => $probable_count_hospital,
            'suspected_count_total' => $suspected_count_total,
            'suspected_count_hq' => $suspected_count_hq,
            'suspected_count_ttmf' => $suspected_count_ttmf,
            'suspected_count_hospital' => $suspected_count_hospital,
            'activecases_count_total' => $activecases_count_total,
            'activecases_count_hq' => $activecases_count_hq,
            'activecases_count_ttmf' => $activecases_count_ttmf,
            'activecases_count_hospital' => $activecases_count_hospital,
            'activecases_count_asymptomatic_total' => $activecases_count_asymptomatic_total,
            'activecases_count_asymptomatic_hq' => $activecases_count_asymptomatic_hq,
            'activecases_count_asymptomatic_ttmf' => $activecases_count_asymptomatic_ttmf,
            'activecases_count_asymptomatic_hospital' => $activecases_count_asymptomatic_hospital,
            'activecases_count_mild_nocomorbid_total' => $activecases_count_mild_nocomorbid_total,
            'activecases_count_mild_nocomorbid_hq' => $activecases_count_mild_nocomorbid_hq,
            'activecases_count_mild_nocomorbid_ttmf' => $activecases_count_mild_nocomorbid_ttmf,
            'activecases_count_mild_nocomorbid_hospital' => $activecases_count_mild_nocomorbid_hospital,
            'activecases_count_mild_withcomorbid_total' => $activecases_count_mild_withcomorbid_total,
            'activecases_count_mild_withcomorbid_hq' => $activecases_count_mild_withcomorbid_hq,
            'activecases_count_mild_withcomorbid_ttmf' => $activecases_count_mild_withcomorbid_ttmf,
            'activecases_count_mild_withcomorbid_hospital' => $activecases_count_mild_withcomorbid_hospital,
            'activecases_count_moderate_total' => $activecases_count_moderate_total,
            'activecases_count_moderate_hq' => $activecases_count_moderate_hq,
            'activecases_count_moderate_ttmf' => $activecases_count_moderate_ttmf,
            'activecases_count_moderate_hospital' => $activecases_count_moderate_hospital,
            'activecases_count_severe_total' => $activecases_count_severe_total,
            'activecases_count_severe_hq' => $activecases_count_severe_hq,
            'activecases_count_severe_ttmf' => $activecases_count_severe_ttmf,
            'activecases_count_severe_hospital' => $activecases_count_severe_hospital,
            'activecases_count_critical_total' => $activecases_count_critical_total,
            'activecases_count_critical_hq' => $activecases_count_critical_hq,
            'activecases_count_critical_ttmf' => $activecases_count_critical_ttmf,
            'activecases_count_critical_hospital' => $activecases_count_critical_hospital,
        ]);
    }

    public function clustering_index() {
        $arr = collect();

        //get Brgy List
        $brgy = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        foreach($brgy as $b) {
            $active_count = Forms::whereHas('records', function ($q) use ($b) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $b->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
            ->count();

            $arr->push([
                'id' => $b->id,
                'city_id' => $b->city_id,
                'brgyName' => $b->brgyName,
                'active_count' => $active_count,
            ]);
        }

        return view('report_clustering_index', [
            'list' => $arr,
        ]);
    }

    public function clustering_viewlist($city_id, $brgy_id, $subd) {
        $city_data = City::findOrFail($city_id);
        $brgy_data = Brgy::findOrFail($brgy_id);

        $list = Forms::whereHas('records', function ($q) use ($city_data, $brgy_data, $subd) {
            $q->where('records.address_city', $city_data->cityName)
            ->where('records.address_brgy', $brgy_data->brgyName)
            ->where('records.address_street', $subd);
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->get();

        return view('report_clustering_view_patientlist', [
            'subd' => $subd,
            'city_data' => $city_data,
            'brgy_data' => $brgy_data,
            'list' => $list,
        ]);
    }

    public function encodingCalendar() {
        ini_set('max_execution_time', 600);
        $brgy = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();

        $period = CarbonPeriod::create(date('Y-m-01'), date('Y-m-d'));

        /*
        $arr = [];
        foreach($brgy as $b) {
            foreach($period as $d) {
                $sus_count = Forms::with('records')
                ->whereHas('records', function ($q) use ($d) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.address_brgy', $d->brgyName);
                })
                ->whereDate('morbidityMonth', $d->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('caseClassification', 'Suspect')
                ->where('outcomeCondition', 'Active')
                ->count();

                $pro_count = Forms::with('records')
                ->whereHas('records', function ($q) use ($d) {
                    $q->where('records.address_province', 'CAVITE')
                    ->where('records.address_city', 'GENERAL TRIAS')
                    ->where('records.address_brgy', $d->brgyName);
                })
                ->whereDate('morbidityMonth', $d->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('caseClassification', 'Probable')
                ->where('outcomeCondition', 'Active')
                ->count();

                array_push($arr, [
                    'brgy' => $b->brgyName,
                    'forDate' => $d->format('Y-m-d'),
                    'sus_count' => $sus_count,
                    'pro_count' => $pro_count,
                ]);
            }
        }
        */

        return view('encodingcalendar', [
            'brgy' => $brgy,
            'period' => $period,
        ]);
    }

    public function accomplishment_index() {
        $current_month = date('n');
        if($current_month >= 1 && $current_month <= 3) {
            $qstr = '1st Quarter Active Cases (January - March '.date('Y').')';
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-t');
        }
        else if ($current_month >= 4 && $current_month <= 6) {
            $qstr = '2nd Quarter Active Cases (April - June '.date('Y').')';
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-t');
        }
        else if ($current_month >= 7 && $current_month <= 9) {
            $qstr = '3rd Quarter Active Cases (July - September '.date('Y').')';
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-t');
        }
        else if ($current_month >= 10 && $current_month <= 12) {
            $qstr = '4th Quarter Active Cases (October - December '.date('Y').')';
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-t');
        }

        //Current Quarter Active Cases
        $currq_active = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('morbidityMonth', '>=', $start_date)
        ->where('morbidityMonth', '<=', $end_date)
        ->count();

        //Previous Year Active Cases
        $count1 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $count2 = $count1/365;

        $count3 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $count4 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $count5 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Suspect')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $count6 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Probable')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $count7 = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $brgyArray = collect();
        //$brgyArray1 = collect();
        //$brgyArray2 = collect();

        $brgyList = Brgy::where('displayInList', 1)
        ->where('city_id', 1)
        ->orderBy('brgyName', 'asc')
        ->get();

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        foreach($brgyList as $brgy) {
            $brgyConfirmedCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', $year)
            ->count();

            $brgyConfirmedCount_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName)
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', $year)
            ->count();

            $brgyConfirmedCount_female = $brgyConfirmedCount - $brgyConfirmedCount_male;

            $brgyDeathCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', $year)
            ->count();

            $brgyDeathCount_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName)
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', $year)
            ->count();

            $brgyDeathCount_female = $brgyDeathCount - $brgyDeathCount_male;

            $brgyRecoveryCount = $brgyConfirmedCount - $brgyDeathCount;

            $brgyRecoveryCount_female = $brgyRecoveryCount - $brgyConfirmedCount_male - $brgyDeathCount_male;
            
            $brgyRecoveryCount_male = $brgyRecoveryCount - $brgyRecoveryCount_female;

            /*
            $brgyRecoveryCount = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', $year)
            ->count();
            */

            /*
            $brgyConfirmedCount1 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $brgyDeathCount1 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $brgyRecoveryCount1 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();
            */

            /*
            $brgyConfirmedCount2 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y'))
            ->count();

            $brgyDeathCount2 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y'))
            ->count();

            $brgyRecoveryCount2 = Forms::with('records')
            ->whereHas('records', function ($q) use ($brgy) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->where('records.address_brgy', $brgy->brgyName);
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y'))
            ->count();
            */

            $brgyArray->push([
                'name' => $brgy->brgyName,
                'confirmed' => $brgyConfirmedCount,
                'confirmed_male' => $brgyConfirmedCount_male,
                'confirmed_female' => $brgyConfirmedCount_female,
                'deaths' => $brgyDeathCount,
                'deaths_male' => $brgyDeathCount_male,
                'deaths_female' => $brgyDeathCount_female,
                'recoveries' => $brgyRecoveryCount,
                'recoveries_male' => $brgyRecoveryCount_male,
                'recoveries_female' => $brgyRecoveryCount_female,
            ]);

            /*
            $brgyArray1->push([
                'name' => $brgy->brgyName,
                'confirmed' => $brgyConfirmedCount1,
                'deaths' => $brgyDeathCount1,
                'recoveries' => $brgyRecoveryCount1,
            ]);
            */

            /*
            $brgyArray2->push([
                'name' => $brgy->brgyName,
                'confirmed' => $brgyConfirmedCount2,
                'deaths' => $brgyDeathCount2,
                'recoveries' => $brgyRecoveryCount2,
            ]);
            */
        }

        $malecount = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('drunit', 'CHO GENERAL TRIAS')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $femalecount = $count1 - $malecount;

        $swabarr = [];

        for($i = 1; $i<=date('n');$i++) {
            if($i == 1) {
                $m = 'January';
            }
            else if($i == 2) {
                $m = 'February';
            }
            else if($i == 3) {
                $m = 'March';
            }
            else if($i == 4) {
                $m = 'April';
            }
            else if($i == 5) {
                $m = 'May';
            }
            else if($i == 6) {
                $m = 'June';
            }
            else if($i == 7) {
                $m = 'July';
            }
            else if($i == 8) {
                $m = 'August';
            }
            else if($i == 9) {
                $m = 'September';
            }
            else if($i == 10) {
                $m = 'October';
            }
            else if($i == 11) {
                $m = 'November';
            }
            else if($i == 12) {
                $m = 'December';
            }

            $count = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('drunit', 'CHO GENERAL TRIAS')
            ->whereYear('morbidityMonth', date('Y'))
            ->whereMonth('morbidityMonth', $i)
            ->where('isPresentOnSwabDay', 1)
            ->count();

            $suspro = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereYear('morbidityMonth', date('Y'))
            ->whereMonth('morbidityMonth', $i)
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->where('outcomeCondition', 'Active')
            ->count();

            $confirmed = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereYear('morbidityMonth', date('Y'))
            ->whereMonth('morbidityMonth', $i)
            ->where('caseClassification', 'Confirmed')
            ->count();

            $cc = Forms::whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->whereYear('morbidityMonth', date('Y'))
            ->whereMonth('morbidityMonth', $i)
            ->where('pType', 'CLOSE CONTACT')
            ->count();

            array_push($swabarr, [
                'month' => $m,
                'count' => $count,
                'suspro' => $suspro,
                'confirmed' => $confirmed,
                'cc' => $cc,
            ]);
        }

        array_push($swabarr, [
            'month' => 'DECEMBER 2021',
            'count' => Forms::whereHas('records', function ($q) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('status', 'approved')
                    ->whereBetween('morbidityMonth', ['2021-12-01', '2021-12-31'])
                    ->where('isPresentOnSwabDay', 1)
                    ->count(),
            'suspro' => 0,
            'confirmed' => 0,
            'cc' => 0,
        ]);

        array_push($swabarr, [
            'month' => 'DECEMBER 2020',
            'count' => Forms::whereHas('records', function ($q) {
                        $q->where('records.address_province', 'CAVITE')
                        ->where('records.address_city', 'GENERAL TRIAS');
                    })
                    ->where('status', 'approved')
                    ->whereBetween('morbidityMonth', ['2020-12-01', '2020-12-31'])
                    ->where('isPresentOnSwabDay', 1)
                    ->count(),
            'suspro' => 0,
            'confirmed' => 0,
            'cc' => 0,
        ]);

        $lastYearSwab = Forms::whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('drunit', 'CHO GENERAL TRIAS')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->where('isPresentOnSwabDay', 1)
        ->count();

        //Last Year Hospitalization
        $lastyear_hospitalized = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $lastyear_hospitalized_recovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();
        
        $lastyear_hospitalized_died = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $lastyear_hospitalized_partialvacc = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $lastyear_hospitalized_fullvacc = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $lastyear_hospitalized_fullvacc_janssen = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $lastyear_hospitalized_fullvacc += $lastyear_hospitalized_fullvacc_janssen;

        $lastyear_hospitalized_boostered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate3')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        //Current Year Hospitalization
        $cy_hospitalized = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        $cy_hospitalized_recovered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y'))
        ->count();
        
        $cy_hospitalized_died = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        $cy_hospitalized_partialvacc = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        $cy_hospitalized_fullvacc = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        $cy_hospitalized_fullvacc_janssen = Forms::with('records')
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
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        $cy_hospitalized_fullvacc += $cy_hospitalized_fullvacc_janssen;

        $cy_hospitalized_boostered = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereNotNull('records.vaccinationDate3')
            ->whereRaw('DATE(DATE_ADD(records.vaccinationDate3, INTERVAL 14 DAY)) <= CURDATE()');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('dispoType', 1)
        ->whereYear('morbidityMonth', date('Y'))
        ->count();

        //Age Group Last 2 Years

        //Under 1 (2 Years Previous)
        $aga_1 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_1_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_1_female = $aga_1 - $aga_1_male;

        $aga_2 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_2_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_2_female = $aga_2 - $aga_2_male;
        
        $aga_3 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_3_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_3_female = $aga_3 - $aga_3_male;

        //Under 1 (1 Years Previous)
        $aga_4 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_4_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_4_female = $aga_4 - $aga_4_male;

        $aga_5 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_5_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_5_female = $aga_5 - $aga_5_male;
        
        $aga_6 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_6_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) < 1')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_6_female = $aga_6 - $aga_6_male;

        //END

        //Age Group 1-4
        $age_array = [];

        array_push($age_array, [
            'name' => 'Under 1',
            'c_l2y' => $aga_1,
            'c_l2y_male' => $aga_1_male,
            'c_l2y_female' => $aga_1_female,
            'd_l2y' => $aga_2,
            'd_l2y_male' => $aga_2_male,
            'd_l2y_female' => $aga_2_female,
            'r_l2y' => $aga_3,
            'r_l2y_male' => $aga_3_male,
            'r_l2y_female' => $aga_3_female,
            'c_l1y' => $aga_4,
            'c_l1y_male' => $aga_4_male,
            'c_l1y_female' => $aga_4_female,
            'd_l1y' => $aga_5,
            'd_l1y_male' => $aga_5_male,
            'd_l1y_female' => $aga_5_female,
            'r_l1y' => $aga_6,
            'r_l1y_male' => $aga_6_male,
            'r_l1y_female' => $aga_6_female,
        ]);

        $g1 = 1;
        $g2 = 4;
        
        for($it=1;$it <= 16;$it++) {
            //Under 1 (2 Years Previous)
            $cl1 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $cl1_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $cl1_female = $cl1 - $cl1_male;

            $cl2 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $cl2_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();
            
            $cl2_female = $cl2 - $cl2_male;
            
            $cl3 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $cl3_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
            ->count();

            $cl3_female = $cl3 - $cl3_male;

            //Under 1 (1 Years Previous)
            $cl4 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl4_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl4_female = $cl4 - $cl4_male;

            $cl5 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl5_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Died')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl5_female = $cl5 - $cl5_male;
            
            $cl6 = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2");
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl6_male = Forms::with('records')
            ->whereHas('records', function ($q) use ($g1, $g2) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS')
                ->whereRaw("TIMESTAMPDIFF(YEAR, bdate, CURDATE()) BETWEEN $g1 and $g2")
                ->where('records.gender', 'MALE');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
            ->count();

            $cl6_female = $cl6 - $cl6_male;

            //END

            array_push($age_array, [
                'name' => $g1.' - '.$g2,
                'c_l2y' => $cl1,
                'c_l2y_male' => $cl1_male,
                'c_l2y_female' => $cl1_female,
                'd_l2y' => $cl2,
                'd_l2y_male' => $cl2_male,
                'd_l2y_female' => $cl2_female,
                'r_l2y' => $cl3,
                'r_l2y_male' => $cl3_male,
                'r_l2y_female' => $cl3_female,
                'c_l1y' => $cl4,
                'c_l1y_male' => $cl4_male,
                'c_l1y_female' => $cl4_female,
                'd_l1y' => $cl5,
                'd_l1y_male' => $cl5_male,
                'd_l1y_female' => $cl5_female,
                'r_l1y' => $cl6,
                'r_l1y_male' => $cl6_male,
                'r_l1y_female' => $cl6_female,
            ]);

            $g1 = $g2 + 1;
            $g2 = $g2 + 5;
        }

        //Above 80 Years

        $aga_1 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_1_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_1_female = $aga_1 - $aga_1_male;

        $aga_2 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_2_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_2_female = $aga_2 - $aga_2_male;
        
        $aga_3 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_3_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-2 Years')))
        ->count();

        $aga_3_female = $aga_3 - $aga_3_male;

        //Under 1 (1 Years Previous)
        $aga_4 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_4_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_4_female = $aga_4 - $aga_4_male;

        $aga_5 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_5_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Died')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_5_female = $aga_5 - $aga_5_male;
        
        $aga_6 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_6_male = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS')
            ->whereRaw('TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 80')
            ->where('records.gender', 'MALE');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Recovered')
        ->whereYear('morbidityMonth', date('Y', strtotime('-1 Year')))
        ->count();

        $aga_6_female = $aga_6 - $aga_6_male;
        //END

        array_push($age_array, [
            'name' => '80 yrs and above',
            'c_l2y' => $aga_1,
            'c_l2y_male' => $aga_1_male,
            'c_l2y_female' => $aga_1_female,
            'd_l2y' => $aga_2,
            'd_l2y_male' => $aga_2_male,
            'd_l2y_female' => $aga_2_female,
            'r_l2y' => $aga_3,
            'r_l2y_male' => $aga_3_male,
            'r_l2y_female' => $aga_3_female,
            'c_l1y' => $aga_4,
            'c_l1y_male' => $aga_4_male,
            'c_l1y_female' => $aga_4_female,
            'd_l1y' => $aga_5,
            'd_l1y_male' => $aga_5_male,
            'd_l1y_female' => $aga_5_female,
            'r_l1y' => $aga_6,
            'r_l1y_male' => $aga_6_male,
            'r_l1y_female' => $aga_6_female,
        ]);

        return view('report_accomplishment', [
            'qstr' => $qstr,
            'count1' => $count1,
            'count2' => $count2,
            'count3' => $count3,
            'count4' => $count4,
            'count5' => $count5,
            'count6' => $count6,
            'count7' => $count7,
            'brgylist' => $brgyArray,
            'year' => $year,
            //'brgylist1' => $brgyArray1,
            //'brgylist2' => $brgyArray2,
            'malecount' => $malecount,
            'femalecount' => $femalecount,
            'currq_active' => $currq_active,
            'swabarr' => $swabarr,
            'lastYearSwab' => $lastYearSwab,
            'cy_hospitalized' => $cy_hospitalized,
            'cy_hospitalized_recovered' => $cy_hospitalized_recovered,
            'cy_hospitalized_died' => $cy_hospitalized_died,
            'cy_hospitalized_partialvacc' => $cy_hospitalized_partialvacc,
            'cy_hospitalized_fullvacc' => $cy_hospitalized_fullvacc,
            'cy_hospitalized_boostered' => $cy_hospitalized_boostered,
            'lastyear_hospitalized' => $lastyear_hospitalized,
            'lastyear_hospitalized_recovered' => $lastyear_hospitalized_recovered,
            'lastyear_hospitalized_died' => $lastyear_hospitalized_died,
            'lastyear_hospitalized_partialvacc' => $lastyear_hospitalized_partialvacc,
            'lastyear_hospitalized_fullvacc' => $lastyear_hospitalized_fullvacc,
            'lastyear_hospitalized_boostered' => $lastyear_hospitalized_boostered,
            'age_array' => $age_array,
        ]);
    }

    public function casechecker_index() {
        if(request()->input('sdate') && request()->input('edate')) {
            $list = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', request()->input('address_province'))
                ->where('records.address_city', request()->input('address_city'))
                ->where('records.address_brgy', request()->input('address_brgy'));
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereBetween('dateReported', [request()->input('sdate'), request()->input('edate')])
            ->orderBy('dateReported', 'DESC')
            ->get();

            if($list->count() != 0) {
                return view('casechecker_index', [
                    'list' => $list,
                ]);
            }
            else {
                return view('casechecker_index', [
                    'msg' => 'No Results found for BRGY. '.request()->input('address_brgy').', '.request()->input('address_city').', '.request()->input('address_province').' on the specified date range.',
                    'msgtype' => 'warning',
                ]);
            }   
        }
        else {
            return view('casechecker_index');
        }
    }

    public function m2fhsis() {
        if(request()->input('year') && request()->input('month')) {
            $brgy = Brgy::where('city_id', 1)
            ->where('displayInList', 1)
            ->orderBy('brgyName', 'ASC')
            ->get();
            
            $y = request()->input('year');
            $m = request()->input('month');

            $collect1 = [];
            $collect2 = [];
            $collect3 = [];

            foreach ($brgy as $b) {
                $rq = Forms::whereHas('records', function ($q) use ($b) {
                    $q->where('records.address_province', $b->city->province->provinceName)
                    ->where('records.address_city', $b->city->cityName)
                    ->where('records.address_brgy', $b->brgyName);
                })
                ->where('status', 'approved')
                ->where('caseClassification', 'Confirmed')
                ->whereMonth('morbidityMonth', $m)
                ->whereYear('morbidityMonth', $y)
                ->get();

                $r1_male = 0; //0-6 Days
                $r1_female = 0; //0-6 Days
                $r2_male = 0; //7-28 Days
                $r2_female = 0; //7-28 Days
                $r3_male = 0; //29 Days - 11 Months
                $r3_female = 0; //29 Days - 11 Months
                $r4_male = 0; //1-4 y/o
                $r4_female = 0; //1-4 y/o
                $r5_male = 0; //5-9 y/o
                $r5_female = 0; //5-9 y/o
                $r6_male = 0; //10-14 y/o
                $r6_female = 0; //10-14 y/o
                $r7_male = 0; //15-19 y/o
                $r7_female = 0; //15-19 y/o
                $r8_male = 0; //20-24 y/o
                $r8_female = 0; //20-24 y/o
                $r9_male = 0; //25-29 y/o
                $r9_female = 0; //25-29 y/o
                $r10_male = 0; //30-34 y/o
                $r10_female = 0; //30-34 y/o
                $r11_male = 0; //35-39 y/o
                $r11_female = 0; //35-39 y/o
                $r12_male = 0; //40-44 y/o
                $r12_female = 0; //40-44 y/o
                $r13_male = 0; //45-49 y/o
                $r13_female = 0; //45-49 y/o
                $r14_male = 0; //50-54 y/o
                $r14_female = 0; //50-54 y/o
                $r15_male = 0; //55-59 y/o
                $r15_female = 0; //55-59 y/o
                $r16_male = 0; //60-64 y/o
                $r16_female = 0; //60-64 y/o
                $r17_male = 0; //65-69 y/o
                $r17_female = 0; //65-69 y/o
                $r18_male = 0; //>= 70 y/o
                $r18_female = 0; //>= 70 y/o
                $r19_male = 0; //Total
                $r19_female = 0; //Total
                $r20 = 0; //TOTAL Both Sex


                foreach ($rq as $d) {
                    $inyear = $d->records->getAgeInt();
                    if($inyear == 0) {
                        $inmonth = Carbon::parse($d->records->bdate)->diff(\Carbon\Carbon::now())->format('%m');
                        if($inmonth == 0 || $inmonth <= 11) {
                            $indays = Carbon::parse($d->records->bdate)->diff(\Carbon\Carbon::now())->format('%d');

                            if($indays >= 0 && $indays <= 6) {
                                if($d->records->gender == 'MALE') {
                                    $r1_male++;
                                }
                                else {
                                    $r1_female++;
                                }
                            }
                            else if($indays >= 7 && $indays <= 28) {
                                if($d->records->gender == 'MALE') {
                                    $r2_male++;
                                }
                                else {
                                    $r2_female++;
                                }
                            }
                            else if($indays >= 29 && $inmonth <= 11) {
                                if($d->records->gender == 'MALE') {
                                    $r3_male++;
                                }
                                else {
                                    $r3_female++;
                                }
                            }
                        }
                    }
                    else {
                        if($inyear >= 1 && $inyear <= 4) {
                            if($d->records->gender == 'MALE') {
                                $r4_male++;
                            }
                            else {
                                $r4_female++;
                            }
                        }
                        else if($inyear >= 5 && $inyear <= 9) {
                            if($d->records->gender == 'MALE') {
                                $r5_male++;
                            }
                            else {
                                $r5_female++;
                            }
                        }
                        else if($inyear >= 10 && $inyear <= 14) {
                            if($d->records->gender == 'MALE') {
                                $r6_male++;
                            }
                            else {
                                $r6_female++;
                            }
                        }
                        else if($inyear >= 15 && $inyear <= 19) {
                            if($d->records->gender == 'MALE') {
                                $r7_male++;
                            }
                            else {
                                $r7_female++;
                            }
                        }
                        else if($inyear >= 20 && $inyear <= 24) {
                            if($d->records->gender == 'MALE') {
                                $r8_male++;
                            }
                            else {
                                $r8_female++;
                            }
                        }
                        else if($inyear >= 25 && $inyear <= 29) {
                            if($d->records->gender == 'MALE') {
                                $r9_male++;
                            }
                            else {
                                $r9_female++;
                            }
                        }
                        else if($inyear >= 30 && $inyear <= 34) {
                            if($d->records->gender == 'MALE') {
                                $r10_male++;
                            }
                            else {
                                $r10_female++;
                            }
                        }
                        else if($inyear >= 35 && $inyear <= 39) {
                            if($d->records->gender == 'MALE') {
                                $r11_male++;
                            }
                            else {
                                $r11_female++;
                            }
                        }
                        else if($inyear >= 40 && $inyear <= 44) {
                            if($d->records->gender == 'MALE') {
                                $r12_male++;
                            }
                            else {
                                $r12_female++;
                            }
                        }
                        else if($inyear >= 45 && $inyear <= 49) {
                            if($d->records->gender == 'MALE') {
                                $r13_male++;
                            }
                            else {
                                $r13_female++;
                            }
                        }
                        else if($inyear >= 50 && $inyear <= 54) {
                            if($d->records->gender == 'MALE') {
                                $r14_male++;
                            }
                            else {
                                $r14_female++;
                            }
                        }
                        else if($inyear >= 55 && $inyear <= 59) {
                            if($d->records->gender == 'MALE') {
                                $r15_male++;
                            }
                            else {
                                $r15_female++;
                            }
                        }
                        else if($inyear >= 60 && $inyear <= 64) {
                            if($d->records->gender == 'MALE') {
                                $r16_male++;
                            }
                            else {
                                $r16_female++;
                            }
                        }
                        else if($inyear >= 65 && $inyear <= 69) {
                            if($d->records->gender == 'MALE') {
                                $r17_male++;
                            }
                            else {
                                $r17_female++;
                            }
                        }
                        else if($inyear >= 70) {
                            if($d->records->gender == 'MALE') {
                                $r18_male++;
                            }
                            else {
                                $r18_female++;
                            }
                        }
                    }

                    $r19_male = $r1_male + $r2_male + $r3_male + $r4_male + $r5_male + $r6_male + $r7_male + $r8_male + $r9_male + $r10_male + $r11_male + $r12_male + $r13_male + $r14_male + $r15_male + $r16_male + $r17_male + $r18_male;
                    $r19_female = $r1_female + $r2_female + $r3_female + $r4_female + $r5_female + $r6_female + $r7_female + $r8_female + $r9_female + $r10_female + $r11_female + $r12_female + $r13_female + $r14_female + $r15_female + $r16_female + $r17_female + $r18_female;
                    $r20 = $r19_male + $r19_female;
                }

                array_push($collect1, [
                    'brgy' => $b->brgyName,
                    'item1_male' => $r1_male,
                    'item1_female' => $r1_female,
                    'item2_male' => $r2_male,
                    'item2_female' => $r2_female,
                    'item3_male' => $r3_male,
                    'item3_female' => $r3_female,
                    'item4_male' => $r4_male,
                    'item4_female' => $r4_female,
                    'item5_male' => $r5_male,
                    'item5_female' => $r5_female,
                    'item6_male' => $r6_male,
                    'item6_female' => $r6_female,
                    'item7_male' => $r7_male,
                    'item7_female' => $r7_female,
                    'item8_male' => $r8_male,
                    'item8_female' => $r8_female,
                    'item9_male' => $r9_male,
                    'item9_female' => $r9_female,
                    'item10_male' => $r10_male,
                    'item10_female' => $r10_female,
                    'item11_male' => $r11_male,
                    'item11_female' => $r11_female,
                    'item12_male' => $r12_male,
                    'item12_female' => $r12_female,
                    'item13_male' => $r13_male,
                    'item13_female' => $r13_female,
                    'item14_male' => $r14_male,
                    'item14_female' => $r14_female,
                    'item15_male' => $r15_male,
                    'item15_female' => $r15_female,
                    'item16_male' => $r16_male,
                    'item16_female' => $r16_female,
                    'item17_male' => $r17_male,
                    'item17_female' => $r17_female,
                    'item18_male' => $r18_male,
                    'item18_female' => $r18_female,
                    'item19_male' => $r19_male,
                    'item19_female' => $r19_female,
                    'item20' => $r20,
                ]);
            }

            return view('report_fhsism2', [
                'collect1' => $collect1,
            ]);
        }
        else {
            return view('report_fhsism2');
        }
    }

    public function temprec() {
        $l = Forms::where('outcomeCondition', 'Recovered')
        ->where('status', 'approved')
        ->whereDate('outcomeRecovDate', '1970-01-01')
        ->get();

        foreach($l as $item) {
            if($item->dispoType != 6 && $item->dispoType != 7) {
                if(!is_null($item->testDateCollected2)) {
                    $swabDateCollected = $item->testDateCollected2;
                }
                else {
                    $swabDateCollected = $item->testDateCollected1;
                }

                //Note: If may babaguhin dito, dapat palitan din yung sa FormsController Store and Update
                if($item->dispoType == 1 || $item->healthStatus == 'Severe' || $item->healthStatus == 'Critical') {
                    $daysToRecover = 21;
                }
                else {
                    if(!is_null($item->records->vaccinationDate2)) {
                        $date1 = Carbon::parse($item->records->vaccinationDate2);
                        $days_diff = $date1->diffInDays($dateToday);

                        if($days_diff >= 14) {
                            $daysToRecover = 7;
                        }
                        else {
                            $daysToRecover = 10;
                        }
                    }
                    else {
                        if($item->records->vaccinationName1 == 'JANSSEN') {
                            $date1 = Carbon::parse($item->records->vaccinationDate1);
                            $days_diff = $date1->diffInDays($dateToday);

                            if($days_diff >= 14) {
                                $daysToRecover = 7;
                            }
                            else {
                                $daysToRecover = 10;
                            }
                        }
                        else {
                            $daysToRecover = 10;
                        }
                    }
                }
    
                /*

                OLD FORMAT (IBANG DATE PAG SA CLOSE CONTACT)

                if($item->pType == 'PROBABLE' || $item->pType == 'TESTING') {
                    $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                    $recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover-1).' Day')));
                }
                else if($item->pType == 'CLOSE CONTACT') {
                    if(!is_null($item->expoitem1)) {
                        $startDate = Carbon::parse(date('Y-m-d', strtotime($item->expoitem1)));
                        $recoverDate = Carbon::parse(date('Y-m-d', strtotime($item->expoitem1.' + '.($daysToRecover-1).' Day')));
                    }
                    else {
                        $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                        $recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover-1).' Day')));
                    }
                }
                */

                $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                //$recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover - 1).' Day'))); //MINUS ONE BECAUSE START DATE IS CONSIRED AS DAY 1
                
                $diff = $startDate->diffInDays($dateToday);
                if($diff >= $daysToRecover) { //MINUS ONE BECAUSE START DATE IS CONSIRED AS DAY 1
                    $update = Forms::find($item->id);
    
                    //$update->outcomeRecovDate = date('Y-m-d');
                    $update->outcomeRecovDate = Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('Y-m-d');
    
                    if($update->isDirty()) {
                        $update->save();
                    }
                }
            }
        }
        
        echo 'done';
    }
}
