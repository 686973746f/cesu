<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsLaboratoryData extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'case_id',
        'case_code',
        'epi_id',
        'sent_to_ritm',
        'specimen_collected_date',
        'specimen_type',
        'date_sent',
        'date_received',
        'result',
        'test_type',
        'interpretation',
        'user_id',
        'timestamp',
        'last_modified_by',
        'last_modified_date',
        'user_regcode',
        'user_provcode',
        'user_citycode',
        'hfhudcode',
    ];
}
