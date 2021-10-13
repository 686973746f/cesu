<?php

namespace App\Models;

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
    ];

    public function province() {
        return $this->belongsTo(Provinces::class);
    }
}
