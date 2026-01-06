<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\FwInjury;
use App\Imports\FwriImport;
use App\Models\DohFacility;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BarangayHealthStation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FwriController extends Controller
{
    public function index($code) {
        $s = BarangayHealthStation::where('sys_code1', $code)->first();
        
        if(!($s)) {
            $s = DohFacility::where('sys_code1', $code)->first();

            if($s) {
                $facility_name = $s->facility_name;
                
                /*
                if($s->facility_type == 'Hospital' || $s->facility_type == 'Infirmary') {
                    return redirect()
                    ->back()
                    ->with('msg', 'Hospitals/Infirmaries must use the CSV Uploading Tool instead.')
                    ->with('msgtype', 'warning');
                }
                */
            }
            else {
                return abort(401);
            }
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

    public static function storeFwriTemplate($code, $r) {
        //SEARCH CODE AND FETCH FACILITY NAME
        if($code == 'FROM_SELFREPORT') {
            $facility_name = 'FROM_SELFREPORT';
            $atype = 'TBD';

            $report_date = date('Y-m-d');
        }
        else {
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

            $report_date = $r->report_date;
        }
        
        $birthdate = Carbon::parse($r->bdate);
        $currentDate = Carbon::parse();

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        //CHECK TREATMENT GIVEN
        if(!empty($r->treatment_given)) {
            if(in_array('NO TREATMENT', $r->treatment_given)) {
                $tr_given = 'NO TREATMENT';
            }
            else {
                $tr_given = implode(',', $r->treatment_given);
            }
        }
        else {
            $tr_given = 'NO TREATMENT';
        }

        //CHECK AWARE HEALTH EDUCATION LIST
        if(!empty($r->aware_healtheducation_list)) {
            if(in_array('', $r->aware_healtheducation_list)) {
                $get_hel = 'NOT AWARE';
            }
            else {
                $get_hel = implode(',', $r->aware_healtheducation_list);
            }
        }
        else {
            $get_hel = 'NOT AWARE';
        }
        

        $address_street = '';

        if($r->filled('address_houseno')) {
            $address_street = $address_street.mb_strtoupper($r->address_street);
        }

        if($r->filled('address_street')) {
            $address_street = $address_street.' '.mb_strtoupper($r->address_houseno);
        }

        $injury_address_street = '';
        if($r->injury_sameadd) {
            if($r->filled('address_houseno')) {
                $injury_address_street = $address_street.mb_strtoupper($r->injury_address_street);
            }

            if($r->filled('address_street')) {
                $injury_address_street = $address_street.' '.mb_strtoupper($r->injury_address_houseno);
            }
        }
        else {
            if($r->filled('injury_address_houseno')) {
            $injury_address_street = $injury_address_street.mb_strtoupper($r->injury_address_houseno);
            }

            if($r->filled('injury_address_street')) {
                $injury_address_street = $injury_address_street.' '.mb_strtoupper($r->injury_address_street);
            }
        }

        $foundunique = false;

        while(!$foundunique) {
            $for_qr = Str::random(10);
            
            $search = FwInjury::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }

        $handle_array = [
            'reported_by' => mb_strtoupper($r->reported_by),
            'report_date' => $report_date,
            'facility_code' => $code,
            'account_type' => $atype,
            'hospital_name' => $facility_name,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
            'bdate' => $r->bdate,
            'gender' => mb_strtoupper($r->gender),
            'is_4ps' => $r->is_4ps,
            'contact_number' => $r->contact_number,
            //'address_region_code' => $r->address_region_code,
            //'address_region_text' => $r->address_region_text,
            //'address_province_code' => $r->address_province_code,
            //'address_province_text' => $r->address_province_text,
            //'address_muncity_code' => $r->address_muncity_code,
            //'address_muncity_text' => $r->address_muncity_text,
            //'address_brgy_code' => $r->address_brgy_text,
            //'address_brgy_text' => $r->address_brgy_text,
            'brgy_id' => $r->brgy_id,
            'address_street' => mb_strtoupper($r->address_street),
            //'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
            'injury_date' => $r->injury_date,
            'consultation_date' => $r->consultation_date,
            'reffered_anotherhospital' => $r->reffered_anotherhospital,
            'nameof_hospital' => ($r->reffered_anotherhospital == 'Y') ? mb_strtoupper($r->nameof_hospital) : NULL,
            'place_of_occurrence' => $r->place_of_occurrence,
            'place_of_occurrence_others' => ($r->place_of_occurrence == 'OTHERS') ? mb_strtoupper($r->place_of_occurrence_others) : NULL,
            'injury_sameadd' => $r->injury_sameadd,
            //'injury_address_region_code' => $inj_reg_code,
            //'injury_address_region_text' => $inj_reg_text,
            //'injury_address_province_code' => $inj_prov_code,
            //'injury_address_province_text' => $inj_prov_text,
            //'injury_address_muncity_code' => $inj_munc_code,
            //'injury_address_muncity_text' => $inj_munc_text,
            //'injury_address_brgy_code' => $inj_brgy,
            //'injury_address_brgy_text' => $inj_brgy,
            'inj_brgy_id' => ($r->injury_sameadd == 'Y') ? $r->brgy_id : $r->inj_brgy_id,
            'injury_address_street' => ($r->injury_sameadd == 'Y') ? mb_strtoupper($r->address_street) : mb_strtoupper($r->injury_address_street),
            //'injury_address_houseno' => ($r->filled('injury_address_houseno')) ? mb_strtoupper($r->injury_address_houseno) : NULL,
            'involvement_type' => $r->involvement_type,
            'nature_injury' => $r->nature_injury,
            'iffw_typeofinjury' => ($r->nature_injury == 'FIREWORKS INJURY') ? implode(',', $r->iffw_typeofinjury) : NULL,
            'complete_diagnosis' => ($r->complete_diagnosis) ? mb_strtoupper($r->complete_diagnosis) : NULL,
            'anatomical_location' => implode(',', $r->anatomical_location),
            'firework_name' => mb_strtoupper($r->firework_name),
            'firework_illegal' => $r->firework_illegal,
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

            'remarks' => (request()->filled('remarks')) ? $r->remarks : NULL,
            'qr' => $for_qr,
        ];

        return $handle_array;
    }

    public function store($code, Request $r) {
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
            $c = FwInjury::create($this->storeFwriTemplate($code, $r));

            return redirect()->route('fwri_success', $code);
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
        $currentDate = Carbon::now();

        if(request()->input('showDisabled')) {
            $list = FwInjury::where('status', 'DISABLED')
            ->orderBy('created_at', 'DESC');
        }
        else {
            $list = FwInjury::where('status', 'ENABLED')
            ->orderBy('created_at', 'DESC');
        }

        if(request()->input('select_year')) {
            $date1 = request()->input('select_year').'-12-01';
            $date2 = (request()->input('select_year')+1).'-01-10';
        }
        else {
            if ($currentDate->month === Carbon::DECEMBER) {
                $date1 = date('Y-12-01');
                $date2 = (date('Y') +1).'-01-10';
            }
            else {
                $date1 = date('Y-12-01', strtotime('-1 Year'));
                $date2 = date('Y-01-10');
            }            
        }

        $list = $list->whereBetween('created_at', [$date1, $date2])->get();

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
        $currentDate = Carbon::parse($r->report_date);

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
            'is_4ps' => $r->is_4ps,
            'contact_number' => $r->contact_number,
            //'address_region_code' => $r->address_region_code,
            //'address_region_text' => $r->address_region_text,
            //'address_province_code' => $r->address_province_code,
            //'address_province_text' => $r->address_province_text,
            //'address_muncity_code' => $r->address_muncity_code,
            //'address_muncity_text' => $r->address_muncity_text,
            //'address_brgy_code' => $r->address_brgy_text,
            //'address_brgy_text' => $r->address_brgy_text,
            'brgy_id' => $r->brgy_id,
            'address_street' => mb_strtoupper($r->address_street),
            //'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
            'injury_date' => $r->injury_date,
            'consultation_date' => $r->consultation_date,
            'reffered_anotherhospital' => $r->reffered_anotherhospital,
            'nameof_hospital' => ($r->reffered_anotherhospital == 'Y') ? mb_strtoupper($r->nameof_hospital) : NULL,
            'place_of_occurrence' => $r->place_of_occurrence,
            'place_of_occurrence_others' => ($r->place_of_occurrence_others == 'OTHERS') ? mb_strtoupper($r->place_of_occurrence_others) : NULL,
            'injury_sameadd' => $r->injury_sameadd,
            'inj_brgy_id' => ($r->injury_sameadd == 'Y') ? $r->brgy_id : $r->inj_brgy_id,
            //'injury_address_region_code' => $r->injury_address_region_code,
            //'injury_address_region_text' => $r->injury_address_region_text,
            //'injury_address_province_code' => $r->injury_address_province_code,
            //'injury_address_province_text' => $r->injury_address_province_text,
            //'injury_address_muncity_code' => $r->injury_address_muncity_code,
            //'injury_address_muncity_text' => $r->injury_address_muncity_text,
            //'injury_address_brgy_code' => $r->injury_address_brgy_text,
            //'injury_address_brgy_text' => $r->injury_address_brgy_text,
            
            'injury_address_street' => ($r->injury_sameadd == 'Y') ? mb_strtoupper($r->address_street) : mb_strtoupper($r->injury_address_street),
            //'injury_address_houseno' => ($r->filled('injury_address_houseno')) ? mb_strtoupper($r->injury_address_houseno) : NULL,
            'involvement_type' => $r->involvement_type,
            'nature_injury' => $r->nature_injury,
            'iffw_typeofinjury' => ($r->nature_injury == 'FIREWORKS INJURY') ? implode(',', $r->iffw_typeofinjury) : NULL,
            'complete_diagnosis' => mb_strtoupper($r->complete_diagnosis),
            'anatomical_location' => implode(',', $r->anatomical_location),
            'firework_name' => mb_strtoupper($r->firework_name),
            'firework_illegal' => $r->firework_illegal,
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

            'status' => $r->status,
            'remarks' => (request()->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
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
            $date2 = date(($forYear + 1).'-01-10');
        }
        else {
            $forYear = date('Y');

            $date1 = date('Y-12-21');
            $date2 = date(($forYear + 1).'-01-10');
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

    public function export() {
        $currentDate = Carbon::now();
        
        if ($currentDate->month === Carbon::DECEMBER) {
            $date1 = Carbon::parse(date('Y-12-21'));
            $date2 = Carbon::parse((date('Y') +1).'-01-10');
        }
        else {
            $date1 = Carbon::parse((date('Y')-1).'-12-21');
            $date2 = Carbon::parse(date('Y-01-10'));
        }

        $list = FwInjury::whereBetween('created_at', [$date1, $date2])
        ->where('status', 'ENABLED')
        ->get();

        $startCell1 = 8;

        if($list->count() != 0) {
            $spreadsheet1 = IOFactory::load(storage_path('FWRI1.xlsx'));
            $sheet1 = $spreadsheet1->getActiveSheet();

            $sheet1->setCellValue('B4', 'DATE: '.date('m/d/Y').' - MunCity: GENERAL TRIAS');

            foreach($list as $d) {
                if($d->nature_injury == 'Fireworks - related') {
                    $getInjuryType = $d->iffw_typeofinjury;
                }
                else {
                    $getInjuryType = $d->nature_injury;
                }

                //GET DIAGNOSIS
                /*
                if(!is_null($d->complete_diagnosis)) {
                    $getDiag = $d->complete_diagnosis.' - '.$d->anatomical_location;
                }
                else {
                    $getDiag = $d->anatomical_location;
                }
                */

                //GET DISPOSITION
                if($d->disposition_after_admission == 'DIED DURING ADMISSION') {
                    $getDispo = 'DIED '.date('(m/d/Y)', strtotime($d->date_died));
                }
                else {
                    if($d->disposition_after_admission == 'Transferred/ Referred to:') {
                        $getDispo = 'Transferred/Referred to: '.$d->disposition_after_admission_transferred_hospital;
                    }
                    else {
                        $getDispo = $d->disposition_after_admission;
                    }
                }
                
                $sheet1->setCellValue('B'.$startCell1, date('m/d/Y h:i A', strtotime($d->consultation_date)));
                $sheet1->setCellValue('C'.$startCell1, $d->hospital_name);
                $sheet1->setCellValue('D'.$startCell1, $d->getName());
                $sheet1->setCellValue('E'.$startCell1, $d->getAgeInt().'/'.$d->sg());
                $sheet1->setCellValue('F'.$startCell1, $d->address_street);
                $sheet1->setCellValue('G'.$startCell1, $d->brgy->name);
                $sheet1->setCellValue('H'.$startCell1, $d->brgy->city->name);
                
                $sheet1->setCellValue('I'.$startCell1, date('m/d/Y h:i A', strtotime($d->injury_date)));
                $sheet1->setCellValue('J'.$startCell1, $d->specifyInjuryLoc());
                $sheet1->setCellValue('K'.$startCell1, $d->injury_sameadd);
                $sheet1->setCellValue('L'.$startCell1, $d->specifyInjuryLocOth());
                $sheet1->setCellValue('M'.$startCell1, ($d->injurybrgy->city->id == 388) ? 'Y' : 'N');
                
                $sheet1->setCellValue('N'.$startCell1, $d->involvement_type);
                $sheet1->setCellValue('O'.$startCell1, $getInjuryType);
                $sheet1->setCellValue('P'.$startCell1, $d->complete_diagnosis);
                $sheet1->setCellValue('Q'.$startCell1, $d->anatomical_location);
                $sheet1->setCellValue('R'.$startCell1, $d->firework_name);
                $sheet1->setCellValue('S'.$startCell1, $d->liquor_intoxication);
                $sheet1->setCellValue('T'.$startCell1, $d->treatment_given);
                $sheet1->setCellValue('U'.$startCell1, $getDispo);
                $sheet1->setCellValue('V'.$startCell1, $d->aware_healtheducation_list);

                $startCell1++;
            }
            
            //$writer1->save(storage_path('app/fwri/CESUGENTRIAS_APIR_LINELIST_'.date('mdY', strtotime('-1 Day')).'.xlsx'));

            $fileName = 'FIREWORKSINJURY_GENTRIAS_'.date('m_d_Y').'.xlsx';
            ob_clean();
            $writer1 = new Xlsx($spreadsheet1);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer1->save('php://output');
        }
    }

    public function selfreportIndex() {
        return view('fwri.selfreport');
    }

    public function selfreportCheck() {
        return view('fwri.index');
    }

    public function selfreportStore(Request $r) {
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
            $c = FwInjury::create($this->storeFwriTemplate('FROM_SELFREPORT', $r));

            return dd('success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient data already exists in the server.')
            ->with('msgtype', 'warning');
        }
    }
}
