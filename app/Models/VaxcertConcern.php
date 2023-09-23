<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaxcertConcern extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'vaxcert_refno',
        'category',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'gender',
        'bdate',
        'contact_number',
        'email',
        'comorbidity',
        'pwd_yn',
        'guardian_name',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'dose1_date',
        'dose1_manufacturer',
        'dose1_batchno',
        'dose1_lotno',
        'dose1_inmainlgu_yn',
        'dose1_bakuna_center_text',
        'dose1_vaccinator_name',
        'dose2_date',
        'dose2_manufacturer',
        'dose2_batchno',
        'dose2_lotno',
        'dose2_inmainlgu_yn',
        'dose2_bakuna_center_text',
        'dose2_vaccinator_name',
        'dose3_date',
        'dose3_manufacturer',
        'dose3_batchno',
        'dose3_lotno',
        'dose3_inmainlgu_yn',
        'dose3_bakuna_center_text',
        'dose3_vaccinator_name',
        'dose4_date',
        'dose4_manufacturer',
        'dose4_batchno',
        'dose4_lotno',
        'dose4_inmainlgu_yn',
        'dose4_bakuna_center_text',
        'dose4_vaccinator_name',
        'concern_type',
        'concern_msg',
        'id_file',
        'vaxcard_file',
        'vaxcard_uniqueid',
        'sys_code',
        'use_type',
        'passport_no',
        'user_remarks',
    ];

    public static function getVaccineBrandsList() {
        $vaccine_list = [
            ['code' => 'AZ', 'name' => 'AstraZeneca'],
            ['code' => 'J&J', 'name' => 'Johnson and Johnson (J&J) / Janssen'],
            ['code' => 'Moderna', 'name' => 'Moderna'],
            ['code' => 'ModernaBivalent', 'name' => 'Moderna Bivalent'],
            ['code' => 'Novavax', 'name' => 'Novavax'],
            ['code' => 'Pfizer', 'name' => 'Pfizer'],
            ['code' => 'PfizerBivalent', 'name' => 'Pfizer Bivalent'],
            ['code' => 'Sinopharm', 'name' => 'Sinopharm'],
            ['code' => 'Sinovac', 'name' => 'Sinovac'],
            ['code' => 'SputnikLight', 'name' => 'Sputnik Light'],
            ['code' => 'Gamaleya', 'name' => 'Sputnik V/Gamaleya'],
        ];

        usort($vaccine_list, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $vaccine_list;
    }

    public static function getCbcrList() {
        $gentri_cbcr_list = [
            ['cbcr_code' => 'CBC000000000006637', 'cbcr_name' => 'CITY OF GENERAL TRIAS CONVENTION CENTER'],
            ['cbcr_code' => 'CBC000000000005586', 'cbcr_name' => 'CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER (GENTRIDOCS)'],
            ['cbcr_code' => 'CBC000000000002325', 'cbcr_name' => 'CITY OF GENERAL TRIAS HEALTH OFFICE (CHO GENTRIAS)'],
            ['cbcr_code' => 'CBC000000000009906', 'cbcr_name' => 'DBA VACCINATION FACILITY'],
            ['cbcr_code' => 'CBC000000000005588', 'cbcr_name' => 'DIVINE GRACE MEDICAL HOSPITAL'],
            ['cbcr_code' => 'CBC000000000007978', 'cbcr_name' => 'GEN TRIAS LGU / VISTA MALL GEN TRIAS / RED CROSS CAVITE CHAPTER VACCINATION SITE'],
            ['cbcr_code' => 'CBC000000000007746', 'cbcr_name' => 'GENERAL TRIAS MOBILE VACCINATION CENTER (BARANGAY)'],
            ['cbcr_code' => 'CBC000000000005587', 'cbcr_name' => 'GENTRIMEDICAL CENTER AND HOSPITAL'],
            ['cbcr_code' => 'CBC000000000007459', 'cbcr_name' => 'ROBINSONS PLACE GEN TRIAS BAKUNA CENTER'],
            ['cbcr_code' => 'CBC000000000008481', 'cbcr_name' => 'SSMC GATEWAY VACCINATION CENTER'],
            ['cbcr_code' => 'CBC000000000008932', 'cbcr_name' => 'ST. EDUARD INTEGRATED SCHOOL LNC / LGU GENTRIAS'],
        ];

        usort($gentri_cbcr_list, function($a, $b) {
            return strcmp($a['cbcr_name'], $b['cbcr_name']);
        });

        return $gentri_cbcr_list;
    }

    public function getName() {
        return $this->last_name.', '.$this->first_name.' '.$this->middle_name.' '.$this->suffix;
    }

    public function getAddress() {
        return 'BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }

    public function getAge() {
        return Carbon::parse($this->bdate)->age;
    }

    public function getNumberOfDose() {
        if(is_null($this->dose2_date)) {
            if($this->dose1_manufacturer == 'J&J') {
                return 2;
            }
            else {
                return 1;
            }
        }
        else {
            if(is_null($this->dose3_date)) {
                return 2;
            }
            else {
                if(is_null($this->dose4_date)) {
                    return 3;
                }
                else {
                    return 4;
                }
            }
        }
    }

    public function getProcessedBy() {
        $f = User::find($this->processed_by);

        if($f) {
            return $f->name;
        }
        else {
            return NULL;
        }
    }
}
