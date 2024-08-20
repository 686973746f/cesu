<?php

namespace App\Http\Controllers;

use App\Models\DohFacility;
use App\Models\HealthRelatedEventMain;
use App\Models\HealthRelatedEventPatient;
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
                'event_code' => $event_code,
                'facility_code' => $facility_code,
            ]);
        }
        else {

        }
    }

    public function encodeCheck($event_code, $facility_code, Request $r) {
        $lname = mb_strtoupper(request()->input('lname'));
        $fname = mb_strtoupper(request()->input('fname'));
        $mname = (!is_null(request()->input('mname'))) ? mb_strtoupper(request()->input('mname')) : NULL;
        $suffix = (!is_null(request()->input('mname'))) ? mb_strtoupper(request()->input('suffix')) : NULL;
        $bdate = request()->input('bdate');

        //Check if Record Existing
        $he = HealthRelatedEventMain::where('qr', $event_code)->first();

        $facility = DohFacility::where('sys_code1', $facility_code)->first();

        $patient = HealthRelatedEventPatient::where('healthevent_id', $he->id)
        ->where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate);

        if(!(clone $patient)->first()) {
            if(!is_null($mname)) {
                $patient = $patient->where('mname', $mname);
            }
            
            if(!is_null($suffix)) {
                $patient = $patient->where('suffix', $suffix);
            }

            if($patient->first()) {

            }
            else {
                return view('healthevents.encode_part2', [
                    'lname' => $lname,
                    'fname' => $fname,
                    'mname' => $mname,
                    'suffix' => $suffix,
                    'bdate' => $bdate,

                    'f' => $facility,
                    'he' => $he,
                    'event_code' => $event_code,
                    'facility_code' => $facility_code,
                ]);
            }
        }
        else {
            
        }
    }

    public function encodeStore($event_code, $facility_code, Request $r) {
        
    }

    public function encodeSuccess($event_code, $facility_code) {
        
    }
}
