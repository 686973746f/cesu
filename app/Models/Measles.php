<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Measles extends Model
{
    use HasFactory;

    protected $table = 'measles';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];

    public function getEdcsFacilityName() {
        $s = DohFacility::where('healthfacility_code', $this->edcs_healthFacilityCode)->first();

        if($s) {
            return $s->facility_name;
        }
        else {
            return 'UNKNOWN';
        }
    }

    public function getName() {
        $full = $this->FamilyName.', '.$this->FirstName;

        if(!is_null($this->middle_name)) {
            $full = $full.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $full = $full.' '.$this->suffix;
        }

        return $full;
    }

    public function displayAgeStringToReport() {
        if($this->AgeYears == 0) {
            if($this->AgeMons == 0) {
                return $this->AgeDays.' '.Str::plural('day', $this->AgeDays).' old';
            }
            else {
                return $this->AgeMons.' '.Str::plural('month', $this->AgeMons).' old';
            }
        }
        else {
            return $this->AgeYears.' '.Str::plural('year', $this->AgeYears).' old';
        }
    }

    public function getStreetPurok() {
        if(!is_null($this->Streetpurok)) {
            return $this->Streetpurok;
        }
        else {
            return 'Street/Purok not Encoded';
        }
    }

    public function listSymptoms() {
        $final_arr = [];

        if($this->fever == 'Y') {
            $final_arr[] = 'Fever';
        }

        if($this->Rash == 'Y') {
            $final_arr[] = 'Rash';
        }

        if($this->RunnyNose == 'Y') {
            $final_arr[] = 'Runny Nose/Coryza';
        }

        if($this->Cough == 'Y') {
            $final_arr[] = 'Cough';
        }
        
        if($this->RedEyes == 'Y') {
            $final_arr[] = 'Red Eyes (Conjunctivitis)';
        }
        
        if(!empty($final_arr)) {
            return implode(', ', $final_arr);
        }
        else {
            return NULL;
        }
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'brgy_id');
    }
}
