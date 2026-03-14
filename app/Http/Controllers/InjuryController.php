<?php

namespace App\Http\Controllers;

use App\Imports\FhsisTbdotsImport;
use App\Imports\FireworksImport;
use App\Imports\InjuryImport;
use App\Models\DohFacility;
use App\Models\EdcsBrgy;
use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class InjuryController extends Controller
{
    public function index($code) {
        $f = DohFacility::where('sys_code1', $code)->first();

        if($f) {
            $list = Injury::where('oneiss_patfacilityno', $f->facility_name)->paginate(10);

            return view('injury_report.index', compact('f', 'list'));
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

        Excel::import(new InjuryImport($f), $r->csv_file);

        return redirect()
        ->back()
        ->with('msg', 'NEISS database was imported successfully')
        ->with('msgtype', 'success');
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
        if($code) {
            $f = DohFacility::where('sys_code1', $code)->first();
        }
        else {
            $f = DohFacility::find(auth()->user()->opdfacility->id);
        }

        $check = Injury::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()->back()->with('msg', 'This record has already been submitted.')->with('msgtype', 'warning');
        }

        $foundunique = false;

        while(!$foundunique) {
            $for_qr = Str::random(20);
            
            $search = Injury::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);

        $check = Injury::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
        ->first();

        if($check) {
            return redirect()->back()->with('msg', 'A record with the same name already exists today. Please check your entry.')->with('msgtype', 'warning');
        }

        $currentDate = Carbon::parse($r->consultation_datetime);

        if($r->filled('bdate')) {
            $birthdate = Carbon::parse($r->bdate);

            $age_years = $birthdate->diffInYears($currentDate);
            $age_months = $birthdate->diffInMonths($currentDate);
            $age_days = $birthdate->diffInDays($currentDate);
        }
        else {
            if($r->age_in == 'YEARS') {
                $age_years = $r->age_display;
                $age_months = null;
                $age_days = null;
            }
            elseif($r->age_in == 'MONTHS') {
                $age_years = null;
                $age_months = $r->age_display;
                $age_days = null;
            }
            elseif($r->age_in == 'DAYS') {
                $age_years = null;
                $age_months = null;
                $age_days = $r->age_display;
            }
        }

        //for multiple injuries
        $injury_count = 0;

        if($r->abrasion == 'Y') {
            $injury_count ++;
        }
        if($r->avulsion == 'Y') {
            $injury_count ++;
        }
        if($r->burn == 'Y') {
            $injury_count ++;
        }
        if($r->concussion == 'Y') {
            $injury_count ++;
        }
        if($r->contusion == 'Y') {
            $injury_count ++;
        }
        if($r->fracture == 'Y') {
            $injury_count ++;
        }
        if($r->open_wound == 'Y') {
            $injury_count ++;
        }
        if($r->traumatic_amputation == 'Y') {
            $injury_count ++;
        }
        if($r->others == 'Y') {
            $injury_count ++;
        }
        
        $table_params = [
            'oneiss_patfacilityno' => $f->facility_name,
            'date_report' => Carbon::parse($r->consulation_datetime)->format('Y-m-d H:i:s'),
            'facility_id' => $f->id,
            'reported_by' => $r->sys_interviewer_name,
            'reporter_contactno' => $r->sys_interviewer_contactno,
            'hosp_no' => ($r->filled('patient_no')) ? mb_strtoupper($r->patient_no) : null,
            //'registry_no',
            'hosp_cas_no' => ($r->filled('case_no')) ? mb_strtoupper($r->case_no) : null,
            'patient_type' => $r->patient_type,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : null,
            'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : null,
            'sex' => $r->sex,
            'bdate' => ($r->filled('bdate')) ? Carbon::parse($r->bdate)->format('Y-m-d') : null,
            'age_years' => $age_years,
            'age_months' => $age_months,
            'age_days' => $age_days,

            'perm_city_code' => $r->address_muncity_code,
            'perm_brgy_code' => $r->perm_brgy_code,
            'perm_streetpurok' => mb_strtoupper($r->perm_streetpurok),

            'tempaddress_sameasperm' => $r->same_address,
            'temp_city_code' => ($r->same_address == 'N') ? $r->temp_address_muncity_code : $r->address_muncity_code,
            'temp_brgy_code' => ($r->same_address == 'N') ? $r->temp_brgy_code : $r->perm_brgy_code,
            'temp_streetpurok' => ($r->same_address == 'N') ? mb_strtoupper($r->temp_streetpurok) : null,

            'contact_no' => $r->contact_no,
            'contact_no2' => $r->contact_no2,
            'philhealth' => $r->philhealth,
            'injury_city_code' => $r->injury_city_code,
            'injury_brgy_code' => ($r->filled('injury_brgy_code')) ? $r->injury_brgy_code : null,
            'injury_place' => ($r->filled('injury_place')) ? mb_strtoupper($r->injury_place) : null,

            'injury_datetime' => Carbon::parse($r->injury_datetime)->format('Y-m-d H:i:s'),
            'encounter_datetime' => Carbon::parse($r->consultation_datetime)->format('Y-m-d H:i:s'),
            'injury_intent' => $r->injury_intent,
            'firstaid_given' => $r->firstaid_given,
            'firstaid_type' => ($r->firstaid_given == 'Y') ? $r->firstaid_type : null,
            'firstaid_bywho' => ($r->firstaid_given == 'Y') ? mb_strtoupper($r->firstaid_bywho) : null,
            'multiple_injuries' => ($injury_count > 1) ? 'Y' : 'N',
            'abrasion' => ($r->abrasion == 'Y') ? 'Y' : 'N',
            'abrasion_site' => ($r->abrasion == 'Y') ? mb_strtoupper($r->abrasion_site) : null,
            'avulsion' => ($r->avulsion == 'Y') ? 'Y' : 'N',
            'avulsion_site' => ($r->avulsion == 'Y') ? mb_strtoupper($r->avulsion_site) : null,
            'burn' => ($r->burn == 'Y') ? 'Y' : 'N',
            'burn_degree' => ($r->burn == 'Y') ? $r->burn_degree : null,
            'burn_site' => ($r->burn == 'Y') ? $r->burn_site : null,
            'concussion' => ($r->concussion == 'Y') ? 'Y' : 'N',
            'concussion_site' => ($r->concussion == 'Y') ? mb_strtoupper($r->concussion_site) : null,
            'contusion' => ($r->contusion == 'Y') ? 'Y' : 'N',
            'contusion_site' => ($r->contusion == 'Y') ? mb_strtoupper($r->contusion_site) : null,
            'fracture' => ($r->fracture == 'Y') ? 'Y' : 'N',
            'fracture_open' => ($r->fracture == 'Y') ? $r->fracture_open : 'N',
            'fracture_open_site' => ($r->fracture == 'Y' && $r->fracture_open == 'Y') ? mb_strtoupper($r->fracture_open_site) : null,
            'fracture_closed' => ($r->fracture == 'Y') ? $r->fracture_closed : 'N',
            'fracture_closed_site', ($r->fracture == 'Y' && $r->fracture_closed == 'Y') ? mb_strtoupper($r->fracture_closed_site) : null,
            'open_wound' => ($r->open_wound == 'Y') ? 'Y' : 'N',
            'open_wound_site' => ($r->open_wound == 'Y') ? mb_strtoupper($r->open_wound_site) : null,
            'traumatic_amputation' => ($r->traumatic_amputation == 'Y') ? 'Y' : 'N',
            'traumatic_amputation_site' => ($r->traumatic_amputation == 'Y') ? mb_strtoupper($r->traumatic_amputation_site) : null,
            'others' => ($r->others == 'Y') ? 'Y' : 'N',
            'others_site' => ($r->others == 'Y') ? mb_strtoupper($r->others_site) : null,

            'bites_stings' => ($r->bites_stings == 'Y') ? 'Y' : 'N',
            'bites_stings_specify' => ($r->bites_stings == 'Y') ? mb_strtoupper($r->bites_stings_specify) : null,
            'ext_burns' => ($r->ext_burns == 'Y') ? 'Y' : 'N',
            'ext_burns_type' => ($r->ext_burns == 'Y') ? implode(",", $r->ext_burns_type) : null,
            'ext_burns_others_specify' => ($r->ext_burns == 'Y' && in_array('OTHERS', $r->ext_burns_type)) ? mb_strtoupper($r->ext_burns_others_specify) : null,
            'chemical_substance' => ($r->chemical_substance == 'Y') ? 'Y' : 'N',
            'chemical_substance_specify' => ($r->chemical_substance == 'Y') ? mb_strtoupper($r->chemical_substance_specify) : null,
            'contact_sharpobject' => ($r->contact_sharpobject == 'Y') ? 'Y' : 'N',
            'contact_sharpobject_specify' => ($r->contact_sharpobject == 'Y') ? mb_strtoupper($r->contact_sharpobject_specify) : null,
            'drowning' => ($r->drowning == 'Y') ? 'Y' : 'N',
            'drowning_type' => ($r->drowning == 'Y') ? implode(",", $r->drowning_type) : null,
            'drowning_other_specify' => ($r->drowning == 'Y' && in_array('OTHERS', $r->drowning_type)) ? mb_strtoupper($r->drowning_other_specify) : null,
            'exposure_forcesofnature' => ($r->exposure_forcesofnature == 'Y') ? 'Y' : 'N',
            'fall' => ($r->fall == 'Y') ? 'Y' : 'N',
            'fall_specify' => ($r->fall == 'Y') ? mb_strtoupper($r->fall_specify) : null,
            'firecracker' => ($r->firecracker == 'Y') ? 'Y' : 'N',
            'firecracker_specify' => ($r->firecracker == 'Y') ? mb_strtoupper($r->firecracker_specify) : null,
            'sexual_assault' => ($r->sexual_assault == 'Y') ? 'Y' : 'N',
            'gunshot' => ($r->gunshot == 'Y') ? 'Y' : 'N',
            'gunshot_specifyweapon' => ($r->gunshot == 'Y') ? mb_strtoupper($r->gunshot_specifyweapon) : null,
            'hanging_strangulation' => ($r->hanging_strangulation == 'Y') ? 'Y' : 'N',
            'mauling_assault' => ($r->hanging_strangulation == 'Y') ? 'Y' : 'N',
            'transport_vehicular_accident' => ($r->transport_vehicular_accident == 'Y') ? 'Y' : 'N',
            'ext_others' => ($r->ext_others == 'Y') ? 'Y' : 'N',
            'ext_others_specify' => ($r->ext_others == 'Y') ? mb_strtoupper($r->ext_others_specify) : null,

            'transfer_hospital' => $r->transfer_hospital,
            'referred_hospital' => $r->referred_hospital,
            'orig_hospital' => ($r->transfer_hospital == 'Y' || $r->referred_hospital == 'Y') ? mb_strtoupper($r->orig_hospital) : null,
            'orig_physician' => ($r->transfer_hospital == 'Y' || $r->referred_hospital == 'Y') ? mb_strtoupper($r->orig_physician) : null,
            'status_reachingfacility' => $r->status_reachingfacility,
            'ifalive_type' => ($r->status_reachingfacility == 'ALIVE') ? $r->ifalive_type : null,
            'modeof_transport' => $r->modeof_transport,
            'modeof_transport_others' => ($r->modeof_transport == 'OTHERS') ? mb_strtoupper($r->modeof_transport_others) : null,
            'initial_impression' => ($r->filled('initial_impression')) ? mb_strtoupper($r->initial_impression) : null,
            'icd10_nature' => ($r->filled('icd10_nature')) ? mb_strtoupper($r->icd10_nature) : null,
            'icd10_external' => ($r->filled('icd10_external')) ? mb_strtoupper($r->icd10_external) : null,
            'disposition' => $r->disposition,
            'disposition_transferred' => ($r->disposition == 'TRANSFERRED TO ANOTHER FACILITY/HOSPITAL') ? mb_strtoupper($r->disposition_transferred) : null,
            'outcome' => $r->outcome,
            
            'remarks' => $r->remarks,
            'qr' => $for_qr,

            'created_by' => (Auth::check()) ? Auth::id() : null,
            'report_year' => $currentDate->year,
            'report_month' => $currentDate->month,
            'report_week' => $currentDate->format('W'),
            'request_uuid' => $r->request_uuid,
        ];

        if($r->transport_vehicular_accident == 'Y') {
            $table_params = $table_params + [
                'vehicle_type' => $r->vehicle_type,
                'collision_type' => $r->collision_type,
                'patients_vehicle_involved' => $r->patients_vehicle_involved,
                'patients_vehicle_involved_others' => ($r->patients_vehicle_involved == 'OTHERS') ? mb_strtoupper($r->patients_vehicle_involved_others) : null,
                'other_vehicle_involved' => ($r->collision_type == 'COLLISION') ? $r->other_vehicle_involved : null,
                'other_vehicle_involved_others' => ($r->collision_type == 'COLLISION' && $r->other_vehicle_involved == 'OTHERS') ? mb_strtoupper($r->other_vehicle_involved_others) : null,
                'patient_position' => $r->patient_position,
                'patient_position_others' => ($r->patient_position == 'OTHERS') ? mb_strtoupper($r->patient_position_others) : null,
                'placeof_occurrence' => $r->placeof_occurrence,
                'placeof_occurrence_workplace_specify' => ($r->placeof_occurrence == 'WORKPLACE') ? mb_strtoupper($r->placeof_occurrence_workplace_specify) : null,
                'placeof_occurrence_others_specify' => ($r->placeof_occurrence == 'OTHERS') ? mb_strtoupper($r->placeof_occurrence_workplace_specify) : null,
                'activitypatient_duringincident' => (!empty($r->activitypatient_duringincident)) ? implode(',', $r->activitypatient_duringincident) : null,
                'act_others' => (in_array('OTHERS', $r->activitypatient_duringincident)) ? mb_strtoupper($r->act_others) : null, 
                'otherrisk_factors' => (!empty($r->otherrisk_factors)) ? implode(',', $r->otherrisk_factors) : null,
                'oth_factors_specify' => (in_array('OTHERS', $r->otherrisk_factors)) ? mb_strtoupper($r->oth_factors_specify) : null, 
                'safety' => (!empty($r->safety)) ? implode(',', $r->safety) : null,
                'safety_others' => (in_array('OTHERS', $r->safety)) ? mb_strtoupper($r->safety_others) : null,
            ];
        }

        if($r->disposition == 'ADMITTED') {
            $table_params = $table_params + [
                'inp_completefinal_diagnosis' => $r->inp_completefinal_diagnosis,
                'inp_disposition' => $r->inp_disposition,
                'inp_disposition_others' => ($r->inp_disposition == 'OTHERS') ? mb_strtoupper($r->inp_disposition_others) : null,
                'inp_disposition_transferred' => ($r->inp_disposition == 'TRANSFERRED TO ANOTHER FACILITY/HOSPITAL') ? mb_strtoupper($r->inp_disposition_transferred) : null,
                'inp_outcome' => $r->inp_outcome,
                'inp_icd10_nature' => $r->inp_icd10_nature,
                'inp_icd10_external' => $r->inp_icd10_external,
                'comments' => $r->comments,
            ];
        }

        $c = Injury::create($table_params);

        return redirect()->route('facility_report_injury_index', $f->sys_code1)
        ->with('msg', 'Injury Form successfully encoded to the system.')
        ->with('msgtype', 'success');
    }
}
