<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SbsPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'school_id',
        
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
        'section_id',

        'street_purok',
        'address_brgy_code',

        'contact_no',
        'guardian_name',
        'guardian_contactno',
        'is_pwd',
        'pwd_condition',

        'remarks',

        'created_by',
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
            return $this->section->gradeLevel->level_name;
        }
        else {
            return $this->staff_designation;
        }
    }

    public function school() {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function section() {
        return $this->belongsTo(SchoolSection::class, 'section_id');
    }
}
