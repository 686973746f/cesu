<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VaxcertConcern;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\VaxcertMasterlistImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\CovidVaccinePatientMasterlist;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class VaxcertController extends Controller
{
    public function walkin() {
        return view('vaxcert.walkin');
    }

    public function walkin_process(Request $request) {
        $request->validate([
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'middle_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'suffix' => 'nullable|regex:/^[\pL\s\-]+$/u|max:3',
            'id_file' => 'required|file|mimes:pdf,jpg,png|max:20000',
            'vaxcard_file' => 'required|file|mimes:pdf,jpg,png|max:20000',
            'dose2_date' => ($request->howmanydose == 2 || $request->howmanydose == 3 || $request->howmanydose == 4) ? 'required|after:dose1_date|before_or_equal:today' : 'nullable',
            'dose3_date' => ($request->howmanydose == 3 || $request->howmanydose == 4) ? 'required|after:dose2_date|before_or_equal:today' : 'nullable',
            'dose4_date' => ($request->howmanydose == 4) ? 'required|after:dose3_date|before_or_equal:today' : 'nullable',
        ]);

        $id_file_name = Str::random(10) . '.' . $request->file('id_file')->extension();
        $vaxcard_file_name = Str::random(10) . '.' . $request->file('vaxcard_file')->extension();

        $request->file('id_file')->move($_SERVER['DOCUMENT_ROOT'].'/assets/vaxcert/patients/', $id_file_name);
        $request->file('vaxcard_file')->move($_SERVER['DOCUMENT_ROOT'].'/assets/vaxcert/patients/', $vaxcard_file_name);
        
        $sys_code = strtoupper(Str::random(6));

        $check = VaxcertConcern::where('last_name', mb_strtoupper($request->last_name))
        ->where('first_name', mb_strtoupper($request->first_name))
        ->whereDate('bdate', $request->bdate)
        ->where('status', 'PENDING')
        ->first();

        if(!($check)) {
            if($request->howmanydose == 1) {
                $dose2_date = NULL;
                $dose2_manufacturer = NULL;
                $dose2_bakuna_center_text = NULL;
                $dose2_batchno = NULL;
                $dose2_inmainlgu_yn = NULL;
                $dose2_vaccinator_name = NULL;
    
                $dose3_date = NULL;
                $dose3_manufacturer = NULL;
                $dose3_bakuna_center_text = NULL;
                $dose3_batchno = NULL;
                $dose3_inmainlgu_yn = NULL;
                $dose3_vaccinator_name = NULL;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_inmainlgu_yn = NULL;
                $dose4_vaccinator_name = NULL;
            }
            else if($request->howmanydose == 2) {
                if($request->dose1_manufacturer == 'J&J') {
                    $dose2_date = NULL;
                    $dose2_manufacturer = NULL;
                    $dose2_bakuna_center_text = NULL;
                    $dose2_batchno = NULL;
                    $dose2_inmainlgu_yn = NULL;
                    $dose2_vaccinator_name = NULL;
                }
                else {
                    $dose2_date = $request->dose2_date;
                    $dose2_manufacturer = $request->dose2_manufacturer;
                    $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                    $dose2_batchno = $request->dose2_batchno;
                    $dose2_inmainlgu_yn = $request->dose2_inmainlgu_yn;
                    $dose2_vaccinator_name = ($request->filled('dose2_vaccinator_last_name') && $request->filled('dose2_vaccinator_first_name')) ? mb_strtoupper($request->dose2_vaccinator_last_name.', '.$request->dose2_vaccinator_first_name) : NULL;
                }
                
                $dose3_date = NULL;
                $dose3_manufacturer = NULL;
                $dose3_bakuna_center_text = NULL;
                $dose3_batchno = NULL;
                $dose3_inmainlgu_yn = NULL;
                $dose3_vaccinator_name = NULL;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_inmainlgu_yn = NULL;
                $dose4_vaccinator_name = NULL;
            }
            else if($request->howmanydose == 3) {
                if($request->dose1_manufacturer == 'J&J') {
                    $dose2_date = NULL;
                    $dose2_manufacturer = NULL;
                    $dose2_bakuna_center_text = NULL;
                    $dose2_batchno = NULL;
                    $dose2_inmainlgu_yn = NULL;
                    $dose2_vaccinator_name = NULL;
                }
                else {
                    $dose2_date = $request->dose2_date;
                    $dose2_manufacturer = $request->dose2_manufacturer;
                    $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                    $dose2_batchno = $request->dose2_batchno;
                    $dose2_inmainlgu_yn = $request->dose2_inmainlgu_yn;
                    $dose2_vaccinator_name = ($request->filled('dose2_vaccinator_last_name') && $request->filled('dose2_vaccinator_first_name')) ? mb_strtoupper($request->dose2_vaccinator_last_name.', '.$request->dose2_vaccinator_first_name) : NULL;
                }
    
                $dose3_date = $request->dose3_date;
                $dose3_manufacturer = $request->dose3_manufacturer;
                $dose3_bakuna_center_text = $request->dose3_bakuna_center_text;
                $dose3_batchno = $request->dose3_batchno;
                $dose3_inmainlgu_yn = $request->dose3_inmainlgu_yn;
                $dose3_vaccinator_name = ($request->filled('dose3_vaccinator_last_name') && $request->filled('dose3_vaccinator_first_name')) ? mb_strtoupper($request->dose3_vaccinator_last_name.', '.$request->dose3_vaccinator_first_name) : NULL;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_inmainlgu_yn = NULL;
                $dose4_vaccinator_name = NULL;
            }
            else if($request->howmanydose == 4) {
                if($request->dose1_manufacturer == 'J&J') {
                    $dose2_date = NULL;
                    $dose2_manufacturer = NULL;
                    $dose2_bakuna_center_text = NULL;
                    $dose2_batchno = NULL;
                    $dose2_inmainlgu_yn = NULL;
                    $dose2_vaccinator_name = NULL;
                }
                else {
                    $dose2_date = $request->dose2_date;
                    $dose2_manufacturer = $request->dose2_manufacturer;
                    $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                    $dose2_batchno = $request->dose2_batchno;
                    $dose2_inmainlgu_yn = $request->dose2_inmainlgu_yn;
                    $dose2_vaccinator_name = ($request->filled('dose2_vaccinator_last_name') && $request->filled('dose2_vaccinator_first_name')) ? mb_strtoupper($request->dose2_vaccinator_last_name.', '.$request->dose2_vaccinator_first_name) : NULL;
                }
    
                $dose3_date = $request->dose3_date;
                $dose3_manufacturer = $request->dose3_manufacturer;
                $dose3_bakuna_center_text = $request->dose3_bakuna_center_text;
                $dose3_batchno = $request->dose3_batchno;
                $dose3_inmainlgu_yn = $request->dose3_inmainlgu_yn;
                $dose3_vaccinator_name = ($request->filled('dose3_vaccinator_last_name') && $request->filled('dose3_vaccinator_first_name')) ? mb_strtoupper($request->dose3_vaccinator_last_name.', '.$request->dose3_vaccinator_first_name) : NULL;
    
                $dose4_date = $request->dose4_date;
                $dose4_manufacturer = $request->dose4_manufacturer;
                $dose4_bakuna_center_text = $request->dose4_bakuna_center_text;
                $dose4_batchno = $request->dose4_batchno;
                $dose4_inmainlgu_yn = $request->dose4_inmainlgu_yn;
                $dose4_vaccinator_name = ($request->filled('dose4_vaccinator_last_name') && $request->filled('dose4_vaccinator_first_name')) ? mb_strtoupper($request->dose4_vaccinator_last_name.', '.$request->dose4_vaccinator_first_name) : NULL;
            }

            $age = Carbon::parse($request->bdate)->diffInYears(Carbon::now());
    
            $create = VaxcertConcern::create([
                'vaxcert_refno' => $request->vaxcert_refno,
                'category' => $request->category,
                'last_name' => mb_strtoupper($request->last_name),
                'first_name' => mb_strtoupper($request->first_name),
                'middle_name' => ($request->filled('middle_name')) ? mb_strtoupper($request->middle_name) : NULL,
                'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                'gender' => $request->gender,
                'bdate' => $request->bdate,
                'contact_number' => $request->contact_number,
                'email' => ($request->filled('email')) ? $request->email : NULL,

                'comorbidity' => $request->comorbidity,
                'pwd_yn' => $request->pwd_yn,

                'guardian_name' => ($age < 18) ? $request->glast_name.', '.$request->gfirst_name : NULL,

                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,

                'dose1_date' => $request->dose1_date,
                'dose1_manufacturer' => $request->dose1_manufacturer,
                'dose1_batchno' => $request->dose1_batchno,
                'dose1_lotno' => $request->dose1_batchno,
                'dose1_inmainlgu_yn' => $request->dose1_inmainlgu_yn,
                'dose1_bakuna_center_text' => $request->dose1_bakuna_center_text,
                'dose1_vaccinator_name' => ($request->filled('dose1_vaccinator_last_name') && $request->filled('dose1_vaccinator_first_name')) ? mb_strtoupper($request->dose1_vaccinator_last_name.', '.$request->dose1_vaccinator_first_name) : NULL,
                
                'dose2_date' => $dose2_date,
                'dose2_manufacturer' => $dose2_manufacturer,
                'dose2_batchno' => $dose2_batchno,
                'dose2_lotno' => $dose2_batchno,
                'dose2_inmainlgu_yn' => $dose2_inmainlgu_yn,
                'dose2_bakuna_center_text' => $dose2_bakuna_center_text,
                'dose2_vaccinator_name' => $dose2_vaccinator_name,
                
                'dose3_date' => $dose3_date,
                'dose3_manufacturer' => $dose3_manufacturer,
                'dose3_batchno' => $dose3_batchno,
                'dose3_lotno' => $dose3_batchno,
                'dose3_inmainlgu_yn' => $dose3_inmainlgu_yn,
                'dose3_bakuna_center_text' => $dose3_bakuna_center_text,
                'dose3_vaccinator_name' => $dose3_vaccinator_name,
                
                'dose4_date' => $dose4_date,
                'dose4_manufacturer' => $dose4_manufacturer,
                'dose4_batchno' => $dose4_batchno,
                'dose4_lotno' => $dose4_batchno,
                'dose4_inmainlgu_yn' => $dose4_inmainlgu_yn,
                'dose4_bakuna_center_text' => $dose4_bakuna_center_text,
                'dose4_vaccinator_name' => $dose4_vaccinator_name,
                
                'concern_type' => $request->concern_type,
                'concern_msg' => $request->concern_msg,

                'use_type' => $request->use_type,
                'passport_no' => ($request->use_type == 'ABROAD') ? mb_strtoupper($request->passport_no) : NULL,

                'id_file' => $id_file_name,
                'vaxcard_file' => $vaxcard_file_name,

                'vaxcard_uniqueid' => ($request->filled('vaxcard_uniqueid')) ? mb_strtoupper($request->vaxcard_uniqueid) : NULL,
                'sys_code' => $sys_code,
            ]);
    
            return view('vaxcert.walkin_complete', [
                'code' => $sys_code,
            ]);
        }
        else {
            return redirect()->back()->with('msg', 'Error: You still have PENDING Ticket. Please wait for our staff to finish your request and wait for an update via text/call/email for 1-2 Days. Thank you.')
            ->with('msgtype', 'warning');
        }
    }

    public function walkin_track() {
        $s = request()->input('ref_code');

        $search = VaxcertConcern::where('sys_code', $s)->first();

        if($search) {
            return view('vaxcert.walkin_tracker', [
                'found' => 1,
                'd' => $search,
            ]);
        }
        else {
            return view('vaxcert.walkin_tracker', [
                'found' => 0,
            ]);
        }
    }

    public function home() {
        if(request()->input('viewcomplete')) {
            $list = VaxcertConcern::where('status', '!=', 'PENDING')
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);
        }
        else {
            $list = VaxcertConcern::where('status', 'PENDING')
            ->orderBy('created_at', 'ASC')
            ->paginate(10);
        }
        
        return view('vaxcert.home', [
            'list' => $list,
        ]);
    }

    public function view_patient($id) {
        $v = VaxcertConcern::findOrFail($id);

        $w = CovidVaccinePatientMasterlist::where('last_name', $v->last_name)
        ->where('first_name', $v->first_name)
        ->whereDate('birthdate', $v->bdate)
        ->first();

        return view('vaxcert.viewconcern', [
            'd' => $v,
        ]);
    }

    public function process_patient(Request $request, $id) {
        $v = VaxcertConcern::findOrFail($id);

        if($request->submit == 'complete') {
            $v->status = 'COMPLETED';

            $msg = 'VaxCert Concern Ticket marked completed.';
            $msgtype = 'success';
        }
        else if($request->submit == 'reject') {
            $v->status = 'REJECTED';

            $msg = 'VaxCert Concern Ticket marked rejected.';
            $msgtype = 'success';
        }
        else if($request->submit == 'update') {
            $v->dose1_bakuna_center_code = $request->dose1_bakuna_center_code;

            if($request->howmanydose == 2 || $request->howmanydose == 3 || $request->howmanydose == 4) {
                $v->dose2_bakuna_center_code = $request->dose2_bakuna_center_code;
                $v->dose2_batchno = $request->dose2_batchno;
                $v->dose2_vaccinator_name = $request->dose2_vaccinator_name;
            }

            if($request->howmanydose == 3 || $request->howmanydose == 4) {
                $v->dose3_bakuna_center_code = $request->dose3_bakuna_center_code;
                $v->dose3_batchno = $request->dose3_batchno;
                $v->dose3_vaccinator_name = $request->dose3_vaccinator_name;
            }

            if($request->howmanydose == 4) {
                $v->dose4_bakuna_center_code = $request->dose4_bakuna_center_code;
                $v->dose4_batchno = $request->dose4_batchno;
                $v->dose4_vaccinator_name = $request->dose4_vaccinator_name;
            }

            $msg = 'VaxCert Concern Ticket was updated successfully.';
            $msgtype = 'success';
        }

        if($v->isDirty()) {
            $v->processed_by = auth()->user()->id;
            $v->save();
        }

        return redirect()->route('vaxcert_home')
        ->with('msg', $msg)
        ->with('msgtype', $msgtype);
    }

    public function dlbase_template($id) {
        $v = VaxcertConcern::findOrFail($id);

        if($v->getNumberOfDose() == 1) {
            if(is_null($v->dose1_bakuna_center_code)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up CBCR ID of 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose1_vaccinator_name)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Name of Vaccinator in 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose1_batchno)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Batch/Lot No. in 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
        }
        else if($v->getNumberOfDose() == 2) {
            if(is_null($v->dose1_bakuna_center_code) || is_null($v->dose2_bakuna_center_code)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up CBCR ID of 1st and 2nd Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose2_vaccinator_name)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Name of Vaccinator in 2nd Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose2_batchno)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Batch/Lot No. in 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
        }
        else if($v->getNumberOfDose() == 3) {
            if(is_null($v->dose1_bakuna_center_code) || is_null($v->dose2_bakuna_center_code) || is_null($v->dose3_bakuna_center_code)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up CBCR ID of 1st, 2nd, and 3rd Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose3_vaccinator_name)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Name of Vaccinator in 3rd Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose3_batchno)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Batch/Lot No. in 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
        }
        else if($v->getNumberOfDose() == 4) {
            if(is_null($v->dose1_bakuna_center_code) || is_null($v->dose2_bakuna_center_code) || is_null($v->dose3_bakuna_center_code) || is_null($v->dose4_bakuna_center_code)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up CBCR ID of 1st, 2nd, 3rd, and 4th Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose4_vaccinator_name)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Name of Vaccinator in 4th Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
            else if(is_null($v->dose4_batchno)) {
                return redirect()->back()
                ->with('msg', 'Error: Please fill up Batch/Lot No. in 1st Dose before proceeding.')
                ->with('msgtype', 'warning');
            }
        }

        $spreadsheet = IOFactory::load(storage_path('vaslinelist_template.xlsx'));
        $sheet = $spreadsheet->getActiveSheet('VAS Template');

        //$collection = collect();

        //$sheet->setCellValue('A2', 'bilat');

        for($i = 1; $i <= $v->getNumberOfDose(); $i++) {
            if($i == 1) {
                $vdate = strtotime($v->dose1_date);
                $vbrand = mb_strtoupper($v->dose1_manufacturer);
                $vbatchlot = mb_strtoupper($v->dose1_batchno);

                $vcbcr = $v->dose1_bakuna_center_code;
                $vvacname = mb_strtoupper($v->dose1_vaccinator_name);

                if($v->dose1_manufacturer == 'J&J') {
                    $vdose1yn = 'N';
                    $vdose2yn = 'Y';
                }
                else {
                    $vdose1yn = 'Y';
                    $vdose2yn = 'N';
                }
                $vdose3yn = 'N';
                $vdose4yn = 'N';
            }
            else if($i == 2 && $v->dose1_manufacturer != 'J&J') {
                $vdate = strtotime($v->dose2_date);
                $vbrand = mb_strtoupper($v->dose2_manufacturer);
                $vbatchlot = mb_strtoupper($v->dose2_batchno);

                $vcbcr = $v->dose2_bakuna_center_code;
                $vvacname = mb_strtoupper($v->dose2_vaccinator_name);

                $vdose1yn = 'N';
                $vdose2yn = 'Y';
                $vdose3yn = 'N';
                $vdose4yn = 'N';
            }
            else if($i == 3) {
                $vdate = strtotime($v->dose3_date);
                $vbrand = mb_strtoupper($v->dose3_manufacturer);
                $vbatchlot = mb_strtoupper($v->dose3_batchno);

                $vcbcr = $v->dose3_bakuna_center_code;
                $vvacname = mb_strtoupper($v->dose3_vaccinator_name);

                $vdose1yn = 'N';
                $vdose2yn = 'N';
                $vdose3yn = 'Y';
                $vdose4yn = 'N';
            }
            else if($i == 4) {
                $vdate = strtotime($v->dose4_date);
                $vbrand = mb_strtoupper($v->dose4_manufacturer);
                $vbatchlot = mb_strtoupper($v->dose4_batchno);

                $vcbcr = $v->dose4_bakuna_center_code;
                $vvacname = mb_strtoupper($v->dose4_vaccinator_name);

                $vdose1yn = 'N';
                $vdose2yn = 'N';
                $vdose3yn = 'N';
                $vdose4yn = 'Y';
            }

            if($v->address_province_text == 'CAVITE' && $v->address_muncity_text == 'GENERAL TRIAS') {
                $prov_code = '042100000Cavite';
                $mun_code = '042108000City of General Trias';
            }
            else {
                //autofind soon
                $prov_code = 'NONE';
                $mun_code = 'NONE';
            }

            if($i == 2 && $v->dose1_manufacturer == 'J&J') {

            }
            else {
                $c = $i+1;
                $sheet->setCellValue('A'.$c, $v->category);
                $sheet->setCellValue('B'.$c, ''); //COMORBID
                $sheet->setCellValue('C'.$c, (!is_null($v->vaxcard_uniqueid)) ? $v->vaxcard_uniqueid : 'NONE'); //UNIQUE PERSON ID
                $sheet->setCellValue('D'.$c, $v->pwd_yn); //PWD
                $sheet->setCellValue('E'.$c, 'NO'); //INDIGENOUS MEMBER
                $sheet->setCellValue('F'.$c, $v->last_name);
                $sheet->setCellValue('G'.$c, $v->first_name);
                $sheet->setCellValue('H'.$c, (!is_null($v->middle_name)) ? $v->middle_name : '');
                $sheet->setCellValue('I'.$c, (!is_null($v->suffix)) ? $v->suffix : '');
                $sheet->setCellValue('J'.$c, substr($v->contact_number, 1));
                $sheet->setCellValue('K'.$c, $v->guardian_name); //GUARDIAN NAME
                $sheet->setCellValue('L'.$c, $v->address_region_text); //REGION
                $sheet->setCellValue('M'.$c, $prov_code); //PROVINCE
                $sheet->setCellValue('N'.$c, $mun_code); //MUNCITY
                $sheet->setCellValue('O'.$c, $v->address_brgy_text); //BARANGAY
                $sheet->setCellValue('P'.$c, $v->gender);
                $sheet->setCellValue('Q'.$c, Date::PHPToExcel(date('Y-m-d', strtotime($v->bdate))));
                $sheet->getStyle('Q'.$c)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_MMDDYYYYSLASH);
                $sheet->setCellValue('R'.$c, 'N'); //DEFERRAL
                $sheet->setCellValue('S'.$c, ''); //DEFERRAL REASON
                $sheet->setCellValue('T'.$c, Date::PHPToExcel(date('Y-m-d', $vdate)));
                $sheet->getStyle('T'.$c)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_MMDDYYYYSLASH);
                $sheet->setCellValue('U'.$c, $vbrand);
                $sheet->setCellValue('V'.$c, $vbatchlot);
                $sheet->setCellValue('W'.$c, $vbatchlot);
                $sheet->setCellValue('X'.$c, $vcbcr);
                $sheet->setCellValue('Y'.$c, $vvacname);
                $sheet->setCellValue('Z'.$c, $vdose1yn);
                $sheet->setCellValue('AA'.$c, $vdose2yn);
                $sheet->setCellValue('AB'.$c, $vdose3yn);
                $sheet->setCellValue('AC'.$c, $vdose4yn);
                $sheet->setCellValue('AD'.$c, 'N'); //ADVERSE EVENT
                $sheet->setCellValue('AE'.$c, ''); //ADVERSE EVENT CONDITION
            }
        }

        $fileName = 'vas-line-template-ped-'.strtolower(Str::random(5)).'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');

        /*
        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())->setShouldWrapText()->build();

        return (new FastExcel($collection))
        ->headerStyle($header_style)
        ->rowsStyle($rows_style)
        ->download('test.xlsx', function ($form) {
            return [
                'CATEGORY' => $form['CATEGORY'],
            ];
        });
        */
    }

    public function dloff_template($id) {

    }

    public function vquery() {
        $lname = request()->input('lname');
        $fname = request()->input('fname');

        if(request()->input('bdate')) {
            $bdate = request()->input('bdate');

            $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
            ->where('first_name', 'LIKE', $fname.'%')
            ->whereDate('birthdate', $bdate)
            ->orderBy('vaccination_date', 'ASC')
            ->get();
        }
        else {
            $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
            ->where('first_name', 'LIKE', $fname.'%')
            ->orderBy('vaccination_date', 'ASC')
            ->get();
        }

        return view('vaxcert.vquery', [
            'd' => $s,
        ]);
    }

    public function report() {
        
    }
}
