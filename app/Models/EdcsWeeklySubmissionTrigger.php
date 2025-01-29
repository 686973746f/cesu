<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsWeeklySubmissionTrigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'week',
        'created_by',
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
