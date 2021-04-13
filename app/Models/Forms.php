<?php

namespace App\Models;

//use App\Models\Records;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forms extends Model
{
    use HasFactory;

    public $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function records() {
        return $this->belongsTo(Records::class);
    }
}
