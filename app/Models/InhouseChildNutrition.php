<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InhouseChildNutrition extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'registration_date',
        'length_atbirth',
        'weight_atbirth',
        'weight_status',
        'breastfeeding',
        
        'nutrition2_date',
        'length_atnutrition2',
        'weight_atnutrition2',
        'weight_status_atnutrition2',

        'lb_iron1',
        'lb_iron2',
        'lb_iron3',

        'exclusive_breastfeeding1',
        'exclusive_breastfeeding2',
        'exclusive_breastfeeding3',

        'nutrition3_date',
        'length_atnutrition3',
        'weight_atnutrition3',
        'weight_status_atnutrition3',

        'exclusive_breastfeeding_4',
        'complementary_feeding',
        'cf_type',

        'vita1',
        'vita2',
        'vita3',

        'mnp1',
        'mnp2',

        'lns1',
        'lns2',

        'nutrition4_date',
        'length_atnutrition4',
        'weight_atnutrition4',
        'weight_status_atnutrition4',

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

        'bdate_fixed',
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

    public function allowedToEdit() {
        if(auth()->user()->isMasterAdminEtcl()) {
            return true;
        }
        else {
            if(auth()->user()->etcl_bhs_id == $this->facility_id) {
                return true;
            }
            else {
                return false;
            }
        }
    }
}
