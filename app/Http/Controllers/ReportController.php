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
        $list = Forms::all();
        $brgy = Brgy::all();

        return view('reports_home', ['list' => $list, 'brgy_list' => $brgy]);
    }

    public function makeAllSuspected() {
        $query = Forms::where(function($query) {
            $query->where('testDateCollected1', date('Y-m-d'))
            ->orWhere('testDateCollected2', date('Y-m-d'));
        })->where('isPresentOnSwabDay', 0)
        ->update(['caseClassification' => 'Suspect']);

        return redirect()->action([ReportController::class, 'index'])->with('status', 'All patients who were absent for today were moved in SUSPECTED Case.')->with('statustype', 'success');
    }

    public function dohExport() {
        return Excel::download(new DOHExport, 'DOH_Excel_'.date('m_d_Y').'.xlsx');
    }

    public function allcifExport() {
        return Excel::download(new FormsExport([0]), 'CIF_ALL_'.date("m_d_Y").'.xlsx');
    }
}
