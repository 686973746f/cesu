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
}
