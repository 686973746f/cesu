<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\DohFacility;
use Illuminate\Http\Request;
use App\Models\HealthRelatedEventMain;
use App\Models\HealthRelatedEventPatient;
use App\Models\HealthRelatedEventRecords;

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
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = (!is_null($r->mname)) ? mb_strtoupper($r->mname) : NULL;
        $suffix = (!is_null($r->suffix)) ? mb_strtoupper($r->suffix) : NULL;
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
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: Patient data already exists. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }
            else {
                //get age in years, month, days
                $birthdate = Carbon::parse($r->bdate);
                $currentDate = Carbon::parse(date('Y-m-d'));

                $get_ageyears = $birthdate->diffInYears($currentDate);
                $get_agemonths = $birthdate->diffInMonths($currentDate);
                $get_agedays = $birthdate->diffInDays($currentDate);

                $createPatient = HealthRelatedEventPatient::create([
                    'enabled' => 1,
                    'lname' => $lname,
                    'fname' => $fname,
                    'mname' => $mname,
                    'suffix' => $suffix,
                    'bdate' => $bdate,
                    'gender' => substr($r->gender,0,1),
                    'is_pregnant' => ($r->gender == 'FEMALE' && $get_ageyears >= 10) ? $r->is_pregnant : 'N',
                    'contact_number' => $r->contact_number,
                    'address_region_code' => $r->address_region_code,
                    'address_region_text' => $r->address_region_text,
                    'address_province_code' => $r->address_province_code,
                    'address_province_text' => $r->address_province_text,
                    'address_muncity_code' => $r->address_muncity_code,
                    'address_muncity_text' => $r->address_muncity_text,
                    'address_brgy_code' => $r->address_brgy_text,
                    'address_brgy_text' => $r->address_brgy_text,
                    'address_street' => mb_strtoupper($r->address_street),
                    'address_houseno' => mb_strtoupper($r->address_houseno),
                    'healthevent_id' => $he->id,
                    'reportedby_facility' => $facility->facility_name,
                    'reportedby_name' => mb_strtoupper($r->reportedby_name),
                ]);


                if($he->id == 1) {
                    $createRecordVars = [
                        'enabled' => 1,
                        'patient_id' => $createPatient->id,
                        'date_onset' => $r->date_onset,
                        'admitted' => $r->admitted,
                        'admittedfacility_name' => NULL,
                        'date_admittedconsulted' => $r->date_admittedconsulted,
                        'vog_dizziness' => $r->vog_dizziness,
                        'vog_dob' => $r->vog_dob,
                        'vog_cough' => $r->vog_cough,
                        'vog_eyeirritation' => $r->vog_eyeirritation,
                        'vog_throatirritation' => $r->vog_throatirritation,
                        'vog_others' => (!is_null($r->vog_others_specify)) ? 'Y' : 'N',
                        'vog_others_specify' => (!is_null($r->vog_others_specify)) ? mb_strtoupper($r->vog_others_specify) : NULL,
                        'outcome' => $r->outcome,
                        'remarks' => NULL,
                        'age_years' => $get_ageyears,
                        'age_months' => $get_agemonths,
                        'age_days' => $get_agedays,
                    ];
                }

                $createRecord = HealthRelatedEventRecords::create($createRecordVars);

                return redirect()->route('he_success', [$event_code, $facility_code, 'status' => 'oks']);
            }
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient data already exists. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function encodeSuccess($event_code, $facility_code) {
        //Check if Record Existing
        $he = HealthRelatedEventMain::where('qr', $event_code)->first();

        $facility = DohFacility::where('sys_code1', $facility_code)->first();

        if(request()->input('status') == 'oks') {
            return view('healthevents.encode_success', [
                'f' => $facility,
                'he' => $he,
                'event_code' => $event_code,
                'facility_code' => $facility_code,
            ]);
        }
        else {
            return abort(401);
        }     
    }
}
