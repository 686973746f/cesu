<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvacuationCenterPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'evacuation_center_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'sex',
        'is_pregnant',
        'is_lactating',
        'bdate',
        'email',
        'contact_number',
        'philhealth_number',
        'religion',
        'street_purok',
        'address_brgy_code',
        'is_headoffamily',
        'family_patient_id',
        'is_pwd',
        'outcome',
        'is_injured',
        'longlat',
        'remarks',
        'id_presented',
        'id_file',
        'picture_file',
        'hash',
        'created_by',

        'age_years',
        'age_months',
        'age_days',
    ];

    public function evacuation_center() {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
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

    public function getAge() {
        return Carbon::parse($this->bdate)->age;
    }
}
