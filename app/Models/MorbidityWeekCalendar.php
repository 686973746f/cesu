<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MorbidityWeekCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'mw',
        'start_date',
        'end_date',
    ];

    public function getPreviousWeek() {
        return MorbidityWeekCalendar::findOrFail($this->id - 1);
    }
}
