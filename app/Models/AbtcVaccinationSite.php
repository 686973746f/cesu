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
        'referral_code',
        'sched_days',
        'new_start',
        'new_end',
        'ff_start',
        'ff_end',
        'new_and_ff_time_same',
        'facility_type',
    ];
}
