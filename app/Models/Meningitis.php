<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meningitis extends Model
{
    use HasFactory;

    protected $table = 'meningitis';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
