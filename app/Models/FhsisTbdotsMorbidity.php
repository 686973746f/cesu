<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisTbdotsMorbidity extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'age',
        'sex',
        'brgy',
        'source_of_patient',
        'ana_site',
        'reg_group',
        'bac_status',
        'xpert_result',
        'date_started_tx',
        'outcome',
    ];
}
