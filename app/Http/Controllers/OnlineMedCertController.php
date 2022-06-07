<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Http\Request;

class OnlineMedCertController extends Controller
{
    public function index() {
        return view('medcert_online');
    }

    public function check(Request $request) {
        if($request->filled('mname')) {
            $b = Forms::whereHas('records', function ($q) use ($request) {
                $q->where('lname', mb_strtoupper($request->lname))
                ->where('fname', mb_strtoupper($request->fname))
                ->where('mname', mb_strtoupper($request->mname))
                ->whereDate('bdate', $request->bdate);
            })
            ->where(function ($q) use ($request){
                $q->whereDate('testDateCollected1', $request->date_swabbed)
                ->orWhereDate('testDateCollected2', $request->date_swabbed);
            })
            ->first();
        }
        else {
            $b = Forms::whereHas('records', function ($q) use ($request) {
                $q->where('lname', mb_strtoupper($request->lname))
                ->where('fname', mb_strtoupper($request->fname))
                ->whereNull('mname')
                ->whereDate('bdate', $request->bdate);
            })
            ->where(function ($q) use ($request) {
                $q->whereDate('testDateCollected1', $request->date_swabbed)
                ->orWhereDate('testDateCollected2', $request->date_swabbed);
            })
            ->first();
        }

        if($b) {
            if($b->outcomeCondition == 'Recovered') {
                $number = date('j');

                $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                if (($number %100) >= 11 && ($number%100) <= 13) {
                    $abbreviation = $number. 'th';
                }
                else {
                    $abbreviation = $number. $ends[$number % 10];
                }

                if($b->pType == 'CLOSE CONTACT') {
                    $pui = true;
                }
                else {
                    $pui = false;
                }

                if(!is_null($b->SAS)) {
                    $pum = true;
                }
                else {
                    $pum = false;
                }

                if(!is_null($b->testDateCollected2)) {
                    $qds = $b->testDateCollected2;
                }
                else {
                    $qds = $b->testDateCollected1;
                }

                if($b->records->ifFullyVaccinated()) {
                    $qde = Carbon::parse($qds)->addDays(10)->format('Y-m-d');
                }
                else {
                    $qde = Carbon::parse($qds)->addDays(14)->format('Y-m-d');
                }

                return view('medcert', [
                    'data' => $b,
                    'pui' => $pui,
                    'pum' => $pum,
                    'cardinal' => $abbreviation,
                    'qds' => $qds,
                    'qde' => $qde,
                ]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'Unable to generate. Patient is not yet recovered.')
                ->with('msgtype', 'danger');
            }
        }
        else {
            return redirect()->back()
            ->with('msg', 'No results found.')
            ->with('msgtype', 'warning');
        }
    }
}
