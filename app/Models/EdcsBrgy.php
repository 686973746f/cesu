<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsBrgy extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'edcs_code',
        'name',
        'alt_name',
        'brgyNameFhsis',
        'noncomm_customOrderNo',
        'psgc_9digit',
        'psgc_10digit',
    ];

    public function city() {
        return $this->belongsTo(EdcsCity::class, 'city_id');
    }
}
