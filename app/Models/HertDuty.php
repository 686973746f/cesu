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
        'code',
        'cycle_number',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members() {
        return $this->hasMany(HertDutyMember::class, 'event_id'); // event_id is FK to hert_duties.id
    }
}
