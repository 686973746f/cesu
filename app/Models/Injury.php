<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Injury extends Model
{
    use HasFactory;

    protected $fillable = [
        //'facility_id',
        'date_report',
        'lname',
        'fname',
        'mname',
        'suffix',

        'reported_by',
        'reporter_contactno',

        'oneiss_pno',
        'oneiss_status',
        'oneiss_dataentrystatus',
        'oneiss_patfacilityno',
        'oneiss_regno',
        'oneiss_tempregno',
        
        'hosp_no',
        'hosp_reg_no',
        'hosp_cas_no',

        //'registry_no',
        //'case_no',
        'patient_type',
        
        'sex',
        'bdate',
        'age_years',
        'age_months',
        'age_days',

        'perm_streetpurok',
        'perm_city_code',
        'perm_brgy_code',

        'tempaddress_sameasperm',
        'temp_streetpurok',
        'temp_city_code',
        'temp_brgy_code',

        'contact_no',
        'contact_no2',
        'philhealth',
        'injury_city_code',
        'injury_brgy_code',
        'injury_place',
        'injury_datetime',
        'consultation_datetime',
        'injury_intent',
        'firstaid_given',
        'firstaid_type',
        'firstaid_bywho',
        'multiple_injuries',
        'abrasion',
        'abrasion_site',
        'avulsion',
        'avulsion_site',
        'burn',
        'burn_degree',
        'burn_site',
        'concussion',
        'concussion_site',
        'contusion',
        'contusion_site',
        'fracture',
        'fracture_open',
        'fracture_open_site',
        'fracture_closed',
        'fracture_closed_site',
        'open_wound',
        'open_wound_site',
        'traumatic_amputation',
        'traumatic_amputation_site',
        'others',
        'others_site',
        'bites_stings',
        'bites_stings_specify',
        'ext_burns',
        'ext_burns_type',
        'ext_burns_others_specify',
        'chemical_substance',
        'chemical_substance_specify',
        'contact_sharpobject',
        'contact_sharpobject_specify',
        'drowning',
        'drowning_type',
        'drowning_other_specify',
        'exposure_forcesofnature',
        'fall',
        'fall_specify',
        'firecracker',
        'firecracker_specify',
        'sexual_assault',
        'gunshot',
        'gunshot_specifyweapon',
        'hanging_strangulation',
        'mauling_assault',
        'transport_vehicular_accident',
        'ext_others',
        'ext_others_specify',
        'vehicle_type',
        'collision_type',
        'patients_vehicle_involved',
        'patients_vehicle_involved_others',
        'other_vehicle_involved',
        'other_vehicle_involved_others',
        'patient_position',
        'patient_position_others',
        'placeof_occurrence',
        'placeof_occurrence_workplace_specify',
        'placeof_occurrence_others_specify',
        'activitypatient_duringincident',
        'act_others',
        'otherrisk_factors',
        'oth_factors_specify',
        'safety',
        'safety_others',
        'transfer_hospital',
        'referred_hospital',
        'orig_hospital',
        'orig_physician',
        'status_reachingfacility',
        'ifalive_type',
        'modeof_transport',
        'modeof_transport_others',
        'initial_impression',
        'icd10_nature',
        'icd10_external',
        'disposition',
        'disposition_transferred',
        'outcome',
        'inp_completefinal_diagnosis',
        'inp_disposition',
        'inp_disposition_others',
        'inp_disposition_transferred',
        'inp_outcome',
        'inp_icd10_nature',
        'inp_icd10_external',
        'comments',
        'remarks',
        'qr',
        'created_by',
        'request_uuid',
    ];

    public function getName() {
        if(!is_null($this->lname)) {
            $fullname = $this->lname.", ".$this->fname;

            if(!is_null($this->mname)) {
                $fullname = $fullname." ".$this->mname;
            }

            if(!is_null($this->suffix)) {
                $fullname = $fullname." ".$this->suffix;
            }

            return $fullname;
        }
        else {
            return 'NOT STATED';
        }
        
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function getAgeString() {
        if(!is_null($this->bdate)) {
            if(Carbon::parse($this->attributes['bdate'])->age > 0) {
                return Carbon::parse($this->attributes['bdate'])->age .' '. Str::plural('year', $this->age_years);
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
            if(!is_null($this->age_years)) {
                return $this->age_years .' '. Str::plural('year', $this->age_years);
            }
            else if(!is_null($this->age_months)) {
                return $this->age_months .' '. Str::plural('month', $this->age_months);
            }
            else if(!is_null($this->age_days)) {
                return $this->age_days .' '. Str::plural('month', $this->age_days);
            }
        }
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'perm_brgy_code');
    }

    public function tempbrgy() {
        return $this->belongsTo(EdcsBrgy::class, 'temp_brgy_code');
    }

    public function injurybrgy() {
        return $this->belongsTo(EdcsBrgy::class, 'injury_brgy_code');
    }

    public function injurycity() {
        return $this->belongsTo(EdcsCity::class, 'injury_city_code');
    }

    public function getInjuriesList() {
        $array = [];

        if($this->abrasion == 'Y') {
            $array[] = 'Abrasion';
        }
        if($this->avulsion == 'Y') {
            $array[] = 'Avulsion';
        }
        if($this->burn == 'Y') {
            $array[] = 'Burn';
        }
        if($this->concussion == 'Y') {
            $array[] = 'Concussion';
        }
        if($this->contusion == 'Y') {
            $array[] = 'Contusion';
        }
        if($this->fracture == 'Y') {
            $array[] = 'Fracture';
        }
        if($this->open_wound == 'Y') {
            $array[] = 'Open Wound';
        }
        if($this->traumatic_amputation == 'Y') {
            $array[] = 'Traumatic Amputation';
        }
        if($this->others == 'Y') {
            $array[] = "Others ($this->others_site)";
        }

        return implode(', ', $array);
    }

    public function getInjuryBrgy() {
        if(!is_null($this->injury_brgy_code)) {
            return $this->injurybrgy->name;
        }
        else {
            return 'N/A';
        }
    }
}
