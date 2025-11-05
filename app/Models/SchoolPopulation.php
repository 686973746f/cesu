<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPopulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'section_id',
        'pop_male',
        'pop_female',
    ];
}
