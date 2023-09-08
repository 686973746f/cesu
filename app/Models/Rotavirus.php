<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rotavirus extends Model
{
    use HasFactory;

    protected $table = 'rotavirus';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
