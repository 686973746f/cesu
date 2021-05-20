<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Illuminate\Http\Request;
use App\Exports\DOHExport;
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

        Excel::create('Filename', function($excel) {

            // Our first sheet
            $excel->sheet('First sheet', function($sheet) {
        
            });
        
            // Our second sheet
            $excel->sheet('Second sheet', function($sheet) {
        
            });
        
        })->export('xlsx');
    }
}
