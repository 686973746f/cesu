<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExposureHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_date',
        'form_id',
        'primarycc_id',
        'cif_linkid',
        'exposure_date',
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
