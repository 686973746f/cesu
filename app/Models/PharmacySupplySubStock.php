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
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
