<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anthrax extends Model
{
    use HasFactory;

    protected $table = 'anthrax';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
