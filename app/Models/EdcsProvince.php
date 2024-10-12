<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsProvince extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'edcs_code',
        'name',
    ];
}
