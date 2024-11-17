<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'disaster_id',
        'name',
        'description',
        'address_brgy_code',
        'longlat',
        'status',
        'date_start',
        'date_end',

        'has_electricity',
        'has_water',
        'has_communication',
        'has_internet',
        'rcho_functional',
        'bhs_functional',
        'has_flood',
        'has_landslide',
        'weather',
        'roads_passable',
        'remarks',
        'hash',
        'created_by',
    ];

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }
}
