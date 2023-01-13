<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccinationSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'enabled',
    ];
}
