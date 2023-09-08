<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leptospirosis extends Model
{
    use HasFactory;

    protected $table = 'leptospirosis';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
