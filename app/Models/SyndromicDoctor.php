<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicDoctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_name',
        'dru_name',
        'gender',
        'bdate',
        'position_ref',
        'position',

        'hired_by',
        'employment_status',
        'active_in_service',
        'current_user',
        
        'reg_no',
        'ptr_no',
        'phic_no',
        'phic_accre_code',
        's2_license',
        'tin_no',

        'catchment_brgy_list', //IF MIDWIFE
    ];
}
