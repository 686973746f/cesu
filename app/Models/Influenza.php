<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influenza extends Model
{
    use HasFactory;

    protected $table = 'influenza';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];
}
