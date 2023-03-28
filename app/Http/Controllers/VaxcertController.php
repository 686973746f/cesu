<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Imports\VaxcertMasterlistImport;
use App\Imports\VaxcertMasterlistImportv2;
use App\Models\CovidVaccinePatientMasterlist;

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
        
    }
}
