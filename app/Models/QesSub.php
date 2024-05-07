<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QesSub extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'qes_main_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'age',
        'sex',
        'contact_number',
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
        'occupation',
        'placeof_work_school',

        'has_symptoms',
        'onset_datetime',
        'illness_duration',
        'diagnosis_date',
        'hospitalized',
        'admission_date',
        'discharge_date',
        'hospital_name',
        'outcome',
        'lbm_3xday',
        'fever',
        'nausea',
        'vomiting',
        'bodyweakness',
        'abdominalcramps',
        'rectalpain',
        'tenesmus',
        'bloodystool',
        'brownish',
        'yellowish',
        'greenish',
        'others',
        'others_specify',
        'volumeofstool',
        'quantify',
        'other_affected_names',
        'other_affected_ages',
        'other_affected_sex',
        'other_affected_donset',
        'question1',
        'question2',
        'question3',
        'question4',
        'question5',
        'question5_souce',
        'question5_others',
        'question6',
        'question6_where',
        'question6_source',
        'question7',
        'question7_others',
        'question8',
        'question9',
        'question10',
        'question11',
        'question12',
        'am_snacks_names',
        'am_snacks_datetime',
        'lunch_names',
        'lunch_datetime',
        'pm_snacks_names',
        'pm_snacks_datetime',
        'dinner_names',
        'dinner_datetime',
        'remarks',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }

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

    public function getStreetPurok() {
        if(!is_null($this->address_street) || !is_null($this->address_houseno)) {
            if(!is_null($this->address_street) && !is_null($this->address_houseno)) {
                return $this->address_street.' '.$this->address_houseno;
            }
            else {
                if(!is_null($this->address_street)) {
                    return $this->address_street;
                }
                else {
                    return $this->address_houseno;
                }
            }
        }
        else {
            return 'N/A';
        }
    }
}
