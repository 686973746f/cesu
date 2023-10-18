<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccineLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'vaccine_id',
        'branch_id',
        'initial_stock',
        'initial_date',
        'current_stock',
        'patient_dosecount_init',
    ];
}
