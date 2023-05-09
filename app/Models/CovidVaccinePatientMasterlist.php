<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CovidVaccinePatientMasterlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_name',
        'category',
        'comorbidity',
        'unique_person_id',
        'pwd',
        'indigenous_member',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'contact_no',
        'guardian_name',
        'region',
        'province',
        'muni_city',
        'barangay',
        'sex',
        'birthdate',
        'deferral',
        'reason_for_deferral',
        'vaccination_date',
        'vaccine_manufacturer_name',
        'batch_number',
        'lot_no',
        'bakuna_center_cbcr_id',
        'vaccinator_name',
        'first_dose',
        'second_dose',
        'additional_booster_dose',
        'second_additional_booster_dose',
        'adverse_event',
        'adverse_event_condition',
        'row_hash',
    ];

    public function showDoseType() {
        $str = '';

        if($this->first_dose == 'Y') {
            $str = $str.'(1st Dose) ';
        }

        if($this->second_dose == 'Y') {
            if($this->vaccine_manufacturer_name == 'J&J') {
                $str = $str.'(1st & 2nd Dose) ';
            }
            else {  
                $str = $str.'(2nd Dose) ';
            }
        }

        if($this->additional_booster_dose == 'Y') {
            $str = $str.'(3rd Dose) ';
        }

        if($this->second_additional_booster_dose == 'Y') {
            $str = $str.'(4th Dose)';
        }

        return $str;
    }

    public function getAge() {
        return Carbon::parse($this->birthdate)->age;
    }
}
