<?php

namespace App\Http\Controllers;

use App\Models\BarangayHealthStation;
use App\Models\DohFacility;
use Illuminate\Http\Request;

class FwriController extends Controller
{
    public function index($code) {
        $s = BarangayHealthStation::where('sys_code1', $code)->first();
        $facility_name = $s->name;

        if(!($s)) {
            $s = DohFacility::where('sys_code1', $code)->first();
            $facility_name = $s->facility_name;
        }

        if($s) {
            return view('fwri.index', [
                's' => $s,
                'hospital_name' => $facility_name,
                'code' => $code,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function store($code, Request $request) {

    }
}
