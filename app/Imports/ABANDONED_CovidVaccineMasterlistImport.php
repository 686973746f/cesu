<?php

namespace App\Imports;

use App\Models\CovidVaccinePatientMasterlist;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CovidVaccineMasterlistImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            $create = CovidVaccinePatientMasterlist::updateOrCreate([
                'row_hash' => $row['row_hash'],
            ], [
                'category' => $row['category'],
                'comorbidity' => $row['comorbidity'],
                'unique_person_id' => $row['unique_person_id'],
                'pwd' => $row['pwd'],
                'indigenous_member' => (is_null($row['indigenous_member'])) ? 'NO' : $row['indigenous_member'],
                'last_name' => $row['last_name'],
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'suffix' => $row['suffix'],
                'contact_no' => $row['contact_no'],
                'guardian_name' => $row['guardian_name'],
                'region' => $row['region'],
                'province' => $row['province'],
                'muni_city' => $row['muni_city'],
                'barangay' => $row['barangay'],
                'sex' => $row['sex'],
                'birthdate' => $row['birthdate'],
                'deferral' => 'N',
                'reason_for_deferral' => NULL,
                'vaccination_date' => $row['vaccination_date'],
                'vaccine_manufacturer_name' => $row['vaccine_manufacturer_name'],
                'batch_number' => $row['batch_number'],
                'lot_no' => $row['lot_no'],
                'bakuna_center_cbcr_id' => $row['bakuna_center_cbcr_id'],
                'vaccinator_name' => $row['vaccinator_name'],
                'first_dose' => $row['first_dose'],
                'second_dose' => $row['second_dose'],
                'additional_booster_dose' => $row['additional_booster_dose'],
                'second_additional_booster_dose' => $row['second_additional_booster_dose'],
                'adverse_event' => (is_null($row['adverse_event'])) ? 'N' : $row['adverse_event'],
                'adverse_event_condition' => ($row['adverse_event'] != 'N') ? $row['adverse_event_condition'] : NULL,
                'row_hash' => $row['row_hash']
            ]);
        }
    }
}
