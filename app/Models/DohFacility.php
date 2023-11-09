<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DohFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'healthfacility_code',
        'healthfacility_code_short',
        'facility_name',
        'facility_name_old1',
        'facility_name_old2',
        'facility_name_old3',
        'major_type',
        'facility_type',
        'ownership_type',
        'subclassification_government',
        'subclassification_private',
        'address_street',
        'address_building',
        'address_region',
        'address_region_psgc',
        'address_province',
        'address_province_psgc',
        'address_muncity',
        'address_muncity_psgc',
        'address_barangay',
        'address_barangay_psgc',
        'zip_code',
        'landline',
        'landline2',
        'fax',
        'email',
        'email2',
        'website',
        'service_capability',
        'bed_capacity',
        'licensing_status',
        'validity_date',
    ];
}
