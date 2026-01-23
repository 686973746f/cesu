<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseChildCare extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'registration_date',
        'cpab1',
        'cpab2',
        'bcg1',
        'bcg2',
        'hepab1',
        'hepab2',
        'dpt1',
        'dpt2',
        'dpt3',
        'opv1',
        'opv2',
        'opv3',
        'ipv1',
        'ipv2',
        'ipv3',
        'pcv1',
        'pcv2',
        'pcv3',
        'mmr1',
        'mmr2',
        'remarks',
        'created_by',
        'updated_by',
        'request_uuid',
    ];

    public function patient() {
        return $this->belongsTo(SyndromicPatient::class, 'patient_id');
    }

    public function isFic() {

    }

    public function isCic() {
        
    }
}
