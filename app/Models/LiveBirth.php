<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveBirth extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'sex',
        'dob',

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
        
        'hospital_lyingin',
        'mother_age',
        'mode_delivery',
        'multiple_delivery',
    ];
}
