<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicLabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'syndromic_record_id',
        'case_code',
        'test_type',
        'test_type_others',
        'manufacturer_name',
        'date_collected',
        'date_transferred',
        'transferred_to',
        'date_received',
        'date_tested',
        'result',
        'result_others_remarks',
        'result_date',
        'interpretation',
        'lab_remarks',
        'remarks',

        'hash_qr',
        'facility_id',

        'morbidity_week',
        'morbidity_month',
        'year',
    ];
}
