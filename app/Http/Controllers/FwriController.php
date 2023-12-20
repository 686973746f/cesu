<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\FwInjury;
use App\Models\DohFacility;
use Illuminate\Http\Request;
use App\Models\BarangayHealthStation;

class FwriController extends Controller
{
    public function index($code) {
        $s = BarangayHealthStation::where('sys_code1', $code)->first();
        
        if(!($s)) {
            $s = DohFacility::where('sys_code1', $code)->first();
            $facility_name = $s->facility_name;
        }
        else {
            $facility_name = $s->name;
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

    public function store($code, Request $r) {
        //SEARCH CODE AND FETCH FACILITY NAME

        $s = BarangayHealthStation::where('sys_code1', $code)->first();
        
        if(!($s)) {
            $s = DohFacility::where('sys_code1', $code)->first();
            $facility_name = $s->facility_name;
        }
        else {
            $facility_name = $s->name;
        }

        if($s) {
            $c = FwInjury::create([
                'reported_by' => mb_strtoupper($r->reported_by),
                'report_date' => $r->report_date,
                'facility_code' => $code,
                'hospital_name' => $facility_name,
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->lname) : NULL,
                'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->lname) : NULL,
                'bdate' => $r->bdate,
                'gender' => mb_strtoupper($r->gender),
                'contact_number' => $r->contact_number,
                'address_region_code' => $r->address_region_code,
                'address_region_text' => $r->address_region_text,
                'address_province_code' => $r->address_province_code,
                'address_province_text' => $r->address_province_text,
                'address_muncity_code' => $r->address_muncity_code,
                'address_muncity_text' => $r->address_muncity_text,
                'address_brgy_code' => $r->address_brgy_text,
                'address_brgy_text' => $r->address_brgy_text,
                'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
                'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
                'injury_date' => $r->injury_date,
                'consultation_date' => $r->consultation_date,
                'reffered_anotherhospital' => $r->reffered_anotherhospital,
                'nameof_hospital' => ($r->reffered_anotherhospital == 'Y') ? mb_strtoupper($r->nameof_hospital) : NULL,
                'place_of_occurrence' => $r->reffered_anotherhospital,
                'place_of_occurrence_others' => ($r->place_of_occurrence_others == 'OTHERS') ? mb_strtoupper($r->reffered_anotherhospital) : NULL,
                'injury_address_region_code' => $r->injury_address_region_code,
                'injury_address_region_text' => $r->injury_address_region_text,
                'injury_address_province_code' => $r->injury_address_province_code,
                'injury_address_province_text' => $r->injury_address_province_text,
                'injury_address_muncity_code' => $r->injury_address_muncity_code,
                'injury_address_muncity_text' => $r->injury_address_muncity_text,
                'injury_address_brgy_code' => $r->injury_address_brgy_text,
                'injury_address_brgy_text' => $r->injury_address_brgy_text,
                'injury_address_street' => ($r->filled('injury_address_street')) ? mb_strtoupper($r->injury_address_street) : NULL,
                'injury_address_houseno' => ($r->filled('injury_address_houseno')) ? mb_strtoupper($r->injury_address_houseno) : NULL,
                'involvement_type' => $r->involvement_type,
                'nature_injury' => $r->nature_injury,
                'iffw_typeofinjury' => ($r->nature_injury == 'FIREWORKS INJURY') ? implode(',', $r->iffw_typeofinjury) : NULL,
                'complete_diagnosis' => mb_strtoupper($r->complete_diagnosis),
                'anatomical_location' => implode(',', $r->anatomical_location),
                'firework_name' => mb_strtoupper($r->firework_name),
                'liquor_intoxication' => $r->liquor_intoxication,
                'treatment_given' => implode(',', $r->treatment_given),
                'disposition_after_consultation' => $r->disposition_after_consultation,
                'disposition_after_consultation_transferred_hospital' => ($r->disposition_after_consultation == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_consultation_transferred_hospital) : NULL,

                'disposition_after_admission' => $r->disposition_after_admission,
                'disposition_after_admission_transferred_hospital' => ($r->disposition_after_admission == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_admission_transferred_hospital) : NULL,
                
                'date_died' => ($r->disposition_after_admission) ? $r->date_died : NULL,
                'aware_healtheducation_list' => implode(',', $r->aware_healtheducation_list),
            ]);

            return redirect()->route('fwri_success', $code);
        }
        else {
            return abort(401);
        }
    }

    public function success($code) {
        return view('fwri.success', [
            'code' => $code,
        ]);
    }

    public function home() {
        $list = FwInjury::orderBy('created_at', 'DESC')->paginate(10);

        return view('fwri.home', [
            'list' => $list,
        ]);
    }

    public function viewCif($id) {
        $d = FwInjury::findOrFail($id);

        return view('fwri.viewcif', [
            'd' => $d,
        ]);
    }

    public function updateCif($id, Request $r) {

    }

    public function printCif($id) {

    }

    public function report() {
        if(request()->input('forYear')) {
            $forYear = request()->input('forYear');

            $date1 = date($forYear.'-12-21');
            $date2 = date(($forYear + 1).'-01-05');
        }
        else {
            $forYear = date('Y');

            $date1 = date('Y-12-21');
            $date2 = date(($forYear + 1).'-01-05');
        }

        $init_list = FwInjury::whereBetween('injury_date', [$date1, $date2]);

        $get_total = $init_list->count();

        $get_male = $init_list->where('gender', 'MALE')->count();
        $get_female = $init_list->where('gender', 'FEMALE')->count();

        //LOOP THROUGH DATES AND GET TOTAL INJURIES

        //PLACE OF OCCURRENCE
        $p_home = $init_list->where('place_of_occurrence', 'HOME')->count();
        $p_street = $init_list->where('place_of_occurrence', 'STREET')->count();
        $p_designated_area = $init_list->where('place_of_occurrence', 'DESIGNATED AREA')->count();
        $p_others = $init_list->where('place_of_occurrence', 'OTHERS')->count();

        //TYPE

        //DISTINCT FIREWORKS NAME ARRAY

        return view('fwri.report', [
            'get_total' => $get_total,
            'get_male' => $get_male,
            'get_female' => $get_female,

            'p_home' => $p_home,
            'p_street' => $p_street,
            'p_designated_area' => $p_designated_area,
            'p_others' => $p_others,
        ]);
    }
}
