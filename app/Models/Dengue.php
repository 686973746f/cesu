<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dengue extends Model
{
    use HasFactory;

    protected $table = 'dengue';
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

    public function getClassificationString() {
        if($this->CaseClassification == 'C') {
            if($this->is_ns1positive != 1) {
                return 'Confirmed Case';
            }
            else {
                return 'Confirmed Case (NS1 Positive)';
            }
        }
        else if($this->CaseClassification == 'P') {
            return 'Probable Case';
        }
        else {
            return 'Suspected Case';
        }
    }
}
