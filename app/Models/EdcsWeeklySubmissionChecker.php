<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsWeeklySubmissionChecker extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_name',
        'year',
        'week',
        'status',
        'waive_status',
        'waive_date',
    ];
}
