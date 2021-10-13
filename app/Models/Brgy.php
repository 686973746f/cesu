<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brgy extends Model
{
    use HasFactory;

    protected $table = 'brgy';

    protected $fillable = [
        'brgyName',
        'city_id',
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }
}
