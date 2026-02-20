<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InhouseMaternalCare extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'facility_id',
        'registration_date',
        'highrisk',
        'lmp',
        'gravida',
        'parity',
        'edc',
        'age_group',

        'visit1_est',
        'visit1',
        'visit1_bp',
        'visit1_type',
        'visit2_est',
        'visit2',
        'visit2_bp',
        'visit2_type',
        'visit3_est',
        'visit3',
        'visit3_bp',
        'visit3_type',
        'visit4_est',
        'visit4',
        'visit4_bp',
        'visit4_type',
        'visit5_est',
        'visit5',
        'visit5_bp',
        'visit5_type',
        'visit6_est',
        'visit6',
        'visit6_bp',
        'visit6_type',
        'visit7_est',
        'visit7',
        'visit7_bp',
        'visit7_type',
        'visit8_est',
        'visit8',
        'visit8_bp',
        'visit8_type',
        'completed_8anc',
        'with_highbp',
        'with_dangersign',
        'with_dangersign_specify',
        'dangersign_referred',
        'dangersign_datereferred',

        'height',
        'weight',
        'bmi',
        'nutritional_assessment',

        'trans_remarks',
        'transout_date',

        'td1',
        'td1_type',
        'td2',
        'td2_type',
        'td3',
        'td3_type',
        'td4',
        'td4_type',
        'td5',
        'td5_type',
        'fim_status',
        'td_lastdose_count',
        'td_lastdose_date',
        'deworming_date',

        'ifa1_date',
        'ifa1_dosage',
        'ifa1_type',
        'ifa2_date',
        'ifa2_dosage',
        'ifa2_type',
        'ifa3_date',
        'ifa3_dosage',
        'ifa3_type',
        'ifa4_date',
        'ifa4_dosage',
        'ifa4_type',
        'ifa5_date',
        'ifa5_dosage',
        'ifa5_type',
        'ifa6_date',
        'ifa6_dosage',
        'ifa6_type',
        'completed_ifa',

        'mms1_date',
        'mms1_dosage',
        'mms1_type',
        'mms2_date',
        'mms2_dosage',
        'mms2_type',
        'mms3_date',
        'mms3_dosage',
        'mms3_type',
        'mms4_date',
        'mms4_dosage',
        'mms4_type',
        'mms5_date',
        'mms5_dosage',
        'mms5_type',
        'mms6_date',
        'mms6_dosage',
        'mms6_type',
        'completed_mms',

        'calcium1_date',
        'calcium1_dosage',
        'calcium1_type',
        'calcium2_date',
        'calcium2_dosage',
        'calcium2_type',
        'calcium3_date',
        'calcium3_dosage',
        'calcium3_type',
        'completed_calcium',

        'syphilis_date',
        'syphilis_result',
        'syp_conf_date',
        'syp_conf_result',
        'syp_conf_treat',
        'hiv_date',
        'hiv_result',
        'hb_date',
        'hb_result',
        'cbc_date',
        'cbc_result',
        'diabetes_date',
        'diabetes_result',

        //'pregnancy_terminated_date',
        'delivery_date',
        'outcome',
        'delivery_type',
        'number_livebirths',
        'number_livebirths_toencode',

        'birth_sex',
        'birth_weight',
        'weight_status',

        'place_of_delivery',
        'facility_type',
        'bcemoncapable',
        'nonhealth_type',

        'attendant',
        'attendant_others',

        'pnc1',
        'pnc1_bp',
        'pnc2',
        'pnc2_bp',
        'pnc3',
        'pnc3_bp',
        'pnc4',
        'pnc4_bp',
        'completed_4pnc',
        'pnc_with_highbp',
        'pnc_with_dangersign',
        'pnc_with_dangersign_specify',
        'pnc_dangersign_referred',
        'pnc_dangersign_datereferred',

        'pp_td1',
        'pp_td1_dosage',
        'pp_td2',
        'pp_td2_dosage',
        'pp_td3',
        'pp_td3_dosage',
        'completed_pp_ifa',

        'vita',
        'pp_remarks',
        'pp_transout_date',

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

    public function childcares() {
        return $this->hasMany(InhouseChildCare::class, 'maternalcare_id');
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

    public function runIndicatorUpdate() {
        //Completed 8ANC detector
        if($this->visit1 && $this->visit2 && $this->visit3 && $this->visit4 &&
           $this->visit5 && $this->visit6 && $this->visit7 && $this->visit8) {
            $this->completed_8anc = 'Y';
        }
        else {
            $this->completed_8anc = 'N';
        }

        if($this->td1 && $this->td2 && $this->td3 && $this->td4 && $this->td5) {
            $this->fim_status = 'Y';
        }
        else {
            $this->fim_status = 'N';
        }

        if($this->ifa1_date && $this->ifa2_date && $this->ifa3_date &&
           $this->ifa4_date && $this->ifa5_date && $this->ifa6_date) {
            $this->completed_ifa = 'Y';
        }
        else {
            $this->completed_ifa = 'N';
        }

        if($this->mms1_date && $this->mms2_date && $this->mms3_date &&
           $this->mms4_date && $this->mms5_date && $this->mms6_date) {
            $this->completed_mms = 'Y';
        }
        else {
            $this->completed_mms = 'N';
        }

        if($this->highrisk == 'Y') {
            if($this->calcium1_date && $this->calcium2_date && $this->calcium3_date) {
                $this->completed_calcium = 'Y';
            }
            else {
                $this->completed_calcium = 'N';
            }
        }

        //Td last dose detector
        if(!is_null($this->td5)) {
            $this->td_lastdose_count = 5;
            $this->td_lastdose_date = $this->td5;
        }
        else if(!is_null($this->td4)) {
            $this->td_lastdose_count = 4;
            $this->td_lastdose_date = $this->td4;
        }
        else if(!is_null($this->td3)) {
            $this->td_lastdose_count = 3;
            $this->td_lastdose_date = $this->td3;
        }
        else if(!is_null($this->td2)) {
            $this->td_lastdose_count = 2;
            $this->td_lastdose_date = $this->td2;
        }
        else if(!is_null($this->td1)) {
            $this->td_lastdose_count = 1;
            $this->td_lastdose_date = $this->td1;
        }

        if($this->pnc1 && $this->pnc2 && $this->pnc3 && $this->pnc4) {
            $this->completed_4pnc = 'Y';
        }
        else {
            $this->completed_4pnc = 'N';
        }

        if($this->pp_td1 && $this->pp_td2 && $this->pp_td3) {
            $this->completed_pp_ifa = 'Y';
        }
        else {
            $this->completed_pp_ifa = 'N';
        }
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
