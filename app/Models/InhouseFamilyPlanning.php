<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InhouseFamilyPlanning extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function visibleVisits() {
        return $this->hasMany(InhouseFpVisit::class, 'fp_tcl_id')
            ->where('is_visible', 'Y')
            ->orderByDesc('visit_date_estimated');
    }

    public function visits() {
        return $this->hasMany(InhouseFpVisit::class, 'fp_tcl_id')->orderByDesc('visit_date_estimated');
    }

    public function latestVisit() {
        return $this->hasOne(InhouseFpVisit::class, 'fp_tcl_id')
            ->latestOfMany('created_at');
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

    public function getClientType($client_type) {
        if($client_type == 'NA') {
            return 'New Acceptor';
        }
        else if($client_type == 'CU') {
            return 'Current User';
        }
        else if($client_type == 'OA') {
            return 'Other Acceptor';
        }
        else if($client_type == 'CU-CM' || $client_type == 'OA-CM') {
            return 'Changing Method';
        }
        else if($client_type == 'CU-CC' || $client_type == 'OA-CC') {
            return 'Changing Clinic';
        }
        else if($client_type == 'CU-RS' || $client_type == 'OA-RS') {
            return 'Restarter';
        }
        else if($client_type == 'OA-CA') {
            return 'Changing Age';
        }
        else {
            return 'None';
        }
    }

    public function getMethod($method) {
        if($method == 'BTL') {
            return 'Bilateral Tubal Ligation';
        }
        else if($method == 'NSV') {
            return 'No Scalpel Vasectomy';
        }
        else if($method == 'CON') {
            return 'Condoms';
        }
        else if($method == 'PILLS-POP') {
            return 'Progestin Only Pills';
        }
        else if($method == 'PILLS-COC') {
            return 'Combined Oral Contraceptives';
        }
        else if($method == 'INJ') {
            return 'Injectables (DMPA)';
        }
        else if($method == 'IMP-I') {
            return 'Implant (Interval)';
        }
        else if($method == 'IMP-PP') {
            return 'Implant (Postpartum)';
        }
        else if($method == 'IUD-I') {
            return 'IUD Interval';
        }
        else if($method == 'IUD-PP') {
            return 'IUD Postpartum';
        }
        else if($method == 'NFP-LAM') {
            return 'Lactational Amenorrhea Method';
        }
        else if($method == 'NFP-BBT') {
            return 'Basal Body Temperature Method';
        }
        else if($method == 'NFP-CMM') {
            return 'Cervical Mucus Method';
        }
        else if($method == 'NFP-STM') {
            return 'Standard Days Method';
        }
        else if($method == 'NFP-SDM') {
            return 'Sympto-Thermal Method';
        }
        else {
            return 'None';
        }
    }

    public function getDropOutReason($reason_code) {
        if($reason_code == 'A') {
            return 'Pregnant';
        }
        else if($reason_code == 'B') {
            return 'Desire to become pregnant';
        }
        else if($reason_code == 'C') {
            return 'Medical complications';
        }
        else if($reason_code == 'D') {
            return 'Fear of side effects';
        }
        else if($reason_code == 'E') {
            return 'Changed Clinic';
        }
        else if($reason_code == 'F') {
            return 'Husband/Partner disapproves';
        }
        else if($reason_code == 'G') {
            return 'Menopause';
        }
        else if($reason_code == 'H') {
            return 'Lost or moved out of the area or residence';
        }
        else if($reason_code == 'I') {
            return 'Failed to get supply';
        }
        else if($reason_code == 'J') {
            return 'Change Method';
        }
        else if($reason_code == 'K') {
            return 'Underwent hysterectomy';
        }
        else if($reason_code == 'L') {
            return 'Underwent Bilateral Salpingo-oophorectomy';
        }
        else if($reason_code == 'M') {
            return 'No FP commodity';
        }
        else if($reason_code == 'N') {
            return 'Unknown';
        }
        else if($reason_code == 'O') {
            return 'Age out for BTL';
        }
        else if($reason_code == 'P') {
            return 'Change of Age';
        }
        else if($reason_code == 'LAM_A') {
            return 'Mother has a menstruation or not amenorrheic within 6 months';
        }
        else if($reason_code == 'LAM_B') {
            return 'No longer practicing fully/exclusively breastfeeding';
        }
        else if($reason_code == 'LAM_C') {
            return 'Baby is more than six (6) months old';
        }
        else {
            return $this->dropout_reason;
        }
    }
}
