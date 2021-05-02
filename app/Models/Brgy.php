<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brgy extends Model
{
    use HasFactory;

    protected $table = 'brgy';

    protected $fillable = [
        'brgyName'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }
}
