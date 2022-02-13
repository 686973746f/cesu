<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCases extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_date',
        'type',
        'total_active',
        'total_recoveries',
        'total_deaths',
        'new_cases',
        'late_cases',
        'new_recoveries',
        'late_recoveries',
        'new_deaths',
        'total_all_confirmed_cases',
        'total_all_suspected_probable_cases',
        'facility_one_count',
        'facility_two_count',
        'hq_count',
        'hospital_count',
        'active_asymptomatic_count',
        'active_mild_count',
        'active_moderate_count',
        'active_severe_count',
        'active_critical_count',
        'active_male_count',
        'active_female_count',
        'active_agegroup1_count',
        'active_agegroup2_count',
        'active_agegroup3_count',
        'active_agegroup4_count',
        'active_agegroup5_count',
        'active_agegroup6_count',
        'reinfection_active',
        'reinfection_recovered',
        'reinfection_deaths',
        'reinfection_total',
    ];
}
