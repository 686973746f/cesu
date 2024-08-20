<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use App\Models\HealthRelatedEventMain;
use Illuminate\Http\Request;

class HealthEventsController extends Controller
{
    public function encodeIndex($event_code, $facility_code) {
        //Check Facility Code
        $facility = DohFacility::where('sys_code1', $facility_code)->first();

        if(!$facility) {

        }

        //Check Health Event Code
        $he = HealthRelatedEventMain::where('qr', $event_code)->first();

        if($he) {
            return view('healthevents.encode_index', [
                'f' => $facility,
                'he' => $he,
            ]);
        }
        else {

        }
    }

    public function encodeCheck($event_code, $facility_code, Request $r) {

    }

    public function encodeStore($event_code, $facility_code, Request $r) {
        
    }

    public function encodeSuccess($event_code, $facility_code) {
        
    }
}
