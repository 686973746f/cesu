<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolGradeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'type',
        'level_name',
    ];
}
