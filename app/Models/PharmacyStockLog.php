<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyStockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subsupply_id',
        'type',
        'get_stock_box',
        'get_stock_piece',
        'stock_credit_box',
        'stock_debit_box',
        'stock_credit_piece',
        'stock_debit_piece',
    ];
}
