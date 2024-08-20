<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRelatedEventPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'is_pregnant',
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
        'healthevent_id',
        'reportedby_facility',
        'reportedby_name',
    ];
}
