<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseChildNutrition extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'registration_date',
        'length_atbirth',
        'weight_atbirth',
        'weight_status',
        'breastfeeding',
        'lb_iron1',
        'lb_iron2',
        'lb_iron3',
        'vita1',
        'vita2',
        'vita3',
        'mnp1',
        'mnp2',
        'lns1',
        'lns2',

        'mam_identified',
        'enrolled_sfp',
        'mam_cured',
        'mam_noncured',
        'mam_defaulted',
        'mam_died',

        'sam_identified',
        'sam_complication',
        'sam_cured',
        'sam_noncured',
        'sam_defaulted',
        'sam_died',

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
}
