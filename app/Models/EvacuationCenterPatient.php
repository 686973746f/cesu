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
        'date_registered',
        'cswd_serialno',
        'lname',
        'fname',
        'mname',
        'suffix',
        //'nickname',
        'bdate',
        'sex',
        'is_pregnant',
        'is_lactating',
        'cs',
        
        'email',
        'contact_number',
        'contact_number2',
        'philhealth_number',
        'religion',
        'occupation',
        'street_purok',
        'address_brgy_code',
        //'is_headoffamily',
        //'family_patient_id',
        'id_presented',
        'id_number',
        'id_file',
        'is_pwd',
        'is_injured',
        'is_4ps',
        'is_indg',
        'outcome',
        'family_status',
        'longlat',
        'remarks',

        'house_ownership',
        'shelterdamage_classification',
        
        'picture_file',
        'hash',
        'created_by',

        'age_years',
        'age_months',
        'age_days',

        'focal_name',
    ];

    public function evacuation_center() {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
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
