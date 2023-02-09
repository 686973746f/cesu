<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rabies extends Model
{
    use HasFactory;

    protected $table = 'rabies';

    public $guarded = [];
}
