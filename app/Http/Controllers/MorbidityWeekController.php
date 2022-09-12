<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\MorbidityWeek;

class MorbidityWeekController extends Controller
{
    public function index() {
        return view('mw');
    }

    public function process(Request $request) {
        $year = $request->year;

        $s = MorbidityWeek::where('year', $year)->first();

        if($s) {
            $d = MorbidityWeek::findOrFail($s->id);
        }
        else {
            $create = MorbidityWeek::create(['year' => $year]);

            $s = MorbidityWeek::where('year', $year)->first();
            $d = MorbidityWeek::findOrFail($s->id);
        }

        $start = $year.'-01-01';
        $end = $year.'-12-31';

        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $cw = date('W', strtotime($date->format('Y-m-d')));

            $count = Forms::with('records')
            ->whereHas('records', function ($q) {
                $q->where('records.address_province', 'CAVITE')
                ->where('records.address_city', 'GENERAL TRIAS');
            })
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->whereDate('morbidityMonth', $date->format('Y-m-d'))
            ->count();

            if($year == date('Y')) {
                $maxweek = date('W');
            }
            else {
                $maxweek = date('W', strtotime($year.'-12-31'));
            }

            if(date('W', strtotime($date->format('Y-m-d'))) == 52 && date('m', strtotime($date->format('Y-m-d'))) == 01) {
                $lys = MorbidityWeek::where('year', date('Y', strtotime($date->format('Y-m-d').' - 1 Year')))->first();

                if($lys) {
                    $lyd = MorbidityWeek::findOrFail($lys->id);
                }
                else {
                    $lydcreate = MorbidityWeek::create(['year' => date('Y', strtotime($date->format('Y-m-d').' - 1 Year'))]);
        
                    $lys = MorbidityWeek::where('year', date('Y', strtotime($date->format('Y-m-d').' - 1 Year')))->first();
                    $lyd = MorbidityWeek::findOrFail($lys->id);
                }

                $lyd->mw52 = $lyd->mw52 + $count;

                if($lyd->isDirty()) {
                    $lyd->save();
                }
            }
            else if(date('W', strtotime($date->format('Y-m-d'))) == 53 && date('m', strtotime($date->format('Y-m-d'))) == 01) {
                $lys = MorbidityWeek::where('year', date('Y', strtotime($date->format('Y-m-d').' - 1 Year')))->first();

                if($lys) {
                    $lyd = MorbidityWeek::findOrFail($lys->id);
                }
                else {
                    $lydcreate = MorbidityWeek::create(['year' => date('Y', strtotime($date->format('Y-m-d').' - 1 Year'))]);
        
                    $lys = MorbidityWeek::where('year', date('Y', strtotime($date->format('Y-m-d').' - 1 Year')))->first();
                    $lyd = MorbidityWeek::findOrFail($lys->id);
                }

                $lyd->mw53 = $lyd->mw53 + $count;

                if($lyd->isDirty()) {
                    $lyd->save();
                }
            }
            else if($cw == 1 && $cw <= $maxweek) {
                $d->mw1 = $d->mw1 + $count;
            }
            else if($cw == 2 && $cw <= $maxweek) {
                $d->mw2 = $d->mw2 + $count;
            }
            else if($cw == 3 && $cw <= $maxweek) {
                $d->mw3 = $d->mw3 + $count;
            }
            else if($cw == 4 && $cw <= $maxweek) {
                $d->mw4 = $d->mw4 + $count;
            }
            else if($cw == 5 && $cw <= $maxweek) {
                $d->mw5 = $d->mw5 + $count;
            }
            else if($cw == 6 && $cw <= $maxweek) {
                $d->mw6 = $d->mw6 + $count;
            }
            else if($cw == 7 && $cw <= $maxweek) {
                $d->mw7 = $d->mw7 + $count;
            }
            else if($cw == 8 && $cw <= $maxweek) {
                $d->mw8 = $d->mw8 + $count;
            }
            else if($cw == 9 && $cw <= $maxweek) {
                $d->mw9 = $d->mw9 + $count;
            }
            else if($cw == 10 && $cw <= $maxweek) {
                $d->mw10 = $d->mw10 + $count;
            }
            else if($cw == 11 && $cw <= $maxweek) {
                $d->mw11 = $d->mw11 + $count;
            }
            else if($cw == 12 && $cw <= $maxweek) {
                $d->mw12 = $d->mw12 + $count;
            }
            else if($cw == 13 && $cw <= $maxweek) {
                $d->mw13 = $d->mw13 + $count;
            }
            else if($cw == 14 && $cw <= $maxweek) {
                $d->mw14 = $d->mw14 + $count;
            }
            else if($cw == 15 && $cw <= $maxweek) {
                $d->mw15 = $d->mw15 + $count;
            }
            else if($cw == 16 && $cw <= $maxweek) {
                $d->mw16 = $d->mw16 + $count;
            }
            else if($cw == 17 && $cw <= $maxweek) {
                $d->mw17 = $d->mw17 + $count;
            }
            else if($cw == 18 && $cw <= $maxweek) {
                $d->mw18 = $d->mw18 + $count;
            }
            else if($cw == 19 && $cw <= $maxweek) {
                $d->mw19 = $d->mw19 + $count;
            }
            else if($cw == 20 && $cw <= $maxweek) {
                $d->mw20 = $d->mw20 + $count;
            }
            else if($cw == 21 && $cw <= $maxweek) {
                $d->mw21 = $d->mw21 + $count;
            }
            else if($cw == 22 && $cw <= $maxweek) {
                $d->mw22 = $d->mw22 + $count;
            }
            else if($cw == 23 && $cw <= $maxweek) {
                $d->mw23 = $d->mw23 + $count;
            }
            else if($cw == 24 && $cw <= $maxweek) {
                $d->mw24 = $d->mw24 + $count;
            }
            else if($cw == 25 && $cw <= $maxweek) {
                $d->mw25 = $d->mw25 + $count;
            }
            else if($cw == 26 && $cw <= $maxweek) {
                $d->mw26 = $d->mw26 + $count;
            }
            else if($cw == 27 && $cw <= $maxweek) {
                $d->mw27 = $d->mw27 + $count;
            }
            else if($cw == 28 && $cw <= $maxweek) {
                $d->mw28 = $d->mw28 + $count;
            }
            else if($cw == 29 && $cw <= $maxweek) {
                $d->mw29 = $d->mw29 + $count;
            }
            else if($cw == 30 && $cw <= $maxweek) {
                $d->mw30 = $d->mw30 + $count;
            }
            else if($cw == 31 && $cw <= $maxweek) {
                $d->mw31 = $d->mw31 + $count;
            }
            else if($cw == 32 && $cw <= $maxweek) {
                $d->mw32 = $d->mw32 + $count;
            }
            else if($cw == 33 && $cw <= $maxweek) {
                $d->mw33 = $d->mw33 + $count;
            }
            else if($cw == 34 && $cw <= $maxweek) {
                $d->mw34 = $d->mw34 + $count;
            }
            else if($cw == 35 && $cw <= $maxweek) {
                $d->mw35 = $d->mw35 + $count;
            }
            else if($cw == 36 && $cw <= $maxweek) {
                $d->mw36 = $d->mw36 + $count;
            }
            else if($cw == 37 && $cw <= $maxweek) {
                $d->mw37 = $d->mw37 + $count;
            }
            else if($cw == 38 && $cw <= $maxweek) {
                $d->mw38 = $d->mw38 + $count;
            }
            else if($cw == 39 && $cw <= $maxweek) {
                $d->mw39 = $d->mw39 + $count;
            }
            else if($cw == 40 && $cw <= $maxweek) {
                $d->mw40 = $d->mw40 + $count;
            }
            else if($cw == 41 && $cw <= $maxweek) {
                $d->mw41 = $d->mw41 + $count;
            }
            else if($cw == 42 && $cw <= $maxweek) {
                $d->mw42 = $d->mw42 + $count;
            }
            else if($cw == 43 && $cw <= $maxweek) {
                $d->mw43 = $d->mw43 + $count;
            }
            else if($cw == 44 && $cw <= $maxweek) {
                $d->mw44 = $d->mw44 + $count;
            }
            else if($cw == 45 && $cw <= $maxweek) {
                $d->mw45 = $d->mw45 + $count;
            }
            else if($cw == 46 && $cw <= $maxweek) {
                $d->mw46 = $d->mw46 + $count;
            }
            else if($cw == 47 && $cw <= $maxweek) {
                $d->mw47 = $d->mw47 + $count;
            }
            else if($cw == 48 && $cw <= $maxweek) {
                $d->mw48 = $d->mw48 + $count;
            }
            else if($cw == 49 && $cw <= $maxweek) {
                $d->mw49 = $d->mw49 + $count;
            }
            else if($cw == 50 && $cw <= $maxweek) {
                $d->mw50 = $d->mw50 + $count;
            }
            else if($cw == 51 && $cw <= $maxweek) {
                $d->mw51 = $d->mw51 + $count;
            }
            else if($cw == 52 && $cw <= $maxweek) {
                $d->mw52 = $d->mw52 + $count;
            }
            else if($cw == 53 && $cw <= $maxweek) {
                $d->mw53 = $d->mw53 + $count;
            }

            if($d->isDirty()) {
                $d->save();
            }
        } 
    }
}
