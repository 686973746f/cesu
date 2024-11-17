<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterResponders extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'evacuation_center_id',
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'sex',
        'bdate',
        'email',
        'contact_number',
        'position',
        'office',
        'bls_trained',
        'duty_started',
        'duty_end',
        'status',
        'remarks',
    ];
}
