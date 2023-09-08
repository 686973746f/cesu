<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afp extends Model
{
    //Acute Flaccid Paralysis
    
    use HasFactory;

    protected $table = 'afp';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
