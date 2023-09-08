<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typhoid extends Model
{
    use HasFactory;

    protected $table = 'typhoid';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
