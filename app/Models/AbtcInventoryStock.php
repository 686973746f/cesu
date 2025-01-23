<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventoryStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'sub_id',
        'batch_no',
        'expiry_date',
        'source',

        'current_qty',
        'created_by',
    ];

    public function submaster() {
        return $this->belongsTo(AbtcInventorySubMaster::class, 'sub_id');
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
}
