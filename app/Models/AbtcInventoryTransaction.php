<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'transaction_date',
        'stock_id',
        'type',
        'process_qty',
        'before_qty',
        'after_qty',
        'po_number',
        'unit_price',
        'unit_price_amount',
        'remarks',
        'created_by',
    ];

    public function stock() {
        return $this->belongsTo(AbtcInventoryStock::class, 'stock_id');
    }
}
