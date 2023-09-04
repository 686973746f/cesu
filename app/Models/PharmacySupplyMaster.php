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
        'category',
        'description',
        'quantity_type',
        'config_piecePerBox',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
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
