<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\City;
use App\Models\Regions;
use App\Models\Provinces;
use Faker\Provider\sv_SE\Municipality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FwInjury extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_by',
        'report_date',
        'facility_code',
        'account_type',
        'hospital_name',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'contact_number',
        'contact_number2',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'address_street',
        'address_houseno',
        'injury_date',
        'consultation_date',
        'reffered_anotherhospital',
        'nameof_hospital',
        'place_of_occurrence',
        'place_of_occurrence_others',
        'injury_sameadd',
        'injury_address_region_code',
        'injury_address_region_text',
        'injury_address_province_code',
        'injury_address_province_text',
        'injury_address_muncity_code',
        'injury_address_muncity_text',
        'injury_address_brgy_code',
        'injury_address_brgy_text',
        'injury_address_street',
        'injury_address_houseno',
        'involvement_type',
        'nature_injury',
        'iffw_typeofinjury',
        'complete_diagnosis',
        'anatomical_location',
        'firework_name',
        'firework_illegal',
        'liquor_intoxication',
        'treatment_given',
        'disposition_after_consultation',
        'disposition_after_consultation_transferred_hospital',

        'disposition_after_admission',
        'disposition_after_admission_transferred_hospital',

        'date_died',
        'aware_healtheducation_list',
        
        'age_years',
        'age_months',
        'age_days',

        'status',
        'remarks',
        'sent',
    ];

    public function getName() {
        $fullname = $this->lname.", ".$this->fname;

        if(!is_null($this->mname)) {
            $fullname = $fullname." ".$this->mname;
        }

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function getAge() {
        if(!is_null($this->bdate)) {
            if(Carbon::parse($this->attributes['bdate'])->age > 0) {
                return Carbon::parse($this->attributes['bdate'])->age;
            }
            else {
                if (Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m') == 0) {
                    return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%d DAYS');
                }
                else {
                    return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
                }
            }
        }
        else {
            return $this->age;
        }
    }

    public function sg() {
        return substr($this->gender,0,1);
    }

    public function getAgeInt() {
        return Carbon::parse($this->bdate)->age;
    }

    public function getStreetPurok() {
        if($this->address_houseno && $this->address_street) {
            $get_txt = $this->address_houseno.', '.$this->address_street;
        }
        else if($this->address_houseno || $this->address_street) {
            if($this->address_houseno) {
                $get_txt = $this->address_houseno;
            }
            else if($this->address_street) {
                $get_txt = $this->address_street;
            }
        }
        else {
            $get_txt = 'N/A';
        }
        
        return $get_txt;
    }

    public function getCompleteAddress() {
        $get_txt = '';

        if($this->address_houseno || $this->address_street) {
            $get_txt = $this->getStreetPurok();
        }
        
        $get_txt = $get_txt.' BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;

        return $get_txt;
    }

    public function getInjuryStreetPurok() {
        if($this->address_houseno && $this->address_street) {
            $get_txt = $this->address_houseno.', '.$this->address_street;
        }
        else if($this->address_houseno || $this->address_street) {
            if($this->address_houseno) {
                $get_txt = $this->address_houseno;
            }
            else if($this->address_street) {
                $get_txt = $this->address_street;
            }
        }
        else {
            $get_txt = 'N/A';
        }
        
        return $get_txt;
    }

    public function getInjuryAddress() {
        $get_txt = '';

        if($this->address_houseno || $this->address_street) {
            $get_txt = $this->getInjuryStreetPurok();
        }
        
        $get_txt = $get_txt.' BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;

        return $get_txt;
    }

    public function getBrgyCode() {
        $getRegion = Regions::where('regionName', $this->address_region_text)->first();

        $getProvince = Provinces::where('region_id', $getRegion->id)
        ->where('provinceName', $this->address_muncity_text)
        ->first();

        $getMuncity = City::where('province_id', $getProvince->id)
        ->where('cityName', $this->address_muncity_text)
        ->first();

        $getBrgy = Brgy::where('city_id', $getMuncity->id)
        ->where('brgyName', $this->injury_address_brgy_text)
        ->first();

        return $getBrgy->json_code;
    }

    public function getInjuryAddStr() {
        if($this->injury_sameadd == 'Y') {
            return 'SAME AS CURRENT ADDRESS';
        }
        else {
            return $this->getInjuryAddress();
        }
    }
}
