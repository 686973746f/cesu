<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResultLogBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'for_case_id',
        'disease_tag',
        'lname',
        'fname',
        'mname',
        'suffix',
        'gender',
        'date_collected',
        'collector_name',
        'specimen_type',
        'sent_to_ritm',
        'ritm_date_sent',
        'ritm_date_received',
        'driver_name',
        'test_type',
        'result',
        'interpretation',
        'remarks',
        'facility_id',
    ];
}
