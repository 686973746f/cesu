<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use Illuminate\Http\Request;
use App\Imports\FhsisTbdotsImport;
use App\Imports\FireworksImport;
use App\Models\Injury;
use Maatwebsite\Excel\Facades\Excel;

class InjuryController extends Controller
{
    public function index($code) {
        $f = DohFacility::where('sys_code1', $code)->first();

        if($f) {
            return view('injury_report.index', compact('f'));
        }
        else {
            return abort(401);
        }
    }

    public function uploadfwri($code, Request $r) {
        $f = DohFacility::where('sys_code1', $code)->first();

        Excel::import(new FireworksImport($f), $r->csv_file);

        return redirect()
        ->back()
        ->with('msg', 'FWRI database was imported successfully')
        ->with('msgtype', 'success');
    }

    public function uploadinjury($code, Request $r) {
        $f = DohFacility::where('sys_code1', $code)->first();

        dd('not yet done');
    }

    public function injuryNewOrEdit(Injury $record, $code = null) {
        if($code) {
            $f = DohFacility::where('sys_code1', $code)->first();
        }
        else {
            //
        }

        return view('injury_report.injury_form', [
            'd' => $record,
            'f' => $f,
        ]);
    }

    public function addCaseCheck($code = null) {
        return $this->injuryNewOrEdit(new Injury(), $code)->with('mode', 'NEW');
    }

    public function store($code = null, Request $r) {
        
    }
}
