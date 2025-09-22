<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterFamilyHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'sex',
        'is_pregnant',
        'is_lactating',
        'bdate',
        'birthplace',
        'cs',
        'religion',
        'occupation',
        'mothermaiden_name',
        'monthlyfamily_income',
        'is_pwd',
        'is_4ps',
        'is_indg',
        'indg_specify',
        'id_presented',
        'id_number',
        'id_file',
        'picture_file',
        'email',
        'contact_number',
        'contact_number2',
        'philhealth_number',
        'street_purok',
        'address_brgy_code',
        'house_ownership',
        'cswd_serialno',
        'dswd_serialno',
        'created_by',
        'hash',
    ];
}
