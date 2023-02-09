<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psp extends Model
{
    //Paralytic Shellfish Poisoning
    
    use HasFactory;

    protected $table = 'psp';

    public $guarded = [];
}
