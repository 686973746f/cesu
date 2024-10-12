<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'edcs_code',
        'name',
    ];
}
