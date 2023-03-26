<?php

namespace App\Imports;

use App\Models\CovidVaccinePatientMasterlist;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VaxcertMasterlistImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if($row[5] == 'N' || $row[5] == 'NO') {
                $indigent = 'NO';
            }
            else {
                $indigent = $row[5];
            }

            if(trim($row[30]) == 'N' || trim($row[30]) == 'NONE' || empty($row[30]) || $row[30] == '') {
                $aefiyn = 'N';
            }
            else {
                $aefiyn = $row[30];
            }

            if($aefiyn == 'Y') {
                $aefitxt = $row[31];
            }
            else {
                $aefitxt = NULL;
            }

            $c = CovidVaccinePatientMasterlist::create([
                'source_name' => $row[0],
                'category' => $row[1],
                'comorbidity' => (empty($row[2]) || $row[2] == '') ? NULL : $row[2],
                'unique_person_id' => $row[3],
                'pwd' => (trim($row[4]) == 'Y' || trim($row[4]) == 'N') ? $row[4] : 'N',
                'indigenous_member' => $indigent,
                'last_name' => $row[6],
                'first_name' => $row[7],
                'middle_name' => $row[8],
                'suffix' => (empty($row[9]) || $row[9] == '') ? NULL : $row[9],
                'contact_no' => (empty($row[10]) || $row[10] == '') ? NULL : $row[10],
                'guardian_name' => (empty($row[11]) || $row[11] == '') ? NULL : $row[11],
                'region' => $row[12],
                'province' => $row[13],
                'muni_city' => $row[14],
                'barangay' => $row[15],
                'sex' => $row[16],
                'birthdate' => $row[17],
                'deferral' => 'N',
                'reason_for_deferral' => 'NONE',
                'vaccination_date' => $row[20],
                'vaccine_manufacturer_name' => $row[21],
                'batch_number' => $row[22],
                'lot_no' => $row[23],
                'bakuna_center_cbcr_id' => $row[24],
                'vaccinator_name' => $row[25],
                'first_dose' => $row[26],
                'second_dose' => $row[27],
                'additional_booster_dose' => $row[28],
                'second_additional_booster_dose' => $row[29],
                'adverse_event' => $aefiyn,
                'adverse_event_condition' => $aefitxt,
                'row_hash' => $row[32],
            ]);
        }
        
    }

    public function startRow(): int {
        return 2;
    }
}
