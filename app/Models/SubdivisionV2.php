<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubdivisionV2 extends Model
{
    use HasFactory;

    protected $fillable = [
        'brgy_id',
        'name',
    ];
}
