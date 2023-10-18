<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccineStocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'wastage_dose_count',
    ];
}
