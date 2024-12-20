<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use Illuminate\Http\Request;

class InjuryController extends Controller
{
    public function index($code) {
        $d = DohFacility::where('sys_code1', $code)->first();

        if($d) {
            return view('injury_report.index', [
                'd' => $d,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function store($code, Request $r) {
        $d = DohFacility::where('sys_code1', $code)->first();

        
    }
}
