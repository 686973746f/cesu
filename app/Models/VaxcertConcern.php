<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'dose1_bakuna_center_text',
        'dose1_vaccinator_name',
        'dose2_date',
        'dose2_manufacturer',
        'dose2_batchno',
        'dose2_lotno',
        'dose2_bakuna_center_text',
        'dose2_vaccinator_name',
        'dose3_date',
        'dose3_manufacturer',
        'dose3_batchno',
        'dose3_lotno',
        'dose3_bakuna_center_text',
        'dose3_vaccinator_name',
        'dose4_date',
        'dose4_manufacturer',
        'dose4_batchno',
        'dose4_lotno',
        'dose4_bakuna_center_text',
        'dose4_vaccinator_name',
        'concern_type',
        'concern_msg',
        'id_file',
        'vaxcard_file',
        'vaxcard_uniqueid',
        'sys_code',
    ];

    public function getName() {
        return $this->last_name.', '.$this->first_name.' '.$this->middle_name.' '.$this->suffix;
    }

    public function getAddress() {
        return 'BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }

    public function getNumberOfDose() {
        if(is_null($this->dose2_date)) {
            return 1;
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
}
