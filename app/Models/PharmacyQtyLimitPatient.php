<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyQtyLimitPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_id',
        'patient_id',
        'qty_limit',
    ]
}
