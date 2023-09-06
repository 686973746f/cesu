<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacySupplySubStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'subsupply_id',
        'expiration_date',
        'batch_number',
        'lot_number',
        'current_box_stock',
        'current_piece_stock',

        'created_by',
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

    public function pharmacysub() {
        return $this->belongsTo(PharmacySupplySub::class, 'subsupply_id');
    }
}
