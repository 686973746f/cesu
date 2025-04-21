<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icd10Code extends Model
{
    use HasFactory;

    protected $table = 'fhsis_ICD10 CODE';
    public $incrementing = false;

    protected $fillable = [
        'ICD10_CODE',
        'ICD10_DESC',
        'ICD10_CAT',
    ];
}
