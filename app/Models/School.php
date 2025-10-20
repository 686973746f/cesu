<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class School extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ownership_type',
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
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
