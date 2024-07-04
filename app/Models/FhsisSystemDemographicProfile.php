<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisSystemDemographicProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'encode_date',
        'city_id',
        'brgy_id',
        'for_year',
        'total_brgy',
        'total_bhs',
        'total_mainhc',
        'total_cityhc',
        'total_ruralhc',
        'doctors_lgu',
        'doctors_doh',
        'dentists_lgu',
        'dentists_doh',
        'nurses_lgu',
        'nurses_doh',
        'midwifes_lgu',
        'midwifes_doh',
        'nutritionists_lgu',
        'nutritionists_doh',
        'medtechs_lgu',
        'medtechs_doh',
        'sanitary_eng_lgu',
        'sanitary_eng_doh',
        'sanitary_ins_lgu',
        'sanitary_ins_doh',
        'bhws_lgu',
        'bhws_doh',
        'total_population',
        'total_household',
        'total_livebirths',

        'remarks',

        'created_by',
    ];
}
