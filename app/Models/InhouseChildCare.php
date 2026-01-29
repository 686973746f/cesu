<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseChildCare extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'mother_type',
        'maternalcare_id',
        'mother_name',
        'facility_id',
        'registration_date',

        'cpab1',
        'cpab2',

        'bcg1',
        'bcg1_type',
        'bcg2',
        'bcg2_type',
        'hepab1',
        'hepab1_type',
        'hepab2',
        'hepab2_type',
        'dpt1',
        'dpt1_type',
        'dpt2',
        'dpt2_type',
        'dpt3',
        'dpt3_type',
        'opv1',
        'opv1_type',
        'opv2',
        'opv2_type',
        'opv3',
        'opv3_type',
        'ipv1',
        'ipv1_type',
        'ipv2',
        'ipv2_type',
        'ipv3',
        'ipv3_type',
        'pcv1',
        'pcv1_type',
        'pcv2',
        'pcv2_type',
        'pcv3',
        'pcv3_type',
        'mmr1',
        'mmr1_type',
        'mmr2',
        'mmr2_type',

        'system_remarks',
        'remarks',

        'created_by',
        'updated_by',
        'request_uuid',

        'age_years',
        'age_months',
        'age_days',
    ];

    public function patient() {
        return $this->belongsTo(SyndromicPatient::class, 'patient_id');
    }

    public function maternalcare() {
        return $this->belongsTo(InhouseMaternalCare::class, 'maternalcare_id');
    }

    public function isFic() {

    }

    public function isCic() {
        
    }
}
