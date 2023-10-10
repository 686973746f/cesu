<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyCartSubBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_cart_id',
        'subsupply_id',
        'qty_to_process',
        'type_to_process',
    ];

    public function pharmacycartmain() {
        return $this->belongsTo(PharmacyCartMainBranch::class, 'main_cart_id');
    }

    public function pharmacysub() {
        return $this->belongsTo(PharmacySupplySub::class, 'subsupply_id');
    }
}
