<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'edcs_code',
        'name',
        'alt_name',
        'geographic_level',
        'city_class',
        'psgc_9digit',
        'psgc_10digit',
    ];

    public function province() {
        return $this->belongsTo(EdcsProvince::class, 'province_id');
    }
}
