<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveBirth extends Model
{
    use HasFactory;

    protected $fillable = [
        'registryno',
        'year',
        'month',
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'dob',

        'parent_lname',
        'parent_fname',
        'parent_mname',
        'parent_suffix',

        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'street_purok',
        //'address_houseno',
        
        'hospital_lyingin',
        'mother_age',
        'mode_delivery',
        'multiple_delivery',
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
}
