<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacySupplyMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku_code',
        'sku_code_doh',
        'category',
        'description',
        'quantity_type',
        'config_piecePerBox',

        'maxbox_perduration',
        'maxpiece_perduration',
        'duration_days',

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
    
    public function getQtyType() {
        if($this->quantity_type == 'BOX') {
            return 'Boxes';
        }
        else {
            return 'Bottles';
        }
    }
}
