<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaccineCertificateLocal extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_no',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'gender',
        'bdate',
        'contact_number',
        'category',
        'vaxcard_uniqueid',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',

        'process_dose1',
        'dose1_city',
        'dose1_vaccination_date',
        'dose1_vaccine_manufacturer_name',
        'dose1_batch_number',
        //'dose1_lotno',
        'dose1_vaccinator_name',
        'dose1_vaccinator_licenseno',
        'process_dose2',
        'dose2_city',
        'dose2_vaccination_date',
        'dose2_vaccine_manufacturer_name',
        'dose2_batch_number',
        //'dose2_lotno',
        'dose2_vaccinator_name',
        'dose2_vaccinator_licenseno',
        'process_dose3',
        'dose3_city',
        'dose3_vaccination_date',
        'dose3_vaccine_manufacturer_name',
        'dose3_batch_number',
        //'dose3_lotno',
        'dose3_vaccinator_name',
        'dose3_vaccinator_licenseno',
        'process_dose4',
        'dose4_city',
        'dose4_vaccination_date',
        'dose4_vaccine_manufacturer_name',
        'dose4_batch_number',
        //'dose4_lotno',
        'dose4_vaccinator_name',
        'dose4_vaccinator_licenseno',
        'process_dose5',
        'dose5_city',
        'dose5_vaccination_date',
        'dose5_vaccine_manufacturer_name',
        'dose5_batch_number',
        //'dose5_lotno',
        'dose5_vaccinator_name',
        'dose5_vaccinator_licenseno',
        'remarks',
        'hash',
        'created_by',
        'updated_by',
        'facility_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }   
    }

    public function getSalutation() {
        if($this->gender == 'M') {
            return 'MR.';
        }
        else {
            return 'MS.';
        }
    }

    public function getName() {
        $fullname = $this->last_name.", ".$this->first_name;

        if(!is_null($this->middle_name)) {
            $fullname = $fullname." ".$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function getAge() {
        return Carbon::parse($this->bdate)->age;
    }

    public function getFullAddress() {
        return 'BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }

    public function numberOfDoses() {
        $num = 0;

        if($this->process_dose1 == 'Y') {
            $num++;
        }

        if($this->process_dose2 == 'Y') {
            $num++;
        }

        if($this->process_dose3 == 'Y') {
            $num++;
        }
        
        if($this->process_dose4 == 'Y') {
            $num++;
        }

        return $num;
    }

    public function listVaccines() {

    }
}
