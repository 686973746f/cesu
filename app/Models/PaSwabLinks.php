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
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function interviewer() {
        return $this->hasOne(Interviewers::class);
    }
}
