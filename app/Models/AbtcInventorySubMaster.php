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

    public function facility() {
        return $this->belongsTo(AbtcVaccinationSite::class, 'abtc_facility_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public function getTotalQuantityAvailable() {
        $total = AbtcInventoryStock::where('sub_id', $this->id)->sum('current_qty');

        return $total;
    }
}
