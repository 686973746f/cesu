<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measles extends Model
{
    use HasFactory;

    protected $table = 'measles';

    public $guarded = [];
}
