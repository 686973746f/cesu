<?php

namespace App\Models;

use App\Models\Interviewers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaSwabLinks extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'secondary_code',
        'interviewer_id',
        'enableLockAddress',
        'lock_brgy',
        'lock_city',
        'lock_city_text',
        'lock_province',
        'lock_province_text',
        'lock_subd_array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function interviewer() {
        return $this->belongsTo(Interviewers::class);
    }
}
