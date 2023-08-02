<?php

namespace App\Models;

use App\Models\Brgy;
use App\Models\Provinces;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';

    protected $fillable = [
        'province_id',
        'cityName',
        'json_code',
        'alt_name',
    ];

    public function brgy() {
        return $this->hasMany(Brgy::class, 'city_id');
    }

    public function province() {
        return $this->belongsTo(Provinces::class);
    }

    public function getPsgcCode() {
        return $this->json_code.'000';
    }
}
