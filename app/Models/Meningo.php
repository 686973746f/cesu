<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meningo extends Model
{
    use HasFactory;

    protected $table = 'meningo';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
