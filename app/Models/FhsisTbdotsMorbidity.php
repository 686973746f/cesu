<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisTbdotsMorbidity extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'validation_status',
        'screening_date',
        'diagnosis_date',
        'notification_date',
        'case_number',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'age',
        'age_months',
        'age_days',
        'sex',
        'brgy',
        'source_of_patient',
        'ana_site',
        'reg_group',
        'bac_status',
        'xpert_result',
        'rdt_release_date',
        'date_started_tx',
        'outcome',
        'date_of_outcomestatus',
        'datetime_record_was_created',
    ];
}
