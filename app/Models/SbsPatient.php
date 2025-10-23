<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SbsPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'date_reported',
        
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'bdate',
        'age_years',
        'age_months',
        'age_days',

        'patient_type',
        'staff_designation',
        'grade_level',
        'section',
        'street_purok',
        'address_brgy_code',

        'contact_no',
        'guardian_name',
        'guardian_contactno',
        'is_pwd',
        'pwd_condition',

        'height',
        'weight',
        'bp_systolic',
        'bp_diastolic',
        'had_dinner_yesterday',
        'had_breakfast_today',
        'had_lunch_today',
        'onset_illness_date',
        'signs_and_symptoms',
        'fever_temperature',
        'signs_and_symptoms_others',
        'remarks',

        'reported_by',
        'reported_by_position',
        'reported_by_contactno',

        'admitted',
        'date_admitted',
        'admitted_facility',

        'enabled',
        'from_selfreport',
        'is_approved',
        'approved_date',

        'is_verified',
        'is_sent',
        'source',
        'approved_date',
        'suspected_disease_tag',
        'sent_disease_tag',
        
        'report_year',
        'report_month',
        'report_week',

        'had_checkuponfacilityafter',
        'name_facility',
        'qr',
    ];

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

    public function getAgeInt() {
        return Carbon::parse($this->attributes['bdate'])->age;
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }

    public function getGradeOrDesignation() {
        if($this->patient_type == 'STUDENT') {
            return $this->grade_level;
        }
        else {
            return $this->staff_designation;
        }
    }

    public function school() {
        return $this->belongsTo(School::class, 'school_id');
    }
}
