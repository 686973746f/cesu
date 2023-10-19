<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccineLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'wastage_dose_count',
    ];
}
