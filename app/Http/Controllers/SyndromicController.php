<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SyndromicDoctor;
use App\Models\SyndromicPatient;
use App\Models\SyndromicRecords;
use Illuminate\Support\Facades\DB;

class SyndromicController extends Controller
{
    public function index() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('ITR_BRGY_ADMIN', $plist) || in_array('ITR_BRGY_ENCODER', $plist)) {
            $uv = SyndromicRecords::where('brgy_verified', 0)
            ->whereHas('syndromic_patient', function ($q) {
                $q->where('address_brgy_text', auth()->user()->brgy->brgyName)
                ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                ->where('address_province_text', auth()->user()->brgy->city->province->provinceName);
            })
            ->orderBy('created_at', 'DESC')
            ->get();

            $v = SyndromicRecords::where('brgy_verified', 1)
            ->whereHas('syndromic_patient', function ($q) {
                $q->where('address_brgy_text', auth()->user()->brgy->brgyName)
                ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                ->where('address_province_text', auth()->user()->brgy->city->province->provinceName);
            })
            ->orderBy('brgy_verified_date', 'DESC')
            ->get();
        }
        else {
            $uv = SyndromicRecords::where('brgy_verified', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

            $v = SyndromicRecords::where('brgy_verified', 1)
            ->orderBy('brgy_verified_date', 'DESC')
            ->get();
        }
        
        return view('syndromic.home', [
            'uv' => $uv,
            'v' => $v,
        ]);
    }

    public function newPatient() {
        $lname = request()->input('lname');
        $fname = request()->input('fname');
        $bdate = request()->input('bdate');
        
        $mname = request()->input('mname');

        //new method of checking duplicate before storing records
        $s = SyndromicPatient::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
        ->whereDate('bdate', $bdate);

        if(request()->input('mname')) {
            $getname = $lname.', '.$fname.' '.$mname;

            $s = $s->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)));
        }
        else {
            $getname = $lname.', '.$fname;
        }

        if(request()->input('suffix')) {
            $suffix = request()->input('suffix');
            $getname = $getname.' '.$suffix;

            $s = $s->where('suffix', $suffix)->first();
        }
        else {
            $s = $s->first();
        }

        if($s) {
            return redirect()->back()
            ->with('msg', 'Patient ('.$getname.') already exists.')
            ->with('msgtype', 'warning');
        }
        else {
            //getAge
            $cbdate = Carbon::parse($bdate);
            $getage = $cbdate->diffInYears(Carbon::now());
            
            return view('syndromic.new_patient', [
                'getage' => $getage,
            ]);
        }
    }

    public function storePatient(Request $request) {

        if(date('n') == 1) {
            $sc = 'A';
        }
        else if(date('n') == 2) {
            $sc = 'B';
        }
        else if(date('n') == 3) {
            $sc = 'C';
        }
        else if(date('n') == 4) {
            $sc = 'D';
        }
        else if(date('n') == 5) {
            $sc = 'E';
        }
        else if(date('n') == 6) {
            $sc = 'F';
        }
        else if(date('n') == 7) {
            $sc = 'G';
        }
        else if(date('n') == 8) {
            $sc = 'H';
        }
        else if(date('n') == 9) {
            $sc = 'I';
        }
        else if(date('n') == 10) {
            $sc = 'J';
        }
        else if(date('n') == 11) {
            $sc = 'K';
        }
        else if(date('n') == 12) {
            $sc = 'L';
        }
        
        $foundunique = false;

        while(!$foundunique) {
            $qr = date('Y').'-'.$sc.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).chr(mt_rand(65, 90)).chr(mt_rand(65, 90));

            $search = SyndromicPatient::where('qr', $qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }

        $c = $request->user()->syndromicpatient()->create([
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
            'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
            'bdate' => $request->bdate,
            'gender' => $request->gender,
            'cs' => $request->cs,
            'spouse_name' => ($request->cs == 'MARRIED') ? $request->spouse_name : NULL,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'contact_number2' => $request->contact_number2,

            'mother_name' => $request->mother_name,
            'father_name' => $request->father_name,

            'address_region_code' => $request->address_region_code,
            'address_region_text' => $request->address_region_text,
            'address_province_code' => $request->address_province_code,
            'address_province_text' => $request->address_province_text,
            'address_muncity_code' => $request->address_muncity_code,
            'address_muncity_text' => $request->address_muncity_text,
            'address_brgy_code' => $request->address_brgy_text,
            'address_brgy_text' => $request->address_brgy_text,
            'address_street' => mb_strtoupper($request->address_street),
            'address_houseno' => mb_strtoupper($request->address_houseno),

            'ifminor_resperson' => ($request->filled('ifminor_resperson')) ? mb_strtoupper($request->ifminor_resperson) : NULL,
            'ifminor_resrelation' => ($request->filled('ifminor_resrelation')) ? mb_strtoupper($request->ifminor_resrelation) : NULL,

            'qr' => $qr,
        ]);

        return redirect()->route('syndromic_newRecord', $c->id)
        ->with('msg', 'Patient record successfully created. Proceed by completing the ITR of the patient.')
        ->with('msgtype', 'success');
    }

    public function newRecord($patient_id) {
        $patient = SyndromicPatient::findOrFail($patient_id);

        $doclist = SyndromicDoctor::get();

        //check if record exist today
        $check = SyndromicRecords::where('syndromic_patient_id', $patient->id)
        ->whereDate('created_at', date('Y-m-d'))
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'ITR already created today for the patient.')
            ->with('msgtype', 'warning');
        }
        else {
            return view('syndromic.new_record', [
                'patient' => $patient,
                'doclist' => $doclist,
            ]);
        }
    }

    public function storeRecord($patient_id, Request $r) {
        $p = SyndromicPatient::findOrFail($patient_id);

        $birthdate = Carbon::parse($p->bdate);
        $currentDate = Carbon::parse(date('Y-m-d'));

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        //opd number generator

        $patient_yearcount = SyndromicRecords::whereYear('created_at', date('Y'))->count() + 1;
        
        $getopd_num = date('Y').'-'.$patient_yearcount;

        $check1 = SyndromicRecords::where('syndromic_patient_id', $p->id)
        ->whereDate('created_at', date('Y-m-d'))
        ->first();

        $foundunique = false;
        while(!$foundunique) {
            $for_qr = mb_strtoupper(Str::random(6));
            
            $search = SyndromicRecords::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }
        
        //permission check
        $perm_list = explode(",", auth()->user()->permission_list);

        if(!$check1) {
            $c = $r->user()->syndromicrecord()->create([
                'chief_complain' => mb_strtoupper($r->chief_complain),
                'syndromic_patient_id' => $p->id,
                'opdno' => $getopd_num,
                'consultation_date' => $r->consultation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => $r->bloodpressure,
                'weight' => $r->weight,
                'respiratoryrate' => $r->respiratoryrate,
                'pulserate' => $r->pulserate,
                'saturationperioxigen' => $r->saturationperioxigen,
                'fever' => ($r->fever_yn) ? 1 : 0,
                'fever_onset' => ($r->fever_yn) ? $r->fever_onset : NULL,
                'fever_remarks' => ($r->fever_yn) ? $r->fever_remarks : NULL,
                'rash' => ($r->rash_yn) ? 1 : 0,
                'rash_onset' => ($r->rash_yn) ? $r->rash_onset : NULL,
                'rash_remarks' => ($r->rash_yn) ? $r->rash_remarks : NULL,
                'cough' => ($r->cough_yn) ? 1 : 0,
                'cough_onset' => ($r->cough_yn) ? $r->cough_onset : NULL,
                'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
                'colds' => ($r->colds_yn) ? 1 : 0,
                'colds_onset' => ($r->colds_yn) ? $r->colds_onset : NULL,
                'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
                'conjunctivitis' => ($r->conjunctivitis_yn) ? 1 : 0,
                'conjunctivitis_onset' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_onset : NULL,
                'conjunctivitis_remarks' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_remarks : NULL,
                'mouthsore' => ($r->mouthsore_yn) ? 1 : 0,
                'mouthsore_onset' => ($r->mouthsore_yn) ? $r->mouthsore_onset : NULL,
                'mouthsore_remarks' => ($r->mouthsore_yn) ? $r->mouthsore_remarks : NULL,
                'sorethroat' => ($r->sorethroat_yn) ? 1 : 0,
                'sorethroat_onset' => ($r->sorethroat_yn) ? $r->sorethroat_onset : NULL,
                'sorethroat_remarks' => ($r->sorethroat_yn) ? $r->sorethroat_remarks : NULL,
                'lossoftaste' => ($r->lossoftaste_yn) ? 1 : 0,
                'lossoftaste_onset' => ($r->lossoftaste_yn) ? $r->lossoftaste_onset : NULL,
                'lossoftaste_remarks' => ($r->lossoftaste_yn) ? $r->lossoftaste_remarks : NULL,
                'lossofsmell' => ($r->lossofsmell_yn) ? 1 : 0,
                'lossofsmell_onset' => ($r->lossofsmell_yn) ? $r->lossofsmell_onset : NULL,
                'lossofsmell_remarks' => ($r->lossofsmell_yn) ? $r->lossofsmell_remarks : NULL,
                'headache' => ($r->headache_yn) ? 1 : 0,
                'headache_onset' => ($r->headache_yn) ? $r->headache_onset : NULL,
                'headache_remarks' => ($r->headache_yn) ? $r->headache_remarks : NULL,
                'jointpain' => ($r->jointpain_yn) ? 1 : 0,
                'jointpain_onset' => ($r->jointpain_yn) ? $r->jointpain_onset : NULL,
                'jointpain_remarks' => ($r->jointpain_yn) ? $r->jointpain_remarks : NULL,
                'musclepain' => ($r->musclepain_yn) ? 1 : 0,
                'musclepain_onset' => ($r->musclepain_yn) ? $r->musclepain_onset : NULL,
                'musclepain_remarks' => ($r->musclepain_yn) ? $r->musclepain_remarks : NULL,
                'diarrhea' => ($r->diarrhea_yn) ? 1 : 0,
                'bloody_stool' => ($r->diarrhea_yn && $r->bloody_stool) ? 1 : 0,
                'diarrhea_onset' => ($r->diarrhea_yn) ? $r->diarrhea_onset : NULL,
                'diarrhea_remarks' => ($r->diarrhea_yn) ? $r->diarrhea_remarks : NULL,
                'abdominalpain' => ($r->abdominalpain_yn) ? 1 : 0,
                'abdominalpain_onset' => ($r->abdominalpain_yn) ? $r->abdominalpain_onset : NULL,
                'abdominalpain_remarks' => ($r->abdominalpain_yn) ? $r->abdominalpain_remarks : NULL,
                'vomiting' => ($r->vomiting_yn) ? 1 : 0,
                'vomiting_onset' => ($r->vomiting_yn) ? $r->vomiting_onset : NULL,
                'vomiting_remarks' => ($r->vomiting_yn) ? $r->vomiting_remarks : NULL,
                'weaknessofextremities' => ($r->weaknessofextremities_yn) ? 1 : 0,
                'weaknessofextremities_onset' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_onset : NULL,
                'weaknessofextremities_remarks' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_remarks : NULL,
                'paralysis' => ($r->paralysis_yn) ? 1 : 0,
                'paralysis_onset' => ($r->paralysis_yn) ? $r->paralysis_onset : NULL,
                'paralysis_remarks' => ($r->paralysis_yn) ? $r->paralysis_remarks : NULL,
                'alteredmentalstatus' => ($r->alteredmentalstatus_yn) ? 1 : 0,
                'alteredmentalstatus_onset' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_onset : NULL,
                'alteredmentalstatus_remarks' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_remarks : NULL,
                'animalbite' => ($r->animalbite_yn) ? 1 : 0,
                'animalbite_onset' => ($r->animalbite_yn) ? $r->animalbite_onset : NULL,
                'animalbite_remarks' => ($r->animalbite_yn) ? $r->animalbite_remarks : NULL,
                'anorexia' => ($r->anorexia_yn) ? 1 : 0,
                'anorexia_onset' => ($r->anorexia_yn) ? $r->anorexia_onset : NULL,
                'anorexia_remarks' => ($r->anorexia_yn) ? $r->anorexia_remarks : NULL,
                'jaundice' => ($r->jaundice_yn) ? 1 : 0,
                'jaundice_onset' => ($r->jaundice_yn) ? $r->jaundice_onset : NULL,
                'jaundice_remarks' => ($r->jaundice_yn) ? $r->jaundice_remarks : NULL,
                'nausea' => ($r->nausea_yn) ? 1 : 0,
                'nausea_onset' => ($r->nausea_yn) ? $r->nausea_onset : NULL,
                'nausea_remarks' => ($r->nausea_yn) ? $r->nausea_remarks : NULL,
                'fatigue' => ($r->fatigue_yn) ? 1 : 0,
                'fatigue_onset' => ($r->fatigue_yn) ? $r->fatigue_onset : NULL,
                'fatigue_remarks' => ($r->fatigue_yn) ? $r->fatigue_remarks : NULL,
                'dyspnea' => ($r->dyspnea_yn) ? 1 : 0,
                'dyspnea_onset' => ($r->dyspnea_yn) ? $r->dyspnea_onset : NULL,
                'dyspnea_remarks' => ($r->dyspnea_yn) ? $r->dyspnea_remarks : NULL,
                'other_symptoms' => ($r->other_symptoms_yn) ? 1 : 0,
                'other_symptoms_onset' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset : NULL,
                'other_symptoms_onset_remarks' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset_remarks : NULL,

                'is_hospitalized' => ($r->is_hospitalized == 'Y') ? 1 : 0,
                'date_admitted' => ($r->is_hospitalized == 'Y') ? $r->date_admitted : NULL,
                'date_released' => ($r->is_hospitalized == 'Y') ? $r->date_released : NULL,

                'outcome' => $r->outcome,
                'outcome_recovered_date' => ($r->outcome == 'RECOVERED') ? $r->outcome_recovered_date : NULL,
                'outcome_died_date' => ($r->outcome == 'DIED') ? $r->outcome_died_date : NULL,

                'bigmessage' => $r->bigmessage,
                'status' => 'approved',
                'name_of_physician' => $r->name_of_physician,
                'dru_name'=> SyndromicDoctor::where('doctor_name', $r->name_of_physician)->first()->dru_name,

                'brgy_verified' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? 1 : 0,
                'brgy_verified_date' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? date('Y-m-d H:i:s') : NULL,
                'brgy_verified_by' => (in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) ? auth()->user()->id : NULL,

                'cesu_verified' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? 1 : 0,
                'cesu_verified_date' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? date('Y-m-d H:i:s') : NULL,
                'cesu_verified_by' => (in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) ? auth()->user()->id : NULL,
    
                'age_years' => $get_ageyears,
                'age_months' => $get_agemonths,
                'age_days' => $get_agedays,

                'qr' => $for_qr,
            ]);
    
            return redirect()->route('syndromic_home')
            ->with('msg', 'Record successfully created.')
            ->with('msgtype', 'success');
        }
        else {
            
        }        
    }

    public function viewPatient($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        return view('syndromic.edit_patient', [
            'd' => $d,
        ]);
    }

    public function updatePatient($patient_id, Request $request) {
        $u = SyndromicPatient::where('id', $patient_id)
        ->update([
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
            'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
            'bdate' => $request->bdate,
            'gender' => $request->gender,
            'cs' => $request->cs,
            'spouse_name' => ($request->cs == 'MARRIED') ? $request->spouse_name : NULL,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'contact_number2' => $request->contact_number2,

            'mother_name' => $request->mother_name,
            'father_name' => $request->father_name,

            'address_region_code' => $request->address_region_code,
            'address_region_text' => $request->address_region_text,
            'address_province_code' => $request->address_province_code,
            'address_province_text' => $request->address_province_text,
            'address_muncity_code' => $request->address_muncity_code,
            'address_muncity_text' => $request->address_muncity_text,
            'address_brgy_code' => $request->address_brgy_text,
            'address_brgy_text' => $request->address_brgy_text,
            'address_street' => mb_strtoupper($request->address_street),
            'address_houseno' => mb_strtoupper($request->address_houseno),

            'ifminor_resperson' => ($request->filled('ifminor_resperson')) ? mb_strtoupper($request->ifminor_resperson) : NULL,
            'ifminor_resrelation' => ($request->filled('ifminor_resrelation')) ? mb_strtoupper($request->ifminor_resrelation) : NULL,
        ]);

        
    }

    public function viewRecord($record_id) {
        $r = SyndromicRecords::findOrFail($record_id);
        $doclist = SyndromicDoctor::get();

        if($r->canAccessRecord()) {
            return view('syndromic.edit_record', [
                'd' => $r,
                'doclist' => $doclist,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updateRecord($record_id, Request $r) {
        $d = SyndromicRecords::findOrFail($record_id);

        $perm_list = explode(",", auth()->user()->permission_list);
        
        if($r->submit == 'update') {
            $u = SyndromicRecords::where('id', $d->id)
            ->update([
                'chief_complain' => mb_strtoupper($r->chief_complain),
                'consultation_date' => $r->consultation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => $r->bloodpressure,
                'weight' => $r->weight,
                'respiratoryrate' => $r->respiratoryrate,
                'pulserate' => $r->pulserate,
                'saturationperioxigen' => $r->saturationperioxigen,
                'fever' => ($r->fever_yn) ? 1 : 0,
                'fever_onset' => ($r->fever_yn) ? $r->fever_onset : NULL,
                'fever_remarks' => ($r->fever_yn) ? $r->fever_remarks : NULL,
                'rash' => ($r->rash_yn) ? 1 : 0,
                'rash_onset' => ($r->rash_yn) ? $r->rash_onset : NULL,
                'rash_remarks' => ($r->rash_yn) ? $r->rash_remarks : NULL,
                'cough' => ($r->cough_yn) ? 1 : 0,
                'cough_onset' => ($r->cough_yn) ? $r->cough_onset : NULL,
                'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
                'colds' => ($r->colds_yn) ? 1 : 0,
                'colds_onset' => ($r->colds_yn) ? $r->colds_onset : NULL,
                'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
                'conjunctivitis' => ($r->conjunctivitis_yn) ? 1 : 0,
                'conjunctivitis_onset' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_onset : NULL,
                'conjunctivitis_remarks' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_remarks : NULL,
                'mouthsore' => ($r->mouthsore_yn) ? 1 : 0,
                'mouthsore_onset' => ($r->mouthsore_yn) ? $r->mouthsore_onset : NULL,
                'mouthsore_remarks' => ($r->mouthsore_yn) ? $r->mouthsore_remarks : NULL,
                'sorethroat' => ($r->sorethroat_yn) ? 1 : 0,
                'sorethroat_onset' => ($r->sorethroat_yn) ? $r->sorethroat_onset : NULL,
                'sorethroat_remarks' => ($r->sorethroat_yn) ? $r->sorethroat_remarks : NULL,
                'lossoftaste' => ($r->lossoftaste_yn) ? 1 : 0,
                'lossoftaste_onset' => ($r->lossoftaste_yn) ? $r->lossoftaste_onset : NULL,
                'lossoftaste_remarks' => ($r->lossoftaste_yn) ? $r->lossoftaste_remarks : NULL,
                'lossofsmell' => ($r->lossofsmell_yn) ? 1 : 0,
                'lossofsmell_onset' => ($r->lossofsmell_yn) ? $r->lossofsmell_onset : NULL,
                'lossofsmell_remarks' => ($r->lossofsmell_yn) ? $r->lossofsmell_remarks : NULL,
                'headache' => ($r->headache_yn) ? 1 : 0,
                'headache_onset' => ($r->headache_yn) ? $r->headache_onset : NULL,
                'headache_remarks' => ($r->headache_yn) ? $r->headache_remarks : NULL,
                'jointpain' => ($r->jointpain_yn) ? 1 : 0,
                'jointpain_onset' => ($r->jointpain_yn) ? $r->jointpain_onset : NULL,
                'jointpain_remarks' => ($r->jointpain_yn) ? $r->jointpain_remarks : NULL,
                'musclepain' => ($r->musclepain_yn) ? 1 : 0,
                'musclepain_onset' => ($r->musclepain_yn) ? $r->musclepain_onset : NULL,
                'musclepain_remarks' => ($r->musclepain_yn) ? $r->musclepain_remarks : NULL,
                'diarrhea' => ($r->diarrhea_yn) ? 1 : 0,
                'bloody_stool' => ($r->diarrhea_yn && $r->bloody_stool) ? 1 : 0,
                'diarrhea_onset' => ($r->diarrhea_yn) ? $r->diarrhea_onset : NULL,
                'diarrhea_remarks' => ($r->diarrhea_yn) ? $r->diarrhea_remarks : NULL,
                'abdominalpain' => ($r->abdominalpain_yn) ? 1 : 0,
                'abdominalpain_onset' => ($r->abdominalpain_yn) ? $r->abdominalpain_onset : NULL,
                'abdominalpain_remarks' => ($r->abdominalpain_yn) ? $r->abdominalpain_remarks : NULL,
                'vomiting' => ($r->vomiting_yn) ? 1 : 0,
                'vomiting_onset' => ($r->vomiting_yn) ? $r->vomiting_onset : NULL,
                'vomiting_remarks' => ($r->vomiting_yn) ? $r->vomiting_remarks : NULL,
                'weaknessofextremities' => ($r->weaknessofextremities_yn) ? 1 : 0,
                'weaknessofextremities_onset' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_onset : NULL,
                'weaknessofextremities_remarks' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_remarks : NULL,
                'paralysis' => ($r->paralysis_yn) ? 1 : 0,
                'paralysis_onset' => ($r->paralysis_yn) ? $r->paralysis_onset : NULL,
                'paralysis_remarks' => ($r->paralysis_yn) ? $r->paralysis_remarks : NULL,
                'alteredmentalstatus' => ($r->alteredmentalstatus_yn) ? 1 : 0,
                'alteredmentalstatus_onset' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_onset : NULL,
                'alteredmentalstatus_remarks' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_remarks : NULL,
                'animalbite' => ($r->animalbite_yn) ? 1 : 0,
                'animalbite_onset' => ($r->animalbite_yn) ? $r->animalbite_onset : NULL,
                'animalbite_remarks' => ($r->animalbite_yn) ? $r->animalbite_remarks : NULL,
                'anorexia' => ($r->anorexia_yn) ? 1 : 0,
                'anorexia_onset' => ($r->anorexia_yn) ? $r->anorexia_onset : NULL,
                'anorexia_remarks' => ($r->anorexia_yn) ? $r->anorexia_remarks : NULL,
                'jaundice' => ($r->jaundice_yn) ? 1 : 0,
                'jaundice_onset' => ($r->jaundice_yn) ? $r->jaundice_onset : NULL,
                'jaundice_remarks' => ($r->jaundice_yn) ? $r->jaundice_remarks : NULL,
                'nausea' => ($r->nausea_yn) ? 1 : 0,
                'nausea_onset' => ($r->nausea_yn) ? $r->nausea_onset : NULL,
                'nausea_remarks' => ($r->nausea_yn) ? $r->nausea_remarks : NULL,
                'fatigue' => ($r->fatigue_yn) ? 1 : 0,
                'fatigue_onset' => ($r->fatigue_yn) ? $r->fatigue_onset : NULL,
                'fatigue_remarks' => ($r->fatigue_yn) ? $r->fatigue_remarks : NULL,
                'dyspnea' => ($r->dyspnea_yn) ? 1 : 0,
                'dyspnea_onset' => ($r->dyspnea_yn) ? $r->dyspnea_onset : NULL,
                'dyspnea_remarks' => ($r->dyspnea_yn) ? $r->dyspnea_remarks : NULL,
                'other_symptoms' => ($r->other_symptoms_yn) ? 1 : 0,
                'other_symptoms_onset' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset : NULL,
                'other_symptoms_onset_remarks' => ($r->other_symptoms_yn) ? $r->other_symptoms_onset_remarks : NULL,
                
                'is_hospitalized' => ($r->is_hospitalized == 'Y') ? 1 : 0,
                'date_admitted' => ($r->is_hospitalized == 'Y') ? $r->date_admitted : NULL,
                'date_released' => ($r->is_hospitalized == 'Y') ? $r->date_released : NULL,

                'outcome' => $r->outcome,
                'outcome_recovered_date' => ($r->outcome == 'RECOVERED') ? $r->outcome_recovered_date : NULL,
                'outcome_died_date' => ($r->outcome == 'DIED') ? $r->outcome_died_date : NULL,

                'bigmessage' => $r->bigmessage,
                'status' => 'approved',
                'name_of_physician' => $r->name_of_physician,
                'dru_name'=> SyndromicDoctor::where('doctor_name', $r->name_of_physician)->first()->dru_name,

                'updated_by' => auth()->user()->id,
            ]);

            $msg = 'Record was updated successfully';
        }
        else if($r->submit == "verify_cesu") {
            if($d->cesu_verified == 0) {
                if(in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) {
                    $d->cesu_verified = 1;
                    $d->cesu_verified_date = date('Y-m-d H:i:s');
                    $d->cesu_verified_by = auth()->user()->id;

                    $d->save();
                }
            }

            $msg = 'Record was marked verified by CESU successfully';
        }
        else if($r->submit == "verify_brgy") {

            if($d->brgy_verified == 0) {
                if(in_array('ITR_BRGY_ADMIN', $perm_list) || in_array('ITR_BRGY_ENCODER', $perm_list)) {
                    $d->brgy_verified = 1;
                    $d->brgy_verified_date = date('Y-m-d H:i:s');
                    $d->brgy_verified_by = auth()->user()->id;
    
                    $d->save();
                }
            }

            $msg = 'Record was marked verified by Barangay successfully';
        }

        return redirect()->back()
        ->with('msg', $msg)
        ->with('msgtype', 'success');
    }

    public function generateMedCert($record_id) {

    }

    public function medcertOnlineVerify($record_id) {

    }

    public function createLabResult($record_id) {

    }

    public function storeLabResult($record_id) {

    }

    public function diseasemap() {
        //fetch brgy
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->orderBy('brgyName', 'ASC')
        ->get();
        
        return view('syndromic.mapdashboard', [
            'brgy' => $brgy_list,
        ]);
    }

    public function viewDiseaseList() {

    }

    public function walkin_part1() {
        
    }

    public function walkin_part2() {

    }

    public function walkin_part3(Request $r) {

    }
}
