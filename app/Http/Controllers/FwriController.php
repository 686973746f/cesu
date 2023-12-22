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
            $atype = 'HOSPITAL';
        }
        else {
            $facility_name = $s->name;
            $atype = 'BHS';
        }
        
        //DUPLICATE CHECKER
        $dpcheck = FwInjury::where(function ($q) use ($r) {
            $q->whereDate('report_date', $r->report_date)
            ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname))
        ->whereDate('bdate', $r->bdate)
        ->first();

        if(!($dpcheck)) {
            //CHECK TREATMENT GIVEN
            if(in_array('NO TREATMENT', $r->treatment_given)) {
                $tr_given = 'NO TREATMENT';
            }
            else {
                $tr_given = implode(',', $r->treatment_given);
            }

            //CHECK AWARE HEALTH EDUCATION LIST
            if(in_array('', $r->aware_healtheducation_list)) {
                $get_hel = 'NOT AWARE';
            }
            else {
                $get_hel = implode(',', $r->aware_healtheducation_list);
            }

            if($s) {
                $birthdate = Carbon::parse($r->bdate);
                $currentDate = Carbon::parse(date('Y-m-d'));

                $get_ageyears = $birthdate->diffInYears($currentDate);
                $get_agemonths = $birthdate->diffInMonths($currentDate);
                $get_agedays = $birthdate->diffInDays($currentDate);

                //SAME INJURY ADDRESS CHECK
                if($r->injury_sameadd == 'Y') {
                    $inj_reg_code = $r->address_region_code;
                    $inj_reg_text = $r->address_region_text;
                    $inj_prov_code = $r->address_province_code;
                    $inj_prov_text = $r->address_province_text;
                    $inj_munc_code = $r->address_muncity_code;
                    $inj_munc_text = $r->address_muncity_text;
                    $inj_brgy = $r->address_brgy_text;
                }
                else {
                    $inj_reg_code = $r->injury_address_region_code;
                    $inj_reg_text = $r->injury_address_region_text;
                    $inj_prov_code = $r->injury_address_province_code;
                    $inj_prov_text = $r->injury_address_province_text;
                    $inj_munc_code = $r->injury_address_muncity_code;
                    $inj_munc_text = $r->injury_address_muncity_text;
                    $inj_brgy = $r->injury_address_brgy_text;
                }

                $c = FwInjury::create([
                    'reported_by' => mb_strtoupper($r->reported_by),
                    'report_date' => $r->report_date,
                    'facility_code' => $code,
                    'account_type' => $atype,
                    'hospital_name' => $facility_name,
                    'lname' => mb_strtoupper($r->lname),
                    'fname' => mb_strtoupper($r->fname),
                    'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
                    'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
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
                    'place_of_occurrence_others' => ($r->place_of_occurrence_others == 'OTHERS') ? mb_strtoupper($r->place_of_occurrence_others) : NULL,
                    'injury_sameadd' => $r->injury_sameadd,
                    'injury_address_region_code' => $inj_reg_code,
                    'injury_address_region_text' => $inj_reg_text,
                    'injury_address_province_code' => $inj_prov_code,
                    'injury_address_province_text' => $inj_prov_text,
                    'injury_address_muncity_code' => $inj_munc_code,
                    'injury_address_muncity_text' => $inj_munc_text,
                    'injury_address_brgy_code' => $inj_brgy,
                    'injury_address_brgy_text' => $inj_brgy,
                    'injury_address_street' => ($r->filled('injury_address_street')) ? mb_strtoupper($r->injury_address_street) : NULL,
                    'injury_address_houseno' => ($r->filled('injury_address_houseno')) ? mb_strtoupper($r->injury_address_houseno) : NULL,
                    'involvement_type' => $r->involvement_type,
                    'nature_injury' => $r->nature_injury,
                    'iffw_typeofinjury' => ($r->nature_injury == 'FIREWORKS INJURY') ? implode(',', $r->iffw_typeofinjury) : NULL,
                    'complete_diagnosis' => mb_strtoupper($r->complete_diagnosis),
                    'anatomical_location' => implode(',', $r->anatomical_location),
                    'firework_name' => mb_strtoupper($r->firework_name),
                    'liquor_intoxication' => $r->liquor_intoxication,
                    'treatment_given' => $tr_given,
                    'disposition_after_consultation' => $r->disposition_after_consultation,
                    'disposition_after_consultation_transferred_hospital' => ($r->disposition_after_consultation == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_consultation_transferred_hospital) : NULL,

                    'disposition_after_admission' => $r->disposition_after_admission,
                    'disposition_after_admission_transferred_hospital' => ($r->disposition_after_admission == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_admission_transferred_hospital) : NULL,
                    
                    'date_died' => ($r->disposition_after_admission == 'DIED DURING ADMISSION') ? $r->date_died : NULL,
                    'aware_healtheducation_list' => $get_hel,

                    'age_years' => $get_ageyears,
                    'age_months' => $get_agemonths,
                    'age_days' => $get_agedays,
                ]);

                return redirect()->route('fwri_success', $code);
            }
            else {
                return abort(401);
            }
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient data already exists in the server.')
            ->with('msgtype', 'warning');
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
        if(in_array('NO TREATMENT', $r->treatment_given)) {
            $tr_given = 'NO TREATMENT';
        }
        else {
            $tr_given = implode(',', $r->treatment_given);
        }

        //CHECK AWARE HEALTH EDUCATION LIST
        if(in_array('', $r->aware_healtheducation_list)) {
            $get_hel = 'NOT AWARE';
        }
        else {
            $get_hel = implode(',', $r->aware_healtheducation_list);
        }

        $birthdate = Carbon::parse($r->bdate);
        $currentDate = Carbon::parse(date('Y-m-d'));

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $update = FwInjury::where('id', $id)
        ->update([
            'reported_by' => mb_strtoupper($r->reported_by),
            'report_date' => $r->report_date,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
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
            'place_of_occurrence_others' => ($r->place_of_occurrence_others == 'OTHERS') ? mb_strtoupper($r->place_of_occurrence_others) : NULL,
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
            'treatment_given' => $tr_given,
            'disposition_after_consultation' => $r->disposition_after_consultation,
            'disposition_after_consultation_transferred_hospital' => ($r->disposition_after_consultation == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_consultation_transferred_hospital) : NULL,

            'disposition_after_admission' => $r->disposition_after_admission,
            'disposition_after_admission_transferred_hospital' => ($r->disposition_after_admission == 'TRANSFERRED TO ANOTHER HOSPITAL') ? mb_strtoupper($r->disposition_after_admission_transferred_hospital) : NULL,
            
            'date_died' => ($r->disposition_after_admission == 'DIED DURING ADMISSION') ? $r->date_died : NULL,
            'aware_healtheducation_list' => $get_hel,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ]);

        return redirect()->back()
            ->withInput()
            ->with('msg', 'Successfully updated the Patient details.')
            ->with('msgtype', 'success');
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
            'date1' => $date1,
            'date2' => $date2,
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
