<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicLabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'syndromic_record_id',
        'test_for_disease',
        'test_type',
        'test_type_others',
        'manufacturer_name',
        'date_collected',
        'date_transferred',
        'transferred_to',
        'date_received',
        'date_released',
        'result',
        'result_others_remarks',
        'remarks',
    ];
}
