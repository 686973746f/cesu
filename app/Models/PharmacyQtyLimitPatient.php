<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyQtyLimitPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'finished',
        'master_supply_id',
        'patient_id',
        'set_pieces_limit',
        'date_started',
        'date_finished',
    ]
}
