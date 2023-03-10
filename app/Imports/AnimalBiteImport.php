<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\AbtcPatient;
use Illuminate\Support\Str;
use App\Models\AbtcBakunaRecords;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;

class AnimalBiteImport implements ToCollection
{
    private function transformDateTime($value, string $format = 'Y-m-d')
    {
        if(!is_null($value) && $value != 'N/A') {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            } catch (\ErrorException $e) {
                if(strtotime($value)) {
                    return Carbon::parse($value)->format('Y-m-d');
                }
            }
        }
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            $getName = $row[2];
            $parts = explode(',', $getName);
            $getLastName = mb_strtoupper($parts[0]);
            if(isset($parts[1])) {
                $getFirstName = mb_strtoupper(trim($parts[1]));
            }
            else {
                $getFirstName = mb_strtoupper($parts[0]);
            }
            

            $foundunique = false;

            while(!$foundunique) {
                $for_qr = Str::random(20);
                
                $search = AbtcPatient::where('qr', $for_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            $c = AbtcPatient::create([
                'lname' => $getLastName,
                'fname' => $getFirstName,
                'mname' => NULL,
                'suffix' => NULL,
                'bdate' => NULL,
                'age' => (empty($row[5])) ? $row[6] : $row[5],
                'gender' => (empty($row[7]) || $row[7] == '') ? 'FEMALE' : 'MALE', 
                'contact_number' => NULL,
                'philhealth' => NULL,
                'address_region_code' => '04',
                'address_region_text' => 'REGION IV-A (CALABARZON)',
                'address_province_code' => '0421',
                'address_province_text' => 'CAVITE',
                'address_muncity_code' => '042108',
                'address_muncity_text' => 'GENERAL TRIAS',
                'address_brgy_code' => mb_strtoupper(trim($row[4])),
                'address_brgy_text' => mb_strtoupper(trim($row[4])),
                'address_street' => mb_strtoupper($row[3]),
                'address_houseno' => NULL,
                'remarks' => NULL,
                'qr' => $for_qr,

                'ip' => request()->ip(),
                'created_by' => auth()->user()->id,
            ]);

            //get animal type
            if(!empty($row[11]) || $row[11] != '') {
                $getAnimal = $row[11];
            }
            else {
                if($row[12] == 'CAT') {
                    $getAnimal = 'PC';
                }
                else {
                    $getAnimal = $row[12];
                }
            }

            //get category level
            if(!empty($row[15]) || $row[15] != '') {
                $getCate = 1;
            }
            else if(!empty($row[16]) || $row[16] != '') {
                $getCate = 2;
            }
            else if(!empty($row[17]) || $row[17] != '') {
                $getCate = 3;
            }

            if($row[23] == 'BOOSTER' || $row[24] == 'BOOSTER') {
                if(!empty($row[21]) && !empty($row[22])) {
                    $getOutcome = 'C';
                }
                else {
                    $getOutcome = 'INC';
                }
            }
            else {
                if(!empty($row[21]) && !empty($row[22]) && $row[23]) {
                    $getOutcome = 'C';
                }
                else {
                    $getOutcome = 'INC';
                }
            }

            //d14 get
            if($row[23] != 'BOOSTER' || $row[24] != 'BOOSTER') {
                if($row[20] == 'ID') {
                    $getday14 = Carbon::parse(date('Y-m-d', Date::excelToTimestamp($row[21])))->addDays(14)->format('Y-m-d');
                    $getday14done = 0;
                }
                else {
                    $getday14 = date('Y-m-d', Date::excelToTimestamp($row[24]));
                    $getday14done = 1;
                }
            }
            else {
                $getday14 = Carbon::parse(date('Y-m-d', Date::excelToTimestamp($row[21])))->addDays(14)->format('Y-m-d');
                $getday14done = 0;
            }

            $b = AbtcBakunaRecords::create([
                'patient_id' => $c->id,
                'vaccination_site_id' => 2,
                'case_id' => $row[0],
                'is_booster' => ($row[23] == 'BOOSTER' || $row[24] == 'BOOSTER') ? 1 : 0,
                'case_date' => date('Y-m-d', Date::excelToTimestamp($row[1])),
                'case_location' => (!empty($row[10]) || $row[10] != '') ? trim($row[10]) : mb_strtoupper(trim($row[4])),
                'animal_type' => $getAnimal,
                'animal_type_others' => NULL,
                'if_animal_vaccinated' => 0,
                'bite_date' => date('Y-m-d', Date::excelToTimestamp($row[9])),
                'bite_type' => $row[13],
                'body_site' => $row[14],
                'category_level' => $getCate,
                'washing_of_bite' => 1,
                'rig_date_given' => (!empty($row[19]) || $row[19] != '') ? date('Y-m-d', Date::excelToTimestamp($row[19])) : NULL,
                'pep_route' => $row[20],
                'brand_name' => $row[26],
                'd0_date' => date('Y-m-d', Date::excelToTimestamp($row[21])),
                'd0_done' => (!empty($row[21]) || $row[28] != '') ? 1 : 0,
                'd0_brand' => $row[26],
                'd3_date' => (!empty($row[22])) ? date('Y-m-d', Date::excelToTimestamp($row[22])) : Carbon::parse(date('Y-m-d', Date::excelToTimestamp($row[21])))->addDays(3)->format('Y-m-d'),
                'd3_done' => (!empty($row[22]) || $row[22] != '') ? 1 : 0,
                'd3_brand' => $row[26],
                'd7_date' => ($row[23] != 'BOOSTER' && !empty($row[23])) ? date('Y-m-d', Date::excelToTimestamp($row[23])) : Carbon::parse(date('Y-m-d', Date::excelToTimestamp($row[21])))->addDays(7)->format('Y-m-d'),
                'd7_done' => (!empty($row[23]) || $row[23] != '') ? 1 : 0,
                'd7_brand' => $row[26],
                'd14_date' => $getday14,
                'd14_done' => $getday14done,
                'd14_brand' => $row[26],
                'd28_date' => ($row[23] != 'BOOSTER' && $row[24] != 'BOOSTER' && !empty($row[25])) ? date('Y-m-d', Date::excelToTimestamp($row[25])) : Carbon::parse(date('Y-m-d', Date::excelToTimestamp($row[21])))->addDays(28)->format('Y-m-d'),
                'd28_done' => (!empty($row[25]) || $row[25] != '') ? 1 : 0,
                'd28_brand' => $row[26],
                'outcome' => $getOutcome,
                'biting_animal_status' => (!empty($row[28]) || $row[28] != '') ? $row[28] : 'N/A',
                'remarks' => (!empty($row[29]) || $row[29] != '') ? $row[29] : NULL,

                'created_by' => auth()->user()->id,
            ]);
        }
    }
}
