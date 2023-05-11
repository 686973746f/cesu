<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisDemographic extends Model
{
    use HasFactory;

    protected $table = 'fhsis_DEMOGRAPHIC PROFILE';

    public $guarded = [];
}
