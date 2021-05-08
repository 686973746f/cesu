<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviewers extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'brgy_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function brgy() {
        return $this->hasMany(Brgy::class);
    }
}
