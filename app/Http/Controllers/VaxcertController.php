<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\VaxcertConcern;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Imports\VaxcertMasterlistImport;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Imports\VaxcertMasterlistImportv2;
use App\Models\CovidVaccinePatientMasterlist;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class VaxcertController extends Controller
{
    public function remoteimport() {
        ini_set('max_execution_time', 999999);
        //Excel::import(new VaxcertMasterlistImport(), storage_path('app/vaxcert/masterlistv2.xlsx'));

        /*
        $import = new VaxcertMasterlistImportv2();
    
        Excel::filter('chunk')->load(storage_path('app/vaxcert/masterlistv2.csv'))->chunk(1000, function($results) use ($import) {
            $import->onRow($results);
        });
        */

        $create = (new FastExcel)->configureCsv(',', '"', 'gbk')->import(storage_path('app/vaxcert/masterlist.csv'), function ($row) {
            /*
            $search = CovidVaccinePatientMasterlist::where('row_hash', $row['ROW_HASH'])->first();

            if(!($search)) {
                
            }
            */

            return CovidVaccinePatientMasterlist::create([
                'source_name' => $row['Source.Name'],
                'category' => $row['CATEGORY'],
                'comorbidity' => $row['COMORBIDITY'],
                'unique_person_id' => $row['UNIQUE_PERSON_ID'],
                'pwd' => $row['PWD'],
                'indigenous_member' => $row['INDIGENOUS_MEMBER'],
                'last_name' => $row['LAST_NAME'],
                'first_name' => $row['FIRST_NAME'],
                'middle_name' => $row['MIDDLE_NAME'],
                'suffix' => $row['SUFFIX'],
                'contact_no' => $row['CONTACT_NO'],
                'guardian_name' => $row['GUARDIAN_NAME'],
                'region' => $row['REGION'],
                'province' => $row['PROVINCE'],
                'muni_city' => $row['MUNI_CITY'],
                'barangay' => $row['BARANGAY'],
                'sex' => $row['SEX'],
                'birthdate' => date('Y-m-d', strtotime($row['BIRTHDATE'])),
                'deferral' => 'N',
                'reason_for_deferral' => NULL,
                'vaccination_date' => date('Y-m-d', strtotime($row['VACCINATION_DATE'])),
                'vaccine_manufacturer_name' => $row['VACCINE_MANUFACTURER_NAME'],
                'batch_number' => $row['BATCH_NUMBER'],
                'lot_no' => $row['LOT_NO'],
                'bakuna_center_cbcr_id' => $row['BAKUNA_CENTER_CBCR_ID'],
                'vaccinator_name' => $row['VACCINATOR_NAME'],
                'first_dose' => $row['FIRST_DOSE'],
                'second_dose' => $row['SECOND_DOSE'],
                'additional_booster_dose' => $row['ADDITIONAL_BOOSTER_DOSE'],
                'second_additional_booster_dose' => $row['SECOND_ADDITIONAL_BOOSTER_DOSE'],
                'adverse_event' => $row['ADVERSE_EVENT'],
                'adverse_event_condition' => $row['ADVERSE_EVENT_CONDITION'],
                'row_hash' => $row['ROW_HASH'],
            ]);
        });
    }

    public function walkin() {
        return view('vaxcert.walkin');
    }

    public function walkin_process(Request $request) {
        $request->validate([
            'id_file' => 'required|file|mimes:pdf,jpg,png|max:20000',
            'vaxcard_file' => 'required|file|mimes:pdf,jpg,png|max:20000',
        ]);

        $id_file_name = Str::random(40) . '.' . $request->file('id_file')->extension();
        $vaxcard_file_name = Str::random(40) . '.' . $request->file('vaxcard_file')->extension();

        $request->file('id_file')->storeAs('vaxcert/patient', $id_file_name);
        $request->file('vaxcard_file')->storeAs('vaxcert/patient', $vaxcard_file_name);
        
        $sys_code = strtoupper(Str::random(6));

        $check = VaxcertConcern::where('last_name', mb_strtoupper($request->last_name))
        ->where('first_name', mb_strtoupper($request->last_name))
        ->whereDate('bdate', $request->bdate)
        ->where('status', 'PENDING')
        ->first();

        if(!($check)) {
            if($request->howmanydose == 1) {
                $dose2_date = NULL;
                $dose2_manufacturer = NULL;
                $dose2_bakuna_center_text = NULL;
                $dose2_batchno = NULL;
                $dose2_vaccinator_last_name = NULL;
                $dose2_vaccinator_first_name = NULL;
    
                $dose3_date = NULL;
                $dose3_manufacturer = NULL;
                $dose3_bakuna_center_text = NULL;
                $dose3_batchno = NULL;
                $dose3_vaccinator_last_name = NULL;
                $dose3_vaccinator_first_name = NULL;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_vaccinator_last_name = NULL;
                $dose4_vaccinator_first_name = NULL;
            }
            else if($request->howmanydose == 2) {
                $dose2_date = $request->dose2_date;
                $dose2_manufacturer = $request->dose2_manufacturer;
                $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                $dose2_batchno = $request->dose2_batchno;
                $dose2_vaccinator_last_name = $request->dose2_vaccinator_last_name;
                $dose2_vaccinator_first_name = $request->dose2_vaccinator_first_name;
    
                $dose3_date = NULL;
                $dose3_manufacturer = NULL;
                $dose3_bakuna_center_text = NULL;
                $dose3_batchno = NULL;
                $dose3_vaccinator_last_name = NULL;
                $dose3_vaccinator_first_name = NULL;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_vaccinator_last_name = NULL;
                $dose4_vaccinator_first_name = NULL;
            }
            else if($request->howmanydose == 3) {
                $dose2_date = $request->dose2_date;
                $dose2_manufacturer = $request->dose2_manufacturer;
                $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                $dose2_batchno = $request->dose2_batchno;
                $dose2_vaccinator_last_name = $request->dose2_vaccinator_last_name;
                $dose2_vaccinator_first_name = $request->dose2_vaccinator_first_name;
    
                $dose3_date = $request->dose3_date;
                $dose3_manufacturer = $request->dose3_manufacturer;
                $dose3_bakuna_center_text = $request->dose3_bakuna_center_text;
                $dose3_batchno = $request->dose3_batchno;
                $dose3_vaccinator_last_name = $request->dose3_vaccinator_last_name;
                $dose3_vaccinator_first_name = $request->dose3_vaccinator_first_name;
    
                $dose4_date = NULL;
                $dose4_manufacturer = NULL;
                $dose4_bakuna_center_text = NULL;
                $dose4_batchno = NULL;
                $dose4_vaccinator_last_name = NULL;
                $dose4_vaccinator_first_name = NULL;
            }
            else if($request->howmanydose == 4) {
                $dose2_date = $request->dose2_date;
                $dose2_manufacturer = $request->dose2_manufacturer;
                $dose2_bakuna_center_text = $request->dose2_bakuna_center_text;
                $dose2_batchno = $request->dose2_batchno;
                $dose2_vaccinator_last_name = $request->dose2_vaccinator_last_name;
                $dose2_vaccinator_first_name = $request->dose2_vaccinator_first_name;
    
                $dose3_date = $request->dose3_date;
                $dose3_manufacturer = $request->dose3_manufacturer;
                $dose3_bakuna_center_text = $request->dose3_bakuna_center_text;
                $dose3_batchno = $request->dose3_batchno;
                $dose3_vaccinator_last_name = $request->dose3_vaccinator_last_name;
                $dose3_vaccinator_first_name = $request->dose3_vaccinator_first_name;
    
                $dose4_date = $request->dose4_date;
                $dose4_manufacturer = $request->dose4_manufacturer;
                $dose4_bakuna_center_text = $request->dose4_bakuna_center_text;
                $dose4_batchno = $request->dose4_batchno;
                $dose4_vaccinator_last_name = $request->dose4_vaccinator_last_name;
                $dose4_vaccinator_first_name = $request->dose4_vaccinator_first_name;
            }
    
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
                'dose1_bakuna_center_text' => $request->dose1_bakuna_center_text,
                'dose1_vaccinator_last_name' => $request->dose1_vaccinator_last_name,
                'dose1_vaccinator_first_name' => $request->dose1_vaccinator_first_name,
                'dose2_date' => $dose2_date,
                'dose2_manufacturer' => $dose2_manufacturer,
                'dose2_batchno' => $dose2_batchno,
                'dose2_lotno' => $dose2_batchno,
                'dose2_bakuna_center_text' => $dose2_bakuna_center_text,
                'dose2_vaccinator_last_name' => $dose2_vaccinator_last_name,
                'dose2_vaccinator_first_name' => $dose2_vaccinator_first_name,
                'dose3_date' => $dose3_date,
                'dose3_manufacturer' => $dose3_manufacturer,
                'dose3_batchno' => $dose3_batchno,
                'dose3_lotno' => $dose3_batchno,
                'dose3_bakuna_center_text' => $dose3_bakuna_center_text,
                'dose3_vaccinator_last_name' => $dose3_vaccinator_last_name,
                'dose3_vaccinator_first_name' => $dose3_vaccinator_first_name,
                'dose4_date' => $dose4_date,
                'dose4_manufacturer' => $dose4_manufacturer,
                'dose4_batchno' => $dose4_batchno,
                'dose4_lotno' => $dose4_batchno,
                'dose4_bakuna_center_text' => $dose4_bakuna_center_text,
                'dose4_vaccinator_last_name' => $dose4_vaccinator_last_name,
                'dose4_vaccinator_first_name' => $dose4_vaccinator_first_name,
                'concern_type' => $request->concern_type,
                'concern_msg' => $request->concern_msg,
                'id_file' => $id_file_name,
                'vaxcard_file' =>$vaxcard_file_name,
                'sys_code' => $sys_code,
            ]);
    
            return view('vaxcert.walkin_complete', [
                'code' => $sys_code,
            ]);
        }
        else {
            return redirect()->back()->with('msg', 'Error: You still have PENDING Ticket.')
            ->with('msgtype', 'warning');
        }
    }

    public function home() {
        $list = VaxcertConcern::where('status', 'PENDING')
        ->orderBy('created_at', 'ASC')
        ->paginate(10);

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

    public function process_patient(Request $request) {

    }

    public function dlbase_template($id) {
        $v = VaxcertConcern::findOrFail($id);

        $spreadsheet = IOFactory::load(storage_path('vaslinelist_template.xlsx'));
        $sheet = $spreadsheet->getActiveSheet('VAS Template');

        //$collection = collect();

        //$sheet->setCellValue('A2', 'bilat');

        for($i = 1; $i <= $v->getNumberOfDose(); $i++) {
            if($i == 1) {
                $vdate = date('m/d/Y', strtotime($v->dose1_date));
                $vbrand = mb_strtoupper($v->dose1_manufacturer);
                $vbatchlot = mb_strtoupper($v->dose1_batchno);

                $vdose1yn = 'Y';
                $vdose2yn = 'N';
                $vdose3yn = 'N';
                $vdose4yn = 'N';
            }
            else if($i == 2) {
                
                $vdose1yn = 'N';
                $vdose2yn = 'Y';
                $vdose3yn = 'N';
                $vdose4yn = 'N';
            }
            else if($i == 3) {
                $vdose1yn = 'N';
                $vdose2yn = 'N';
                $vdose3yn = 'Y';
                $vdose4yn = 'N';
            }
            else if($i == 4) {
                $vdose1yn = 'N';
                $vdose2yn = 'N';
                $vdose3yn = 'N';
                $vdose4yn = 'Y';
            }

            $c = $i+1;
            $sheet->setCellValue('A'.$c, $v->category);
            $sheet->setCellValue('B'.$c, ''); //COMORBID
            $sheet->setCellValue('C'.$c, (!is_null($v->vaxcard_uniqueid)) ? $v->vaxcard_uniqueid : 'NONE'); //UNIQUE PERSON ID
            $sheet->setCellValue('D'.$c, $v->pwd_yn); //PWD
            $sheet->setCellValue('E'.$c, 'N'); //INDIGENOUS MEMBER
            $sheet->setCellValue('F'.$c, $v->last_name);
            $sheet->setCellValue('G'.$c, $v->first_name);
            $sheet->setCellValue('H'.$c, (!is_null($v->middle_name)) ? $v->middle_name : '');
            $sheet->setCellValue('I'.$c, (!is_null($v->suffix)) ? $v->suffix : '');
            $sheet->setCellValue('J'.$c, $v->contact_number);
            $sheet->setCellValue('K'.$c, ''); //GUARDIAN NAME
            $sheet->setCellValue('L'.$c, $v->address_region_text); //REGION
            $sheet->setCellValue('M'.$c, 'NONE'); //PROVINCE
            $sheet->setCellValue('N'.$c, 'NONE'); //MUNCITY
            $sheet->setCellValue('O'.$c, $v->address_brgy_text); //BARANGAY
            $sheet->setCellValue('P'.$c, $v->gender);
            $sheet->setCellValue('Q'.$c, date('m/d/Y', strtotime($v->birthdate)));
            $sheet->setCellValue('R'.$c, 'N'); //DEFERRAL
            $sheet->setCellValue('S'.$c, ''); //DEFERRAL REASON
            $sheet->setCellValue('T'.$c, $vdate);
            $sheet->setCellValue('U'.$c, $vbrand);
            $sheet->setCellValue('V'.$c, $vbatchlot);
            $sheet->setCellValue('W'.$c, 'NONE');
            $sheet->setCellValue('X'.$c, 'NONE');
            $sheet->setCellValue('Y'.$c, 'NONE');
            $sheet->setCellValue('Z'.$c, $vdose1yn);
            $sheet->setCellValue('AA'.$c, $vdose2yn);
            $sheet->setCellValue('AB'.$c, $vdose3yn);
            $sheet->setCellValue('AC'.$c, $vdose4yn);
            $sheet->setCellValue('AD'.$c, 'N'); //ADVERSE EVENT
            $sheet->setCellValue('AE'.$c, ''); //ADVERSE EVENT CONDITION
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
}
