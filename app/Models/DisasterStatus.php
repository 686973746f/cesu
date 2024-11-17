<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisasterStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'evacuation_center_id',
        'update_time',

        'no_families',
        'no_individuals',
        'no_underfive',
        'no_male',
        'no_female',
        'no_pregnant',
        'no_lactating',
        'no_senior',
        'no_pwd',
        'age_1',
        'age_2',
        'age_3',
        'age_4',
        'age_5',
        'medicalneeds_age1',
        'medicalneeds_age2',
        'medicalneeds_age3',
        'has_electricity',
        'has_water',
        'has_communication',
        'has_internet',
        'rcho_functional',
        'bhs_functional',
        'has_flood',
        'has_landslide',
        'weather',
        'roads_passable',
        'email_sent',
        'remarks',
        'created_by',
    ];
}
