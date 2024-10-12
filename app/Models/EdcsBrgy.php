<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsBrgy extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'edcs_code',
        'name',
    ];
}
