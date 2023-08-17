<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VaxcertConcern;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\VaxcertMasterlistImport;
use App\Models\City;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\CovidVaccinePatientMasterlist;
use App\Models\Provinces;
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
                'email' => ($request->filled('email')) ? strtolower($request->email) : NULL,

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
        if(request()->input('q')) {
            $s = request()->input('q');

            $list = VaxcertConcern::where('id', $s)
            ->orWhere('contact_number', $s)
            ->orWhere('sys_code', $s)
            ->orWhere(DB::raw('CONCAT(last_name," ",first_name)'), 'LIKE', "%".str_replace(',','',mb_strtoupper($s))."%")
            ->paginate(10);
        }
        else {
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
            $v->dose1_batchno = $request->dose1_batchno;
            $v->dose1_vaccinator_name = $request->dose1_vaccinator_name;

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

        if($request->submit == 'complete') {
            return redirect()->route('vaxcert_home')
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
        else {
            return redirect()->route('vaxcert_viewpatient', $v->id)
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
    }

    public function dlbase_template($id) {
        $v = VaxcertConcern::findOrFail($id);

        if($v->getNumberOfDose() == 1 || $v->dose1_manufacturer == 'J&J') {
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
                $vbasedate = $v->dose1_date;
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
                $vbasedate = $v->dose2_date;
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
                $vbasedate = $v->dose3_date;
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
                $vbasedate = $v->dose4_date;
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
                //search province
                $ps = Provinces::where('provinceName', $v->address_province_text)->first();
                $cs = City::where('province_id', $ps->id)
                ->where('cityName', $v->address_muncity_text)->first();

                $prov_code = $ps->getPsgcCode().$ps->alt_name;
                $mun_code = $cs->getPsgcCode().$cs->alt_name;
            }

            if($vbrand == 'ASTRAZENECA') {
                $vbrand = 'AZ';
            }

            if($i == 2 && $v->dose1_manufacturer == 'J&J') {

            }
            else {
                $c = $i+1;

                //check age if matched sa ropp
                $check_bdate = Carbon::parse($v->bdate);
                $check_age = $check_bdate->diffInYears($vbasedate);
                if($check_age >= 5 && $check_age <= 11 && $v->category != 'ROPP (5-11 YEARS OLD)') {
                    $vcat = 'ROPP (5-11 YEARS OLD)';
                }
                else if($check_age >= 12 && $check_age <= 17 && $v->category != 'ROPP (12-17 YEARS OLD)') {
                    $vcat = 'ROPP (12-17 YEARS OLD)';
                }
                else {
                    $vcat = $v->category;
                }

                $sheet->setCellValue('A'.$c, $vcat);
                $sheet->setCellValue('B'.$c, $v->comorbidity); //COMORBID
                $sheet->setCellValue('C'.$c, (!is_null($v->vaxcard_uniqueid)) ? $v->vaxcard_uniqueid : 'NONE'); //UNIQUE PERSON ID
                $sheet->setCellValue('D'.$c, $v->pwd_yn); //PWD
                $sheet->setCellValue('E'.$c, 'NO'); //INDIGENOUS MEMBER
                $sheet->setCellValue('F'.$c, $v->last_name);
                $sheet->setCellValue('G'.$c, $v->first_name);
                $sheet->setCellValue('H'.$c, (!is_null($v->middle_name)) ? $v->middle_name : 'NONE');
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
        $v = CovidVaccinePatientMasterlist::findOrFail($id);

        $spreadsheet = IOFactory::load(storage_path('vaslinelist_template.xlsx'));
        $sheet = $spreadsheet->getActiveSheet('VAS Template');

        if($v->category == '_A1_WORKERS_IN_FRONTLINE_HEALTH_SERVICES') {
            $vcat = 'A1';
        }
        else if ($v->category == 'A3') {
            $vcat = 'A3 - Immunocompromised';
        }
        else {
            $vcat = $v->category;
        }
        
        if($v->vaccine_manufacturer_name == 'ASTRAZENECA') {
            $vbrand = 'AZ';
        }
        else {
            $vbrand = $v->vaccine_manufacturer_name;
        }

        $sheet->setCellValue('A2', $vcat);
        $sheet->setCellValue('B2', $v->comorbidity); //COMORBID
        $sheet->setCellValue('C2', $v->unique_person_id); //UNIQUE PERSON ID
        $sheet->setCellValue('D2', $v->pwd); //PWD
        $sheet->setCellValue('E2', $v->indigenous_member); //INDIGENOUS MEMBER
        $sheet->setCellValue('F2', $v->last_name);
        $sheet->setCellValue('G2', $v->first_name);
        $sheet->setCellValue('H2', (!is_null($v->middle_name)) ? $v->middle_name : 'NONE');
        $sheet->setCellValue('I2', (!is_null($v->suffix)) ? $v->suffix : '');
        $sheet->setCellValue('J2', $v->contact_no);
        $sheet->setCellValue('K2', $v->guardian_name); //GUARDIAN NAME
        $sheet->setCellValue('L2', $v->region); //REGION
        $sheet->setCellValue('M2', $v->province); //PROVINCE
        $sheet->setCellValue('N2', $v->muni_city); //MUNCITY
        $sheet->setCellValue('O2', $v->barangay); //BARANGAY
        $sheet->setCellValue('P2', $v->sex);
        $sheet->setCellValue('Q2', Date::PHPToExcel(date('Y-m-d', strtotime($v->birthdate))));
        $sheet->getStyle('Q2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_MMDDYYYYSLASH);
        $sheet->setCellValue('R2', $v->deferral); //DEFERRAL
        $sheet->setCellValue('S2', $v->reason_for_deferral); //DEFERRAL REASON
        $sheet->setCellValue('T2', Date::PHPToExcel(date('Y-m-d', strtotime($v->vaccination_date))));
        $sheet->getStyle('T2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_MMDDYYYYSLASH);
        $sheet->setCellValue('U2', $vbrand);
        $sheet->setCellValue('V2', $v->batch_number);
        $sheet->setCellValue('W2', $v->lot_no);
        $sheet->setCellValue('X2', $v->bakuna_center_cbcr_id);
        $sheet->setCellValue('Y2', $v->vaccinator_name);
        $sheet->setCellValue('Z2', $v->first_dose);
        $sheet->setCellValue('AA2', $v->second_dose);
        $sheet->setCellValue('AB2', $v->additional_booster_dose);
        $sheet->setCellValue('AC2', $v->second_additional_booster_dose);
        $sheet->setCellValue('AD2', $v->adverse_event); //ADVERSE EVENT
        $sheet->setCellValue('AE2', $v->adverse_event_condition); //ADVERSE EVENT CONDITION

        $fileName = 'vas-line-template-ped-'.strtolower(Str::random(5)).'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function vquery() {
        $lname = request()->input('lname');
        $fname = request()->input('fname');

        if(request()->input('bdate')) {
            $bdate = request()->input('bdate');

            if(request()->input('fname')) {
                if(request()->input('lname')) {
                    $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
                    ->where('first_name', 'LIKE', $fname.'%')
                    ->whereDate('birthdate', $bdate)
                    ->orderBy('vaccination_date', 'ASC')
                    ->get();
                }
                else {
                    $s = CovidVaccinePatientMasterlist::where('first_name', 'LIKE', $fname.'%')
                    ->whereDate('birthdate', $bdate)
                    ->orderBy('vaccination_date', 'ASC')
                    ->get();
                }
            }
            else {
                $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
                ->whereDate('birthdate', $bdate)
                ->orderBy('last_name', 'ASC')
                ->orderBy('first_name', 'ASC')
                ->orderBy('vaccination_date', 'ASC')
                ->get();
            }

            $paginate = false;
        }
        else {
            if(request()->input('fname')) {
                if(request()->input('lname')) {
                    $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
                    ->where('first_name', 'LIKE', $fname.'%')
                    ->orderBy('vaccination_date', 'ASC')
                    ->paginate(25);
                }
                else {
                    $s = CovidVaccinePatientMasterlist::where('first_name', 'LIKE', $fname.'%')
                    ->orderBy('vaccination_date', 'ASC')
                    ->paginate(25);
                }
            }
            else {
                $s = CovidVaccinePatientMasterlist::where('last_name', 'LIKE', $lname.'%')
                ->orderBy('last_name', 'ASC')
                ->orderBy('first_name', 'ASC')
                ->orderBy('vaccination_date', 'ASC')
                ->paginate(25);

                
            }

            $paginate = true;
        }

        if(mb_strpos($lname, 'Ñ') !== false) {
            $enyecheck = true;
        }
        else {
            $enyecheck = false;
        }

        return view('vaxcert.vquery', [
            'd' => $s,
            'enyecheck' => $enyecheck,
            'paginate' => $paginate,
        ]);
    }

    public function report() {
        $get_total = VaxcertConcern::where('status', 'COMPLETED')->count();

        $get_total_current_year = VaxcertConcern::where('status', 'COMPLETED')
        ->whereYear('created_at', date('Y'))
        ->count();

        $get_total_previous_year = VaxcertConcern::where('status', 'COMPLETED')
        ->whereYear('created_at', '!=', date('Y'))
        ->count();

        $get_total_current_month = VaxcertConcern::where('status', 'COMPLETED')
        ->whereMonth('created_at', date('m'))
        ->count();

        $get_total_previous_month = VaxcertConcern::where('status', 'COMPLETED')
        ->whereMonth('created_at', date('m', strtotime('-1 Month')))
        ->count();

        return view('vaxcert.report', [
            'get_total' => $get_total,
            'get_total_current_year' => $get_total_current_year,
            'get_total_previous_year' => $get_total_previous_year,
            'get_total_current_month' => $get_total_current_month,
            'get_total_previous_month' => $get_total_previous_month,
        ]);
    }

    public function walkinmenu() {
        return view('vaxcert.walkin_menu');
    }
}
