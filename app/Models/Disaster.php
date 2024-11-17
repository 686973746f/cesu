<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'name',
        'city_id',
        'date_start',
        'date_end',
        'status',
        'hash',
        'created_by',
    ];
}
