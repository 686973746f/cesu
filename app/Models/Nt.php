<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nt extends Model
{
    //Neonatal Tetanus
    
    use HasFactory;

    protected $table = 'nt';

    public $guarded = [];
}
