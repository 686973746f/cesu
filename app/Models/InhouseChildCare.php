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

        'cpab',
        'cpab_type',

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
        'dpt1_months',
        'dpt2',
        'dpt2_type',
        'dpt2_months',
        'dpt3',
        'dpt3_type',
        'dpt3_months',
        'opv1',
        'opv1_type',
        'opv1_months',
        'opv2',
        'opv2_type',
        'opv2_months',
        'opv3',
        'opv3_type',
        'opv3_months',
        'ipv1',
        'ipv1_type',
        'ipv1_months',
        'ipv2',
        'ipv2_type',
        'ipv2_months',
        'ipv3',
        'ipv3_type',
        'ipv3_months',
        'pcv1',
        'pcv1_type',
        'pcv1_months',
        'pcv2',
        'pcv2_type',
        'pcv2_months',
        'pcv3',
        'pcv3_type',
        'pcv3_months',
        'mmr1',
        'mmr1_type',
        'mmr1_months',
        'mmr2',
        'mmr2_type',
        'mmr2_months',

        'fic',
        'cic',

        'system_remarks',
        'remarks',

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

    public function maternalcare() {
        return $this->belongsTo(InhouseMaternalCare::class, 'maternalcare_id');
    }

    public function facility() {
        return $this->belongsTo(DohFacility::class, 'facility_id');
    }

    public function runIndicatorUpdate() {
        if(!is_null($this->mmr2)) {
            if($this->mmr2_months <= 12) {
                $this->fic = $this->mmr2;
            }
            else {
                $this->cic = $this->mmr2;
            }

            $this->is_locked = 'Y';
        }
    }

    public function isFic(): bool {
        return !is_null($this->fic);
    }

    public function isCic(): bool {
        return !is_null($this->cic);
    }

    public static function colorFromType(?string $type): string {
        return match ($type) {
            'YOUR BHS' => 'FF000000', // black
            'PUBLIC'    => 'FF008000', // green
            'PRIVATE'  => 'FFFF0000', // red
            'OTHER RHU/BHS' => 'FF0000FF', // blue
            null => 'FFFFFFFF', // white
            default => 'FFFFFFFF', // white
        };
    }
}
