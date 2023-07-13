<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SyndromicPatient;
use App\Models\SyndromicRecords;
use Illuminate\Support\Facades\DB;

class SyndromicController extends Controller
{
    public function index() {
        if(auth()->user()->isAdmin == 1) {

        }

        return view('syndromic.home');
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
            $getage = $getname.' '.$suffix;

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

        return view('syndromic.new_record', [
            'patient' => $patient,
        ]);
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

        if(!$check1) {
            $c = $r->user()->syndromicrecord()->create([
                'chief_complain' => $r->chief_complain,
                'syndromic_patient_id' => $p->id,
                'opdno' => $getopd_num,
                'consulation_date' => $r->consulation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => $r->bloodpressure,
                'weight' => $r->weight,
                'respiratoryrate' => $r->bloodpressure,
                'pulserate' => $r->pulserate,
                'saturationperioxigen' => $r->saturationperioxigen,
                'fever' => ($r->fever_yn) ? 1 : 0,
                'fever_remarks' => ($r->fever_yn) ? $r->fever_remarks : NULL,
                'rash' => ($r->rash_yn) ? 1 : 0,
                'rash_remarks' => ($r->rash_yn) ? $r->rash_remarks : NULL,
                'cough' => ($r->cough_yn) ? 1 : 0,
                'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
                'colds' => ($r->colds_yn) ? 1 : 0,
                'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
                'conjunctivitis' => ($r->conjunctivitis_yn) ? 1 : 0,
                'conjunctivitis_remarks' => ($r->conjunctivitis_yn) ? $r->conjunctivitis_remarks : NULL,
                'mouthsore' => ($r->mouthsore_yn) ? 1 : 0,
                'mouthsore_remarks' => ($r->mouthsore_yn) ? $r->mouthsore_remarks : NULL,
                'lossoftaste' => ($r->lossoftaste_yn) ? 1 : 0,
                'lossoftaste_remarks' => ($r->lossoftaste_yn) ? $r->lossoftaste_remarks : NULL,
                'lossofsmell' => ($r->lossofsmell_yn) ? 1 : 0,
                'lossofsmell_remarks' => ($r->lossofsmell_yn) ? $r->lossofsmell_remarks : NULL,
                'headache' => ($r->headache_yn) ? 1 : 0,
                'headache_remarks' => ($r->headache_yn) ? $r->headache_remarks : NULL,
                'jointpain' => ($r->jointpain_yn) ? 1 : 0,
                'jointpain_remarks' => ($r->jointpain_yn) ? $r->jointpain_remarks : NULL,
                'musclepain' => ($r->musclepain_yn) ? 1 : 0,
                'musclepain_remarks' => ($r->musclepain_yn) ? $r->musclepain_remarks : NULL,
                'diarrhea' => ($r->diarrhea_yn) ? 1 : 0,
                'diarrhea_remarks' => ($r->diarrhea_yn) ? $r->diarrhea_remarks : NULL,
                'abdominalpain' => ($r->abdominalpain_yn) ? 1 : 0,
                'abdominalpain_remarks' => ($r->abdominalpain_yn) ? $r->abdominalpain_remarks : NULL,
                'vomiting' => ($r->vomiting_yn) ? 1 : 0,
                'vomiting_remarks' => ($r->vomiting_yn) ? $r->vomiting_remarks : NULL,
                'weaknessofextremities' => ($r->weaknessofextremities_yn) ? 1 : 0,
                'weaknessofextremities_remarks' => ($r->weaknessofextremities_yn) ? $r->weaknessofextremities_remarks : NULL,
                'paralysis' => ($r->paralysis_yn) ? 1 : 0,
                'paralysis_remarks' => ($r->paralysis_yn) ? $r->paralysis_remarks : NULL,
                'alteredmentalstatus' => ($r->alteredmentalstatus_yn) ? 1 : 0,
                'alteredmentalstatus_remarks' => ($r->alteredmentalstatus_yn) ? $r->alteredmentalstatus_remarks : NULL,
                'animalbite' => ($r->animalbite_yn) ? 1 : 0,
                'animalbite_remarks' => ($r->animalbite_yn) ? $r->animalbite_remarks : NULL,
                'bigmessage' => $r->bigmessage,
                'status' => 'approved',
    
                'age_years' => $get_ageyears,
                'age_months' => $get_agemonths,
                'age_days' => $get_agedays,
            ]);
    
            return redirect()->route('syndromic_home')
            ->with('msg', 'Record successfully created.')
            ->with('msgtype', 'success');
        }
        else {
            
        }        
    }

    public function viewPatient() {

    }

    public function viewRecord() {

    }
    
    public function updatePatient() {

    }

    public function updateRecord() {

    }

    public function diseasemap() {
        
        return view('syndromic.mapdashboard');
    }

    public function walkin_part1() {

    }

    public function walkin_part2() {

    }

    public function walkin_part3(Request $r) {

    }
}
