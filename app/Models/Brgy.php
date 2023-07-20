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
        'user_id',
        'city_id',
        'brgyName',
        'displayInList',
        'json_code',
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }
}
