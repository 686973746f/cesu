<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyCartMainBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'branch_id',
        'processor_branch_id',
        'remarks',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pharmacycartsub() {
        return $this->hasMany(PharmacyCartSubBranch::class, 'main_cart_id');
    }
}
