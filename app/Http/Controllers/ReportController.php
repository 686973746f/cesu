<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $list = Forms::all();
        $brgy = Brgy::all();

        return view('reports_home', ['list' => $list, 'brgy_list' => $brgy]);
    }

    public function makeAllSuspected() {
        $query = Forms::where('testDateCollected1', date('Y-m-d'))
        ->where('isPresentOnSwabDay', 0)
        ->update(['caseClassification' => 'Suspect']);

        return redirect()->action([ReportController::class, 'index'])->with('status', 'All patients who were absent for today were moved in SUSPECTED Case.')->with('statustype', 'success');
    }
}
