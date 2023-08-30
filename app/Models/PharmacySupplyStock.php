<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacySupplyStock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supply_id',
        'expiration_date',

        'current_box_stock',
        'current_piece_stock',
    ];

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

    public function pharmacysupply() {
        return $this->belongsTo(PharmacySupply::class, 'supply_id');
    }
}
