<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExposureHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'is_primarycc',
        'is_secondarycc',
        'is_tertiarycc',
        'is_primarycc_date',
        'is_secondarycc_date',
        'is_tertiarycc_date',
        'is_primarycc_date_set',
        'is_secondarycc_date_set',
        'is_tertiarycc_date_set',
        'user_id',
        'updated_by',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function form() {
        return $this->belongsTo(Forms::class);
    }
}
