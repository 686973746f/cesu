<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use Illuminate\Http\Request;

class FacilityReportingController extends Controller
{
    public function index($code) {
        $d = DohFacility::where('sys_code1', $code)->first();

        if($d) {
            return view('facility_report.index', [
                'd' => $d,
            ]);
        }
        else {
            return abort(401);
        }
    }
}
