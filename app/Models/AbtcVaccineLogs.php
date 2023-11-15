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
        'patients_count',
        'vials_used',
        'wastage_dose_count',
        'stocks_remaining',
        'created_at',
    ];
}
