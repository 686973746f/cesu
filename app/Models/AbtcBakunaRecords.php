<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcBakunaRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'vaccination_site_id',
        'case_id',
        'is_booster',
        'case_date',
        'case_location',
        'animal_type',
        'animal_type_others',
        'if_animal_vaccinated',
        'bite_date',
        'bite_type',
        'body_site',
        'category_level',
        'washing_of_bite',
        'rig_date_given',
        'pep_route',
        'brand_name',
        'd0_date',
        'd0_done',
        'd3_date',
        'd3_done',
        'd7_date',
        'd7_done',
        'd14_date',
        'd14_done',
        'd28_date',
        'd28_done',
        'outcome',
        'biting_animal_status',
        'remarks',
    ];

    public function patient() {
        return $this->belongsTo(AbtcPatient::class, 'patient_id');
    }

    public function patients() {
        return $this->belongsTo(AbtcPatient::class, 'patient_id');
    }

    public function vaccinationsite() {
        return $this->belongsTo(AbtcVaccinationSite::class, 'vaccination_site_id');
    }

    public function ifOldCase() {
        //fetch latest and compare to id
        $latest = AbtcBakunaRecords::where('patient_id', $this->patient->id)->orderBy('created_at', 'DESC')->first();

        if($latest->id == $this->id) {
            return false;
        }
        else {
            return true;
        }
    }

    public function ifAbleToProcessD0() {
        if($this->d0_done == 0) {
            if(date('Y-m-d') == $this->d0_date) {
                return 'Y';
            }
            else {
                if(date('Y-m-d') < $this->d0_date) {
                    return 'N';
                }
                else {
                    return 'D';
                }
            }
        }
        else {
            return 'N';
        }
    }

    public function ifAbleToProcessD3() {
        if($this->d0_done == 1 && $this->d3_done == 0) {
            if(date('Y-m-d') == $this->d3_date) {
                return 'Y';
            }
            else {
                if(date('Y-m-d') >= Carbon::parse($this->d3_date)->addDays(3)->format('Y-m-d')) {
                    if(date('Y-m-d') <= Carbon::parse($this->d3_date)->addDays(3)->format('Y-m-d')) {
                        return 'Y';
                    }
                    else {
                        return 'D';
                    }
                }
                else {
                    return 'N';
                }
            }
        }
        else {
            return 'N';
        }
    }

    public function ifAbleToProcessD7() {
        if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 0) {
            if(date('Y-m-d') == $this->d7_date) {
                return 'Y';
            }
            else {
                if(date('Y-m-d') >= Carbon::parse($this->d7_date)->addDays(2)->format('Y-m-d')) {
                    if(date('Y-m-d') <= Carbon::parse($this->d7_date)->addDays(2)->format('Y-m-d')) {
                        return 'Y';
                    }
                    else {
                        return 'D';
                    }
                }
                else {
                    return 'N';
                }
            }
        }
        else {
            return 'N';
        }
    }

    public function ifAbleToProcessD14() {
        if($this->pep_route == 'ID') {
            if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 0) {
                if(date('Y-m-d') == $this->d14_date) {
                    return 'Y';
                }
                else {
                    if(date('Y-m-d') >= Carbon::parse($this->d14_date)->addDays(2)->format('Y-m-d')) {
                        if(date('Y-m-d') <= Carbon::parse($this->d14_date)->addDays(2)->format('Y-m-d')) {
                            return 'Y';
                        }
                        else {
                            return 'D';
                        }
                    }
                    else {
                        return 'N';
                    }
                }
            }
            else {
                return 'N';
            }
        }
        else {
            return 'N';
        }
    }

    public function ifAbleToProcessD28() {
        if($this->pep_route == 'ID') {
            if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 1 && $this->d28_done == 0) {
                if(date('Y-m-d') == $this->d28_date) {
                    return 'Y';
                }
                else {
                    if(date('Y-m-d') >= Carbon::parse($this->d28_date)->addDays(2)->format('Y-m-d')) {
                        if(date('Y-m-d') <= Carbon::parse($this->d28_date)->addDays(2)->format('Y-m-d')) {
                            return 'D';
                        }
                        else {
                            return 'N';
                        }
                    }
                    else {
                        return 'D';
                    }
                }
            }
            else {
                return 'N';
            }
        }
        else {
            if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d28_done == 0) {
                if(date('Y-m-d') == $this->d28_date) {
                    return 'Y';
                }
                else {
                    if(date('Y-m-d') >= Carbon::parse($this->d28_date)->addDays(2)->format('Y-m-d')) {
                        if(date('Y-m-d') <= Carbon::parse($this->d28_date)->addDays(2)->format('Y-m-d')) {
                            return 'Y';
                        }
                        else {
                            return 'D';
                        }
                    }
                    else {
                        return 'N';
                    }
                }
            }
            else {
                return 'N';
            }
        }
    }
}
