<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyCartMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'patient_id',
        'branch_id',
        'prescription_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pharmacycartsub() {
        return $this->hasMany(PharmacyCartSub::class, 'main_cart_id');
    }
}
