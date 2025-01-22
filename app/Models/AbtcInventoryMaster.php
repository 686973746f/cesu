<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventoryMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'name',
        'description',
        'uom',
        'created_by',
    ];
}
