<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyQtyLimitPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'master_supply_id',
        'set_pieces_limit',
        'date_started',
        'date_finished',
    ];
}
