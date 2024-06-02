<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskGenerator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'generate_every',
        'weekly_whatday',
        'monthly_whatday',
        'has_duration',
        'duration_type',
        'duration_daily_whattime',
        'duration_weekly_howmanydays',
        'duration_monthly_howmanymonth',
        'duration_yearly_howmanyyear',
        'encodedcount_enable',
        'has_tosendimageproof',
    ];
}
