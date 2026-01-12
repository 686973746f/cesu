<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MorbidityWeekCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'mw',
        'start_date',
        'end_date',
    ];
}
