<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'familyhead_id',
        'relationship_tohead',
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
        //'outcome',
        //'date_missing',
        //'date_returned',
        //'date_died',
        //'is_injured',
        'is_pwd',
        'is_4ps',
        'is_indg',
        //'cswd_serialno',
        //'dswd_serialno',
        //'remarks',
        'created_by',
        'hash',
    ];
}
