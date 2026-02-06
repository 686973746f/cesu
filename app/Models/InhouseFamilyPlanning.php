<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseFamilyPlanning extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'patient_id',
        'facility_id',
        'registration_date',
        'age_group',

        'client_type',
        'source',
        'previous_method',
        'current_method',
        'is_permanent',
        'is_dropout',
        'dropout_date',
        'dropout_reason',
        'remarks',
        'system_remarks',
        'created_by',
        'updated_by',
        'request_uuid',
        'age_years',
        'age_months',
        'age_days',
        'is_locked',
    ];

    public function patient() {
        return $this->belongsTo(SyndromicPatient::class, 'patient_id');
    }

    public function facility() {
        return $this->belongsTo(DohFacility::class, 'facility_id');
    }

    public function visits() {
        return $this->hasMany(InhouseFpVisit::class, 'fp_tcl_id')->orderByDesc('visit_date_estimated');;
    }

    public function latestVisit() {
        return $this->hasOne(InhouseFpVisit::class, 'fp_tcl_id')
            ->latestOfMany('created_at');
    }
}
