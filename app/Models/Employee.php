<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'profession_suffix',
        'gender',
        'bdate',
        'contact_number',
        'email',
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
        'type',
        'job_position',
        'office',
        'sub_office',
        'date_hired',
        'employment_status',
        'date_resigned',
        'remarks',
        'picture_file',
        'fingerprint_hash',
        'is_blstrained',
        'recent_bls_date',
        'bls_id',
        'bls_typeofrescuer',
        'bls_codename',
        'duty_canbedeployed',
        'duty_team',
        'duty_completedcycle',
        'created_by',
        'updated_by',
    ];

    public function getName() {
        return $this->lname.', '.$this->fname;
    }
}
