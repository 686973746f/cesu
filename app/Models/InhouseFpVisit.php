<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseFpVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'fp_tcl_id',
        'method_used',
        'visit_date_estimated',
        'visit_date_actual',
        'status',
        'created_by',
        'updated_by',
        'age_years',
        'age_months',
        'age_days',
        'request_uuid',
    ];
}
