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
        'd0_brand',
        'd3_date',
        'd3_done',
        'd3_brand',
        'd7_date',
        'd7_done',
        'd7_brand',
        'd14_date',
        'd14_done',
        'd14_brand',
        'd28_date',
        'd28_done',
        'd28_brand',
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

    public function getschedtype() {
        if($this->d0_date == date('Y-m-d')) {
            return 'NEW';
        }
        else {
            return 'FOLLOW-UP';
        }
    }

    public function getlatestday() {
        if($this->d0_done == 0) {
            return 'D0';
        }
        else if($this->d0_done == 1 && $this->d3_done == 0) {
            return 'D3';
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 0) {
            return 'D7';
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 0 && $this->pep_route == 'IM') {
            return 'D14';
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 1 && $this->d28_done == 0) {
            return 'D28';
        }
    }

    //Supplementary for Quick Done Vaccination on Follow-up Patients
    public function getCurrentDose() {
        if($this->d0_done == 0) {
            return 1;
        }
        else if($this->d0_done == 1 && $this->d3_done == 0) {
            return 2;
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 0) {
            return 3;
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 0 && $this->pep_route == 'IM') {
            return 4;
        }
        else if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->d14_done == 1 && $this->d28_done == 0) {
            return 5;
        }
        else {
            return NULL;
        }
    }

    public function getCreatedBy() {
        if(!is_null($this->created_by)) {
            $a = User::find($this->created_by);
            return $a->name;
        }
        else {
            return 'N/A';
        }
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            $a = User::find($this->updated_by);
        
            return $a->name;
        }
        else {
            return 'N/A';
        }
    }

    public function getGenericName() {
        $d = AbtcVaccineBrand::where('brand_name', $this->brand_name)->first();

        return $d->generic_name;
    }

    public function getSource() {
        if($this->animal_type == 'PD') {
            return 'PET DOG';
        }
        else if($this->animal_type == 'SD') {
            return 'STRAY DOG';
        }
        else if($this->animal_type == 'PC') {
            return 'PET CAT';
        }
        else if($this->animal_type == 'SC') {
            return 'STRAY CAT';
        }
        else if($this->animal_type == 'C') {
            return 'CAT';
        }
        else if($this->animal_type == 'O') {
            return mb_strtoupper($this->animal_type_others);
        }
    }

    public function getBranch() {
        $v = AbtcVaccinationSite::findOrFail($this->vaccination_site_id);

        return $v->site_name;
    }

    public function getBiteType() {
        if($this->bite_type == 'B') {
            return 'BITE';
        }
        else {
            return 'SCRATCH';
        }
    }

    //for Printing Page
    public function showRig() {
        if(!is_null($this->rig_date_given)) {
            return date('m/d/Y', strtotime($this->rig_date_given));
        }
        else {
            if($this->outcome == 'C') {
                return 'N/A';
            }
            else {
                return '__________';
            }
        }
    }

    public function ifCanProcessQuickMark() {
        if($this->getCurrentDose() == 1) {
            //D0
        }
        else if($this->getCurrentDose() == 2) {
            //D3
            if($this->d0_done == 1) {
                return true;
            }
            else {
                return 'Check D0 first to proceed.';
            }
        }
        else if($this->getCurrentDose() == 3) {
            //D7
            if($this->d0_done == 1 && $this->d3_done == 1) {
                return true;
            }
            else {
                return 'Check D3 first to proceed.';
            }
        }
        else if($this->getCurrentDose() == 4) {
            //D14
            if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->is_booster == 0) {
                return true;
            }
            else {
                return 'Check D7 first to proceed.';
            }
        }
        else if($this->getCurrentDose() == 5) {
            //D28
            if($this->d0_done == 1 && $this->d3_done == 1 && $this->d7_done == 1 && $this->is_booster == 0) {
                return true;
            }
            else {
                return 'Check D7 first to proceed.';
            }
        }
    }
}
