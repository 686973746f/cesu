<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicDoctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_name',
        'dru_name',
        'position',
        'reg_no',
    ];
}
