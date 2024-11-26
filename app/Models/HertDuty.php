<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HertDuty extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'status',

        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
