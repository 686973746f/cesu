<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsProvince extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'edcs_code',
        'name',
        'geographic_level',
        'psgc_9digit',
        'psgc_10digit',
        'region_9digit',
    ];

    public function region() {
        return $this->belongsTo(Regions::class, 'region_id');
    }
}
