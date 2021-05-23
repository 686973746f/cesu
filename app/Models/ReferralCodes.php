<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCodes extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'refCode',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function company() {
        return $this->belongsTo(Companies::class);
    }
}
