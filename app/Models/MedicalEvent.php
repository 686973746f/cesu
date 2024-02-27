<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'status',
        'description',
        'oneDayEvent',
        'date_start',
        'date_end',
    ];
}
