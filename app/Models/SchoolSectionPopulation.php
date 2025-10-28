<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSectionPopulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'academic_year',
        'count_male',
        'count_female',
        'count_total',
    ];
}
