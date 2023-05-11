<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FhsisInfectiousDiseases extends Model
{
    use HasFactory;

    protected $table = 'fhsis_SCHISTOSOMIASIS';

    public $guarded = [];
}
