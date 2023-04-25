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
