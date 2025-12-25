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
        $table_params = [
            'facility_id',
            'reported_by',
            'reporter_contactno',

            'patient_no',
            'registry_no',
            'case_no',
            'patient_type',

            'lname',
            'fname',
            'mname',
            'suffix',

            'sex',
            'bdate',
            'age_years',
            'age_months',
            'age_days',

            'perm_streetpurok',
            'perm_brgy_code',
            'tempaddress_sameasperm',
            'temp_streetpurok',
            'temp_brgy_code',
            
            'contact_no',
            'philhealth',

            'injury_city_code',
            'injury_datetime',
            'consultation_datetime',

            'injury_intent',
            'firstaid_given',
            'firstaid_type',
            'firstaid_bywho',
            'multiple_injuries',

            'abrasion',
            'abrasion_site',
            'avulsion',
            'avulsion_site',
            'burn',
            'burn_degree',
            'burn_site',
            'concussion',
            'concussion_site',
            'contusion',
            'contusion_site',
            'fracture',
            'fracture_open',
            'fracture_open_site',
            'fracture_closed',
            'fracture_closed_site',
            'open_wound',
            'open_wound_site',
            'traumatic_amputation',
            'traumatic_amputation_site',
            'others',
            'others_site',

            'bites_stings',
            'bites_stings_specify',
            'ext_burns',
            'ext_burns_type',
            'ext_burns_others_specify',
            'chemical_substance',
            'chemical_substance_specify',
            'contact_sharpobject',
            'contact_sharpobject_specify',
            'drowning',
            'drowning_type',
            'drowning_other_specify',
            'exposure_forcesofnature',
            'fall',
            'fall_specify',
            'firecracker',
            'firecracker_specify',
            'sexual_assault',
            'gunshot',
            'gunshot_specifyweapon',
            'hanging_strangulation',
            'mauling_assault',
            'transport_vehicular_accident',
            'ext_others',
            'ext_others_specify',

            'vehicle_type',
            'collision_type',
            'patients_vehicle_involved',
            'patients_vehicle_involved_others',
            'other_vehicle_involved',
            'other_vehicle_involved_others',
            'patient_position',
            'patient_position_others',
            'placeof_occurrence',
            'placeof_occurrence_workplace_specify',
            'placeof_occurrence_others_specify',
            'activitypatient_duringincident',
            'activitypatient_duringincident_others',
            'otherrisk_factors',
            'otherrisk_factors_others',
            'safety',
            'safety_others',

            'transferred_fromanotherhospital',
            'referredby_anotherhospital',
            'originating_physician',
            'status_reachingfacility',
            'status_ifalive_type',
            'modeof_transport',
            'modeof_transport_others',
            'initial_impression',
            'icd10_nature',
            'icd10_external',
            'disposition',
            'disposition_transferred',
            'outcome',

            'inp_completefinal_diagnosis',
            'inp_disposition',
            'inp_disposition_others',
            'inp_disposition_transferred',
            'inp_outcome',
            'inp_icd10_nature',
            'inp_icd10_external',

            'comments',
            'created_by',
            'report_year',
            'report_month',
            'report_week',
        ];
    }
}
