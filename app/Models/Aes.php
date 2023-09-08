<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aes extends Model
{
    //Acute Encephalitis Syndrome
    
    use HasFactory;

    protected $table = 'aes';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
