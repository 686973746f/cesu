<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SevereAcuteRespiratoryInfection extends Model
{
    use HasFactory;

    public $guarded = [];
    
    public function getFullName() {
        $getFullName = $this->lname.', '.$this->fname;

        if(!is_null($this->middle_name)) {
            $getFullName = $getFullName.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $getFullName = $getFullName.' '.$this->suffix;
        }

        return $getFullName;
    }

    public function getName() {
        $getFullName = $this->lname.', '.$this->fname;

        if(!is_null($this->middle_name)) {
            $getFullName = $getFullName.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $getFullName = $getFullName.' '.$this->suffix;
        }

        return $getFullName;
    }

    public function displayAgeStringToReport() {
        if($this->age_years == 0) {
            if($this->age_months == 0) {
                return $this->age_days.' '.Str::plural('day', $this->age_days).' old';
            }
            else {
                return $this->age_months.' '.Str::plural('month', $this->age_months).' old';
            }
        }
        else {
            return $this->age_years.' '.Str::plural('year', $this->age_years).' old';
        }
    }

    public function getStreetPurok() {
        if(!is_null($this->streetpurok)) {
            return $this->streetpurok;
        }
        else {
            return 'Street/Purok not Encoded';
        }
    }
}
