<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        'enabled',
        'is_verified',
        'is_sent',
        'suspected_disease_tag',
        'report_year',
        'report_month',
        'report_week',

        'had_checkuponfacilityafter',
        'name_facility',
        'qr',
    ];
}
