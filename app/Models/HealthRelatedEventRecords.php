<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRelatedEventRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'patient_id',
        'date_onset',
        'admitted',
        'admittedfacility_name',
        'date_admittedconsulted',
        'vog_dizziness',
        'vog_dob',
        'vog_cough',
        'vog_eyeirritation',
        'vog_throatirritation',
        'vog_others',
        'vog_others_specify',
        'outcome',
        'remarks',
        'age_years',
        'age_months',
        'age_days',
    ];
}
