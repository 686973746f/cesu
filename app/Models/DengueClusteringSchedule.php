<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DengueClusteringSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'morbidity_week',
        'brgy_id',
        'purok_subdivision',
        'assigned_team',
        'status',
        'cycle1_date',
        'cycle2_date',
        'cycle3_date',
        'cycle4_date',
        'created_by',
    ];
}
