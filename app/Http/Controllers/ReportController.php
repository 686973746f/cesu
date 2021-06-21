<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use App\Exports\DOHExport;
use App\Exports\FormsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index() {
        if(auth()->user()->isCesuAccount()) {
            return view('report_select');
        }
        else {
            if(auth()->user()->isBrgyAccount()) {

            }
            else if(auth()->user()->isCompanyAccount()) {
                
            }
        }
    }

    public function viewDaily() {
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
    }

    public function viewSituational() {
        $forms = Forms::all();
        $brgy = Brgy::all();

        return view('report_situational', ['list' => $forms, 'brgy_list' => $brgy]);
    }

    public function makeAllSuspected() {
        $query = Forms::where(function($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where('isPresentOnSwabDay', 0)
        ->update(['caseClassification' => 'Suspect']);

        return redirect()->action([ReportController::class, 'index'])->with('status', 'All patients who were absent for today were moved in SUSPECTED Case.')->with('statustype', 'success');
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
