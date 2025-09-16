<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterPatientMembers extends Model
{
    use HasFactory;

    protected $fillable = [
        'familyhead_id',
        'relationship_tohead',
        'date_registered',
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'bdate',
        'sex',
        'is_pregnant',
        'is_lactating',
        'highest_education',
        'occupation',
        'outcome',
        'date_missing',
        'date_returned',
        'date_died',
        'is_injured',
        'is_pwd',
        'is_4ps',
        'is_indg',
        'indg_specify',
        'age_years',
        'age_months',
        'age_days',
        'cswd_serialno',
        'dswd_serialno',
        'remarks',
        'created_by',
        'updated_by',
        'hash',
    ];
}
