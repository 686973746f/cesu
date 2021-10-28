<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSheetSub extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_sheet_masters_id',
        'forDate',
        'forMeridian',
        'fever',
        'cough',
        'sorethroat',
        'dob',
        'colds',
        'diarrhea',
        'os1',
        'os2',
        'os3',
    ];
}
