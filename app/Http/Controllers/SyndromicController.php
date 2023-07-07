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
        $c = $request->user()->syndromicpatient()->create([
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
            'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
            'bdate' => $request->bdate,
            'gender' => $request->gender,
            'cs' => $request->cs,
            'contact_number' => $request->contact_number,

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

        //opd number generator

        $patient_yearcount = SyndromicRecords::whereYear('created_at', date('Y'))->count() + 1;
        
        $getopd_num = date('Y').'-'.$patient_yearcount;

        $c = $r->user()->syndromicrecord()->create([
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
            'cough',
            'cough_remarks' => ($r->cough_yn) ? $r->cough_remarks : NULL,
            'colds',
            'colds_remarks' => ($r->colds_yn) ? $r->colds_remarks : NULL,
            'conjunctivitis',
            'conjunctivitis_remarks' => ($r->conjunctivitis) ? $r->conjunctivitis_remarks : NULL,
            'mouthsore',
            'mouthsore_remarks' => ($r->fever_yn) ? $r->mouthsore_remarks : NULL,
            'lossoftaste',
            'lossoftaste_remarks' => ($r->fever_yn) ? $r->lossoftaste_remarks : NULL,
            'lossofsmell',
            'lossofsmell_remarks' => ($r->fever_yn) ? $r->lossofsmell_remarks : NULL,
            'headache',
            'headache_remarks' => ($r->fever_yn) ? $r->headache_remarks : NULL,
            'jointpain',
            'jointpain_remarks' => ($r->fever_yn) ? $r->jointpain_remarks : NULL,
            'musclepain',
            'musclepain_remarks' => ($r->fever_yn) ? $r->musclepain_remarks : NULL,
            'diarrhea',
            'diarrhea_remarks' => ($r->fever_yn) ? $r->diarrhea_remarks : NULL,
            'abdominalpain',
            'abdominalpain_remarks' => ($r->fever_yn) ? $r->abdominalpain_remarks : NULL,
            'vomiting',
            'vomiting_remarks' => ($r->fever_yn) ? $r->vomiting_remarks : NULL,
            'weaknessofextremities',
            'weaknessofextremities_remarks' => ($r->fever_yn) ? $r->weaknessofextremities_remarks : NULL,
            'paralysis',
            'paralysis_remarks' => ($r->fever_yn) ? $r->paralysis_remarks : NULL,
            'alteredmentalstatus',
            'alteredmentalstatus_remarks' => ($r->fever_yn) ? $r->alteredmentalstatus_remarks : NULL,
            'animalbite',
            'animalbite_remarks' => ($r->fever_yn) ? $r->animalbite_remarks : NULL,
            'bigmessage',
        ]);
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

    }


}
