<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisSystemDemographicProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'for_year',
        'total_brgy',
        'total_bhs',
        'total_mainhc',
        'total_cityhc',
        'total_ruralhc',
        'doctors_male',
        'doctors_female',
        'dentists_male',
        'dentists_female',
        'nurses_male',
        'nurses_female',
        'midwifes_male',
        'midwifes_female',
        'nutritionists_male',
        'nutritionists_female',
        'medtechs_male',
        'medtechs_female',
        'sanitary_eng_male',
        'sanitary_eng_female',
        'sanitary_ins_male',
        'sanitary_ins_female',
        'bhws_male',
        'bhws_female',
        'total_population',
        'total_household',
        'total_livebirths',
    ];
}
