<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Hfmd extends Model
{
    use HasFactory;

    protected $table = 'hfmd';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'brgy_id');
    }

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

    public function getDateOnset() {
        if(!is_null($this->DONSET)) {
            return $this->DONSET;
        }
        else {
            if(!is_null($this->FeverOnset)) {
                if(!is_null($this->SoreOnset)) {
                    $fever_onset = Carbon::parse($this->FeverOnset);
                    $rash_onset = Carbon::parse($this->SoreOnset);

                    if($fever_onset->gt($rash_onset)) {
                        return $fever_onset->format('Y-m-d');
                    }
                    else {
                        return $rash_onset->format('Y-m-d');
                    }
                }
                else {
                    return $this->FeverOnset;
                }
            }
            else if(!is_null($this->SoreOnset)) {
                if(!is_null($this->FeverOnset)) {
                    $fever_onset = Carbon::parse($this->FeverOnset);
                    $rash_onset = Carbon::parse($this->SoreOnset);

                    if($fever_onset->gt($rash_onset)) {
                        return $fever_onset->format('Y-m-d');
                    }
                    else {
                        return $rash_onset->format('Y-m-d');
                    }
                }
                else {
                    return $this->SoreOnset;
                }
            }
            else {
                return null;
            }
        }
    }
}
