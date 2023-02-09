<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nnt extends Model
{
    //Non-Neonatal Tetanus
    
    use HasFactory;

    protected $table = 'nnt';

    public $guarded = [];
}
