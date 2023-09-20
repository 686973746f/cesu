<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PharmacyPatient;
use App\Models\SyndromicDoctor;
use App\Models\SyndromicPatient;
use App\Models\SyndromicRecords;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SyndromicController extends Controller
{
    public function index() {
        $plist = explode(",", auth()->user()->permission_list);

        if(in_array('ITR_BRGY_ADMIN', $plist) || in_array('ITR_BRGY_ENCODER', $plist)) {
            $default_view_opd = 1;
        }
        else {
            $default_view_opd = 0;
        }

        if(in_array('ITR_BRGY_ADMIN', $plist) || in_array('ITR_BRGY_ENCODER', $plist)) {
            if(!(request()->input('q'))) {
                if(request()->input('opd_view')) {
                    $ll = SyndromicRecords::whereDate('created_at', date('Y-m-d'))
                    ->orderBy('created_at', 'DESC')
                    ->paginate(10);
                }
                else {
                    if(!(request()->input('showVerified'))) {
                        $ll = SyndromicRecords::where('brgy_verified', 0);
                    }
                    else {
                        $ll = SyndromicRecords::where('brgy_verified', 1);
                    }
    
                    $ll = $ll->whereHas('syndromic_patient', function ($q) {
                        $q->where('address_brgy_text', auth()->user()->brgy->brgyName)
                        ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                        ->where('address_province_text', auth()->user()->brgy->city->province->provinceName);
                    })
                    ->orderBy('created_at', 'ASC')
                    ->paginate(10);
    
                    return view('syndromic.home', [
                        'list' => $ll,
                    ]);
                }
            }
            else {
                if(request()->input('opd_view')) {

                }
                else {
                    $ll = SyndromicPatient::where(function ($q) {
                        $q->where('id', $q)
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%");
                    })
                    ->where('address_brgy_text', auth()->user()->brgy->brgyName)
                    ->where('address_muncity_text', auth()->user()->brgy->city->cityName)
                    ->where('address_province_text', auth()->user()->brgy->city->province->provinceName)
                    ->paginate(10);
    
                    return view('syndromic.search_patient', [
                        'list' => $ll,
                    ]);
                }
            }
        }
        else {
            if(!(request()->input('q'))) {
                if(!(request()->input('showVerified'))) {
                    $ll = SyndromicRecords::where('brgy_verified', 0)
                    ->orderBy('created_at', 'ASC')
                    ->paginate(10);
                }
                else {
                    $ll = SyndromicRecords::where('brgy_verified', 1)
                    ->orderBy('created_at', 'ASC')
                    ->paginate(10);
                }

                return view('syndromic.home', [
                    'list' => $ll,
                ]);
            }
            else {
                $q = request()->input('q');

                $ll = SyndromicPatient::where('id', $q)
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($q))."%")
                ->paginate(10);

                return view('syndromic.search_patient', [
                    'list' => $ll,
                ]);
            }
        }
    }

    public function downloadOpdExcel() {
        $year = request()->input('year');

        $get_records = SyndromicRecords::whereYear('consultation_date', $year)
        ->orderBy('consultation_date', 'DESC')
        ->get();

        $spreadsheet = IOFactory::load(storage_path('ITR_OPD_RECORD.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        foreach($get_records as $ind => $d) {
            $curtab = $ind + 2;

            //$sheet->setCellValue('A'.$curtab, '');
            $sheet->setCellValue('A'.$curtab, date('m/d/Y', strtotime($d->consultation_date)));
            $sheet->setCellValue('B'.$curtab, $d->opdno);
            $sheet->setCellValue('C'.$curtab, $d->syndromic_patient->getName());
            $sheet->setCellValue('D'.$curtab, $d->syndromic_patient->address_brgy_text);
            $sheet->setCellValue('E'.$curtab, $d->syndromic_patient->getStreetPurok());
            $sheet->setCellValue('F'.$curtab, date('m/d/Y', strtotime($d->syndromic_patient->bdate)));
            $sheet->setCellValue('G'.$curtab, $d->syndromic_patient->getAge());
            $sheet->setCellValue('H'.$curtab, $d->syndromic_patient->gender);
            $sheet->setCellValue('I'.$curtab, $d->syndromic_patient->contact_number);
            $sheet->setCellValue('J'.$curtab, $d->dcnote_assessment);
            $sheet->setCellValue('K'.$curtab, $d->dcnote_plan);
            $sheet->setCellValue('L'.$curtab, date('m/d/Y', strtotime($d->created_at)));
            $sheet->setCellValue('M'.$curtab, $d->user->name);
        }

        $fileName = 'OPD_MASTERLIST_'.$year.'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
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
            ->with('msg', 'Error: Patient ('.$getname.') already exists in the database.')
            ->with('p', $s)
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
            $search2 = PharmacyPatient::where('qr', $qr)->first();

            if(!$search && !$search2) {
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
            ->with('msg', 'Error: Patient ITR Record that was encoded today already exists in the server.')
            ->with('msgtype', 'warning');
        }
        else {
            $number_in_line = SyndromicRecords::where('created_by', auth()->user()->id)
            ->whereDate('created_at', date('Y-m-d'))->count() + 1;

            return view('syndromic.new_record', [
                'patient' => $patient,
                'doclist' => $doclist,
                'number_in_line' => $number_in_line,
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

        //GET LAST CHECK UP
        $lastcheckup = SyndromicRecords::where('syndromic_patient_id', $p->id)
        ->orderBy('consultation_date', 'DESC')
        ->first();

        if(!$check1) {
            $c = $r->user()->syndromicrecord()->create([
                'chief_complain' => mb_strtoupper($r->chief_complain),
                'syndromic_patient_id' => $p->id,
                'opdno' => $getopd_num,
                'last_checkup_date' => ($lastcheckup) ? date('Y-m-d', strtotime($lastcheckup->consultation_date)) : NULL,
                'consultation_date' => $r->consultation_date,
                'temperature' => $r->temperature,
                'bloodpressure' => $r->filled('bloodpressure') ? $r->bloodpressure : NULL,
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

                //'bigmessage' => $r->bigmessage,
                'dcnote_assessment' => ($r->filled('dcnote_assessment')) ? mb_strtoupper($r->dcnote_assessment) : NULL,
                'dcnote_plan' => ($r->filled('dcnote_plan')) ? mb_strtoupper($r->dcnote_plan) : NULL,
                'dcnote_diagprocedure' => ($r->filled('dcnote_diagprocedure')) ? mb_strtoupper($r->dcnote_diagprocedure) : NULL,
                'rx' => ($r->filled('rx')) ? mb_strtoupper($r->rx) : NULL,
                'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,

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

            //Auto Create Pharmacy Account
            $pharmacy_check = PharmacyPatient::where('itr_id', $p->id)->first();
            
            if(!($pharmacy_check)) {
                $create_pharma = $r->user()->pharmacypatient()->create([
                    'lname' => $p->lname,
                    'fname' => $p->fname,
                    'mname' => $p->mname,
                    'suffix' => $p->suffix,
                    'bdate' => $p->bdate,
                    'gender' => $p->gender,
                    'email' => $p->email,
                    'contact_number' => $p->contact_number,
                    'contact_number2' => $p->contact_number2,
                    'philhealth' => $p->philhealth,
            
                    'address_region_code' => $p->address_region_code,
                    'address_region_text' => $p->address_region_text,
                    'address_province_code' => $p->address_province_code,
                    'address_province_text' => $p->address_province_text,
                    'address_muncity_code' => $p->address_muncity_code,
                    'address_muncity_text' => $p->address_muncity_text,
                    'address_brgy_code' => $p->address_brgy_code,
                    'address_brgy_text' => $p->address_brgy_text,
                    'address_street' => $p->address_street,
                    'address_houseno' => $p->address_houseno,
                    
                    'concerns_list' => NULL, //for creation ng pharmacy encoder
                    'qr' => $p->qr,
            
                    'id_file' => NULL,
                    'selfie_file' => NULL,
            
                    'status' => 'ENABLED',
                    
                    'itr_id' => $p->id,
                    'pharmacy_branch_id' => auth()->user()->pharmacy_branch_id,
                ]);
            }
            else {
                $create_pharma = PharmacyPatient::findOrFail($pharmacy_check->id);
            }

            return redirect()->route('syndromic_home')
            ->with('msg', 'Record successfully created.')
            ->with('option_medcert', $c->id)
            ->with('option_pharmacy', $create_pharma->id)
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient ITR Record that was encoded today already exists in the server.')
            ->with('msgtype', 'warning');
        }        
    }

    public function viewPatient($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $sal = User::where('id', '!=', auth()->user()->id)
        ->where('id', '!=', $d->created_by)
        ->where(function ($q) {
            $q->where('permission_list', 'LIKE', '%ITR_BRGY_ADMIN')
            ->orWhere('permission_list', 'LIKE', '%ITR_BRGY_ENCODER');
        })->get();

        $hasRecord_check = SyndromicRecords::where('syndromic_patient_id', $d->id)
        ->first();

        if($hasRecord_check) {
            $has_record = true;
        }
        else {
            $has_record = false;
        }

        if($d->userHasPermissionToAccess()) {
            return view('syndromic.edit_patient', [
                'd' => $d,
                'sal' => $sal,
                'has_record' => $has_record,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function updatePatient($patient_id, Request $request) {
        $lname = $request->lname;
        $fname = $request->fname;
        $bdate = $request->bdate;
        
        $mname = $request->mname;

        //new method of checking duplicate before storing records
        $s = SyndromicPatient::where('id', '!=', $patient_id)
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
        ->whereDate('bdate', $bdate);

        if($request->filled('mname')) {
            $getname = $lname.', '.$fname.' '.$mname;

            $s = $s->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)));
        }
        else {
            $getname = $lname.', '.$fname;
        }

        if($request->filled('suffix')) {
            $suffix = $request->suffix;
            $getname = $getname.' '.$suffix;

            $s = $s->where('suffix', $suffix)->first();
        }
        else {
            $s = $s->first();
        }

        if($s) {
            return redirect()->back()
            ->with('msg', 'Cannot update record. Patient name already exists.')
            ->with('msgtype', 'warning');
        }
        else {
            $getpatient = SyndromicPatient::findOrFail($patient_id);

            if($getpatient->userHasPermissionToShareAccess()) {
                $sharedAccessList = (!is_null($request->shared_access_list)) ? implode(",", $request->shared_access_list) : NULL;
            }
            else {
                $sharedAccessList = $getpatient->shared_access_list;
            }

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

                'shared_access_list' => $sharedAccessList,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->back()
            ->with('msg', 'Patient record was updated successfully.')
            ->with('msgtype', 'success');
        }
    }

    public function deletePatient($patient_id) {
        if(auth()->user()->isAdminSyndromic()) {
            $d = SyndromicPatient::findOrFail($patient_id)->delete();

            return redirect()->route('syndromic_home')
            ->with('msg', 'Patient data was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function viewExistingRecordList($patient_id) {
        $list = SyndromicRecords::where('syndromic_patient_id', $patient_id)
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('syndromic.view_existing_records', [
            'list' => $list,
        ]);
    }

    public function viewRecord($record_id) {
        $r = SyndromicRecords::findOrFail($record_id);
        $doclist = SyndromicDoctor::get();

        if($r->syndromic_patient->userHasPermissionToAccess()) {
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
                'bloodpressure' => ($r->filled('bloodpressure')) ? $r->bloodpressure : NULL,
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

                //'bigmessage' => $r->bigmessage,
                'dcnote_assessment' => ($r->filled('dcnote_assessment')) ? mb_strtoupper($r->dcnote_assessment) : NULL,
                'dcnote_plan' => ($r->filled('dcnote_plan')) ? mb_strtoupper($r->dcnote_plan) : NULL,
                'dcnote_diagprocedure' => ($r->filled('dcnote_diagprocedure')) ? mb_strtoupper($r->dcnote_diagprocedure) : NULL,
                'rx' => ($r->filled('rx')) ? mb_strtoupper($r->rx) : NULL,
                'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,

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

    public function deleteRecord($record_id) {
        if(auth()->user()->isAdminSyndromic()) {
            $d = SyndromicRecords::findOrFail($record_id)->delete();

            return redirect()->route('syndromic_home')
            ->with('msg', 'Record associated with the patient was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function downloadItrDocx($record_id) {
        $d = SyndromicRecords::findOrFail($record_id);

        $paylname = 'ITR_'.$d->syndromic_patient->lname.'_'.date('mdY', strtotime($d->created_at)).'.docx';

        $templateProcessor  = new TemplateProcessor(storage_path('CHO_ITR.docx'));
        
        //$templateProcessor->setValue('asd', '');
        $templateProcessor->setValue('opd_number', $d->opdno);
        $templateProcessor->setValue('qcode', $d->opdno);
        $templateProcessor->setValue('line_number', $d->line_number);

        $templateProcessor->setValue('last_name', $d->syndromic_patient->lname);
        $templateProcessor->setValue('first_name', $d->syndromic_patient->fname);
        $templateProcessor->setValue('middle_name', ($d->syndromic_patient->mname) ? $d->syndromic_patient->mname : 'N/A');
        $templateProcessor->setValue('suffix', ($d->syndromic_patient->suffix) ? $d->syndromic_patient->suffix : 'N/A');
        $templateProcessor->setValue('complete_address', $d->syndromic_patient->getStreetPurok());
        $templateProcessor->setValue('barangay', $d->syndromic_patient->address_brgy_text);
        $templateProcessor->setValue('city', $d->syndromic_patient->address_muncity_text);
        $templateProcessor->setValue('bdate', date('m/d/Y', strtotime($d->syndromic_patient->bdate)));
        $templateProcessor->setValue('age', $d->syndromic_patient->getAge());
        $templateProcessor->setValue('sex', substr($d->syndromic_patient->gender,0,1));
        $templateProcessor->setValue('cs', $d->syndromic_patient->cs);
        $templateProcessor->setValue('get_contactno', $d->syndromic_patient->getContactNumber());
        $templateProcessor->setValue('philhealth', ($d->syndromic_patient->philhealth) ? $d->syndromic_patient->philhealth : 'N/A');
        $templateProcessor->setValue('email', ($d->syndromic_patient->email) ? $d->syndromic_patient->email : 'N/A');
        $templateProcessor->setValue('mother_name', ($d->syndromic_patient->mother_name) ? $d->syndromic_patient->mother_name : 'N/A');
        $templateProcessor->setValue('father_name', ($d->syndromic_patient->father_name) ? $d->syndromic_patient->father_name : 'N/A');
        $templateProcessor->setValue('spouse_name', ($d->syndromic_patient->spouse_name) ? $d->syndromic_patient->spouse_name : 'N/A');
        $templateProcessor->setValue('minor_guardian', ($d->syndromic_patient->ifminor_resperson) ? $d->syndromic_patient->ifminor_resperson : 'N/A');
        $templateProcessor->setValue('guardian_res', ($d->syndromic_patient->ifminor_resrelation) ? $d->syndromic_patient->ifminor_resrelation : 'N/A');
        
        $templateProcessor->setValue('chief_complain', $d->chief_complain);
        $templateProcessor->setValue('con_date', date('m/d/Y h:i A', strtotime($d->consultation_date)));
        $templateProcessor->setValue('temp', $d->temperature.'°C');
        $templateProcessor->setValue('bp', ($d->bloodpressure) ? $d->bloodpressure : 'N/A');
        $templateProcessor->setValue('height', ($d->height) ? $d->height.'cm' : 'N/A');
        $templateProcessor->setValue('weight', ($d->weight) ? $d->weight.'kg' : 'N/A');
        $templateProcessor->setValue('rr', ($d->respiratoryrate) ? $d->respiratoryrate : 'N/A');
        $templateProcessor->setValue('pulse', ($d->pulserate) ? $d->pulserate : 'N/A');
        
        $templateProcessor->setValue('list_assessment', ($d->dcnote_assessment) ? $d->dcnote_assessment : '');
        $templateProcessor->setValue('list_plan', ($d->dcnote_plan) ? $d->dcnote_plan : '');
        $templateProcessor->setValue('rx', ($d->rx) ? $d->rx : '');
        $templateProcessor->setValue('list_diag', ($d->dcnote_diagprocedure) ? $d->dcnote_diagprocedure : '');

        /*
        $templateProcessor->setValue('abdoons', ($d->abdominalpain_onset) ? date('m/d/Y', strtotime($d->abdominalpain_onset)) : 'N/A');
        $templateProcessor->setValue('amsons', ($d->alteredmentalstatus_onset) ? date('m/d/Y', strtotime($d->alteredmentalstatus_onset)) : 'N/A');
        $templateProcessor->setValue('abtons', ($d->animalbite_onset) ? date('m/d/Y', strtotime($d->animalbite_onset)) : 'N/A');
        $templateProcessor->setValue('couons', ($d->cough_onset) ? date('m/d/Y', strtotime($d->cough_onset)) : 'N/A');
        $templateProcessor->setValue('colons', ($d->colds_onset) ? date('m/d/Y', strtotime($d->colds_onset)) : 'N/A');
        $templateProcessor->setValue('conons', ($d->conjunctivitis_onset) ? date('m/d/Y', strtotime($d->conjunctivitis_onset)) : 'N/A');
        $templateProcessor->setValue('eatons', ($d->anorexia_onset) ? date('m/d/Y', strtotime($d->anorexia_onset)) : 'N/A');
        $templateProcessor->setValue('fatons', ($d->fatigue_onset) ? date('m/d/Y', strtotime($d->fatigue_onset)) : 'N/A');
        $templateProcessor->setValue('fevons', ($d->fever_onset) ? date('m/d/Y', strtotime($d->fever_onset)) : 'N/A');
        $templateProcessor->setValue('hedons', ($d->headache_onset) ? date('m/d/Y', strtotime($d->headache_onset)) : 'N/A');
        $templateProcessor->setValue('joions', ($d->jointpain_onset) ? date('m/d/Y', strtotime($d->jointpain_onset)) : 'N/A');
        $templateProcessor->setValue('jauons', ($d->jaundice_onset) ? date('m/d/Y', strtotime($d->jaundice_onset)) : 'N/A');
        $templateProcessor->setValue('losons', ($d->lossofsmell_onset) ? date('m/d/Y', strtotime($d->lossofsmell_onset)) : 'N/A');
        $templateProcessor->setValue('lotons', ($d->lossoftaste_onset) ? date('m/d/Y', strtotime($d->lossoftaste_onset)) : 'N/A');
        $templateProcessor->setValue('musons', ($d->musclepain_onset) ? date('m/d/Y', strtotime($d->musclepain_onset)) : 'N/A');
        $templateProcessor->setValue('nauons', ($d->nausea_onset) ? date('m/d/Y', strtotime($d->nausea_onset)) : 'N/A');
        $templateProcessor->setValue('parons', ($d->paralysis_onset) ? date('m/d/Y', strtotime($d->paralysis_onset)) : 'N/A');
        $templateProcessor->setValue('rasons', ($d->rash_onset) ? date('m/d/Y', strtotime($d->rash_onset)) : 'N/A');
        $templateProcessor->setValue('sormons', ($d->mouthsore_onset) ? date('m/d/Y', strtotime($d->mouthsore_onset)) : 'N/A');
        $templateProcessor->setValue('sortons', ($d->sorethroat_onset) ? date('m/d/Y', strtotime($d->sorethroat_onset)) : 'N/A');
        $templateProcessor->setValue('shoons', ($d->dyspnea_onset) ? date('m/d/Y', strtotime($d->dyspnea_onset)) : 'N/A');
        $templateProcessor->setValue('vomons', ($d->vomiting_onset) ? date('m/d/Y', strtotime($d->vomiting_onset)) : 'N/A');
        $templateProcessor->setValue('weaons', ($d->weaknessofextremities_onset) ? date('m/d/Y', strtotime($d->weaknessofextremities_onset)) : 'N/A');
        $templateProcessor->setValue('othons', ($d->other_symptoms_onset) ? date('m/d/Y', strtotime($d->other_symptoms_onset)) : 'N/A');

        $templateProcessor->setValue('s1c', ($d->abdominalpain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s2c', ($d->alteredmentalstatus == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s3c', ($d->animalbite == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s4c', ($d->cough == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s5c', ($d->colds == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s6c', ($d->conjunctivitis == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s7c', ($d->anorexia == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s8c', ($d->fatigue == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s9c', ($d->fever == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s10c', ($d->headache == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s11c', ($d->jointpain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s12c', ($d->jaundice == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s13c', ($d->lossofsmell == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s14c', ($d->lossoftaste == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s15c', ($d->musclepain == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s16c', ($d->nausea == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s17c', ($d->paralysis == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s18c', ($d->rash == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s19c', ($d->mouthsore == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s20c', ($d->sorethroat == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s21c', ($d->dyspnea == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s22c', ($d->vomiting == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s23c', ($d->weaknessofextremities == 1) ? '☑' : '☐');
        $templateProcessor->setValue('s24c', ($d->other_symptoms == 1) ? '☑' : '☐');
        */

        $templateProcessor->setValue('doctor_name', $d->name_of_physician);
        $templateProcessor->setValue('doctor_position', $d->getPhysicianDetails()->position);
        $templateProcessor->setValue('doctor_regno', $d->getPhysicianDetails()->reg_no);

        ob_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="'. urlencode($paylname).'"');
        $templateProcessor->saveAs('php://output');
    }

    public function generateMedCert($record_id, Request $r) {
        $d = SyndromicRecords::findOrFail($record_id);
        
        $d->medcert_enabled = 1;
        $d->medcert_generated_date = $r->medcert_generated_date;
        $d->medcert_validity_date = $r->medcert_validity_date;
        $d->outcome = 'RECOVERED';
        $d->outcome_recovered_date = $r->medcert_validity_date;

        if($r->filled('medcert_start_date') && $r->filled('medcert_end_date')) {
            $d->medcert_start_date = $r->medcert_start_date;
            $d->medcert_end_date = $r->medcert_end_date;
        }

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->route('syndromic_view_medcert', $d->id);
    }

    public function viewMedCert($record_id) {
        $d = SyndromicRecords::findOrFail($record_id);

        if($d->medcert_enabled == 0) {
            $d->medcert_enabled = 1;
            $d->medcert_generated_date = date('Y-m-d');
            $d->medcert_validity_date = date('Y-m-d');
            $d->outcome = 'RECOVERED';
            $d->outcome_recovered_date = date('Y-m-d');

            if($d->isDirty()) {
                $d->save();
            }
        }

        if(!is_null($d->name_of_physician)) {
            return view('syndromic.view_medcert', ['d' => $d]);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: Please specify Physician before generating MedCert')
            ->with('msgtype', 'warning');
        }
    }

    public function medcertOnlineVerify($qr) {
        $d = SyndromicRecords::where('qr', $qr)->where('medcert_enabled', 1)->first();

        return view('syndromic.online_medcert', ['c' => $d]);
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
        
        $final_arr = [];

        foreach($brgy_list as $b) {
            $case_now = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereDate('created_at', date('Y-m-d'))
            ->count();

            $case_month = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereMonth('created_at', date('m'))
            ->count();

            $case_year = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            })->whereYear('created_at', date('Y'))
            ->count();

            $final_arr[] = [
                'brgy' => $b->brgyName,
                'brgy_id' => $b->id,
                'case_now' => $case_now,
                'case_month' => $case_month,
                'case_year' => $case_year,
            ];
        }
        
        return view('syndromic.mapdashboard', [
            'list' => $final_arr,
        ]);
    }

    public function viewDiseaseList() {
        if(request()->input('brgy_id') && request()->input('type')) {
            $brgy_id = request()->input('brgy_id');
            $type = request()->input('type');

            $b = Brgy::findOrFail($brgy_id);
            
            $query = SyndromicRecords::whereHas('syndromic_patient', function ($q) use ($b) {
                $q->where('address_brgy_text', $b->brgyName)
                ->where('address_muncity_text', $b->city->cityName)
                ->where('address_province_text', $b->city->province->provinceName);
            });

            if($type == 'daily') {
                $query = $query->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
            }
            else if($type == 'monthly') {
                $query = $query->whereMonth('created_at', date('m'))->orderBy('created_at', 'DESC')->get();
            }
            else if($type == 'yearly') {
                $query = $query->whereYear('created_at', date('Y'))->orderBy('created_at', 'DESC')->get();
            }

            return view('syndromic.map_disease_list', [
                'list' => $query,
                'type' => $type,
                'b' => $b,
            ]);
        }
        else {

        }
    }

    public function walkin_part1() {
        
    }

    public function walkin_part2() {

    }

    public function walkin_part3(Request $r) {

    }
}
