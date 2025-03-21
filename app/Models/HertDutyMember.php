<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HertDutyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'employee_id',
        'locked_in',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function event() {
        return $this->belongsTo(HertDuty::class, 'event_id');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
