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
        //Excel::import(new VaxcertMasterlistImport(), storage_path('app/vaxcert/masterlistv2.xlsx'));

        /*
        $import = new VaxcertMasterlistImportv2();
    
        Excel::filter('chunk')->load(storage_path('app/vaxcert/masterlistv2.csv'))->chunk(1000, function($results) use ($import) {
            $import->onRow($results);
        });
        */

        $create = (new FastExcel)->import(storage_path('app/vaxcert/masterlistv2.xlsx'), function ($row) {
            if(is_null($row['INDIGENOUS_MEMBER']) || $row['INDIGENOUS_MEMBER'] == 'N' || $row['INDIGENOUS_MEMBER'] == 'NO') {
                $indigent = 'N';
            }
            else {
                $indigent = $row['INDIGENOUS_MEMBER'];
            }
            
            if(is_null($row['ADVERSE_EVENT']) || trim($row['ADVERSE_EVENT']) == 'N' || trim($row['ADVERSE_EVENT']) == 'NONE' || empty($row['ADVERSE_EVENT']) || $row['ADVERSE_EVENT'] == '') {
                $aefiyn = 'N';
            }
            else {
                $aefiyn = $row['ADVERSE_EVENT'];
            }

            if($aefiyn == 'Y') {
                $aefitxt = $row['ADVERSE_EVENT_CONDITION'];
            }
            else {
                $aefitxt = NULL;
            }

            $search = CovidVaccinePatientMasterlist::where('row_hash', $row['ROW_HASH'])->first();

            if(!($search)) {
                return CovidVaccinePatientMasterlist::create([
                    'source_name' => $row['Source.Name'],
                    'category' => $row['CATEGORY'],
                    'comorbidity' => (empty($row['COMORBIDITY']) || $row['COMORBIDITY'] == '') ? NULL : $row['COMORBIDITY'],
                    'unique_person_id' => $row['UNIQUE_PERSON_ID'],
                    'pwd' => (trim($row['PWD']) == 'Y' || trim($row['PWD']) == 'N') ? $row['PWD'] : 'N',
                    'indigenous_member' => $indigent,
                    'last_name' => $row['LAST_NAME'],
                    'first_name' => $row['FIRST_NAME'],
                    'middle_name' => ($row['MIDDLE_NAME'] != "") ? $row['MIDDLE_NAME'] : NULL,
                    'suffix' => ($row['SUFFIX'] != "") ? $row['SUFFIX'] : NULL,
                    'contact_no' => ($row['CONTACT_NO'] != "") ? $row['CONTACT_NO'] : NULL,
                    'guardian_name' => ($row['GUARDIAN_NAME'] != "") ? $row['GUARDIAN_NAME'] : NULL,
                    'region' => $row['REGION'],
                    'province' => $row['PROVINCE'],
                    'muni_city' => $row['MUNI_CITY'],
                    'barangay' => $row['BARANGAY'],
                    'sex' => $row['SEX'],
                    'birthdate' => ($row['BIRTHDATE'] instanceof DateTime) ? date('Y-m-d', strtotime($row['BIRTHDATE']->format('Y-m-d'))) : '1900-01-01',
                    'deferral' => 'N',
                    'reason_for_deferral' => NULL,
                    'vaccination_date' => ($row['VACCINATION_DATE'] instanceof DateTime) ? date('Y-m-d', strtotime($row['VACCINATION_DATE']->format('Y-m-d'))) : '1900-01-01',
                    'vaccine_manufacturer_name' => $row['VACCINE_MANUFACTURER_NAME'],
                    'batch_number' => $row['BATCH_NUMBER'],
                    'lot_no' => $row['LOT_NO'],
                    'bakuna_center_cbcr_id' => $row['BAKUNA_CENTER_CBCR_ID'],
                    'vaccinator_name' => $row['VACCINATOR_NAME'],
                    'first_dose' => $row['FIRST_DOSE'],
                    'second_dose' => $row['SECOND_DOSE'],
                    'additional_booster_dose' => $row['ADDITIONAL_BOOSTER_DOSE'],
                    'second_additional_booster_dose' => $row['SECOND_ADDITIONAL_BOOSTER_DOSE'],
                    'adverse_event' => $aefiyn,
                    'adverse_event_condition' => $aefitxt,
                    'row_hash' => $row['ROW_HASH'],
                ]);
            }
        });
    }
}
