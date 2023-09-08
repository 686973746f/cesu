<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chikv extends Model
{
    use HasFactory;

    protected $table = 'chikv';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
