<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FwInjury extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'facility_code',
        'hospital_name',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'contact_number',
        'contact_number2',
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
        'injury_date',
        'consultation_date',
        'reffered_anotherhospital',
        'nameof_hospital',
        'place_of_occurrence',
        'place_of_occurrence_others',
        'injury_address_region_code',
        'injury_address_region_text',
        'injury_address_province_code',
        'injury_address_province_text',
        'injury_address_muncity_code',
        'injury_address_muncity_text',
        'injury_address_brgy_code',
        'injury_address_brgy_text',
        'injury_address_street',
        'injury_address_houseno',
        'involvement_type',
        'nature_injury',
        'iffw_typeofinjury',
        'complete_diagnosis',
        'anatomical_location',
        'firework_name',
        'liquor_intoxication',
        'treatment_given',
        'treatment_given_transferred_hospital',
        'disposition_after_consultation',
        'disposition_after_consultation_transferred_hospital',
        'date_died',
        'aware_healtheducation_list',
    ];
}
