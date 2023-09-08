<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahf extends Model
{
    //Acute Hemorrhagic Fever
    
    use HasFactory;

    protected $table = 'ahf';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
