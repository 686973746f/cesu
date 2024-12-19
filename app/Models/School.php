<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_type',
        'school_id',
        'address_brgy_code',
        'contact_number',
        'contact_number_telephone',
        'schoolhead_name',
        'schoolhead_position',
        'focalperson_name',
        'longlat',
        'qr',
    ];
}
