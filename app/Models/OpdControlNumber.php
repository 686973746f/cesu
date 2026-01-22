<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdControlNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'control_number',
        'created_by',
        'updated_by',
    ];
}
