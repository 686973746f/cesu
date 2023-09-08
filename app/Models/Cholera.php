<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cholera extends Model
{
    use HasFactory;

    protected $table = 'cholera';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
