<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentStatusUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',

        'status',
        'update_type',
        'effective_date',
        'resigned_remarks',
        'terminated_remarks',
        'job_type',
        'job_position',
        'office',
        'sub_office',

        'source',
        'created_by',
        'updated_by',
        'request_uuid',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
