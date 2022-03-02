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
        'total_active_halfvax',
        'total_active_fullvax',
        'total_recoveries',
        'total_recoveries_halfvax',
        'total_recoveries_fullvax',
        'total_deaths',
        'total_deaths_halfvax',
        'total_deaths_fullvax',
        'new_cases',
        'new_cases_halfvax',
        'new_cases_fullvax',
        'late_cases',
        'late_cases_halfvax',
        'late_cases_fullvax',
        'new_recoveries',
        'new_recoveries_halfvax',
        'new_recoveries_fullvax',
        'late_recoveries',
        'late_recoveries_halfvax',
        'late_recoveries_fullvax',
        'new_deaths',
        'new_deaths_halfvax',
        'new_deaths_fullvax',
        'total_all_confirmed_cases',
        'total_all_suspected_probable_cases',
        'facility_one_count',
        'facility_two_count',
        'hq_count',
        'hospital_count',
        'active_asymptomatic_count',
        'active_mild_with_comorbid_count',
        'active_mild_without_comorbid_count',
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
