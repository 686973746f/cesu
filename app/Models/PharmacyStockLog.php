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
        'get_stock',
        'stock_credit',
        'stock_debit',
    ];
}
