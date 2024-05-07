<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QesMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'name',
        'description',
        'date_start',
        'date_end',
        'remarks',
        'facility_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
