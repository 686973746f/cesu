<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventorySubMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'master_id',
        'abtc_facility_id',
        'created_by',
    ];

    public function master() {
        return $this->belongsTo(AbtcInventoryMaster::class, 'master_id');
    }
}
