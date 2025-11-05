<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbsCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'student_id',
        'section_id',
        'date_reported',

        'age_years',
        'age_months',
        'age_days',

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

        'outcome',
        'date_senthome',
        'date_recovered',
        
        'reported_by',
        'reported_by_position',
        'reported_by_contactno',

        'from_selfreport',
        'is_approved',
        'approved_date',

        'is_verified',
        'is_sent',
        
        'suspected_disease_tag',
        'sent_disease_tag',
        
        'report_year',
        'report_month',
        'report_week',

        'admitted',
        'date_admitted',
        'admitted_facility',

        'had_checkuponfacilityafter',
        'name_facility',
        'qr',

        'remarks',
        'cesu_remarks',
    ];
}
