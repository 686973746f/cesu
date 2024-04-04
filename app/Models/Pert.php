<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pert extends Model
{
    //Pertussis
    
    use HasFactory;

    protected $table = 'pert';
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

    public function getAgeString() {
        if($this->AgeYears == 0) {
            return $this->AgeMons.' '.Str::plural('month', $this->AgeMons).' old';
        }
        else {
            return $this->AgeYears.' '.Str::plural('year', $this->AgeYears).' old';
        }
    }
}
