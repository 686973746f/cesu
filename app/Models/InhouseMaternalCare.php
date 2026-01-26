<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseMaternalCare extends Model
{
    use HasFactory;

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
        'visit1_type',
        'visit2_est',
        'visit2',
        'visit2_type',
        'visit3_est',
        'visit3',
        'visit3_type',
        'visit4_est',
        'visit4',
        'visit4_type',
        'visit5_est',
        'visit5',
        'visit5_type',
        'visit6_est',
        'visit6',
        'visit6_type',
        'visit7_est',
        'visit7',
        'visit7_type',
        'visit8_est',
        'visit8',
        'visit8_type',
        'completed_8anc',

        'height',
        'weight',
        'bmi',
        'nutritional_assessment',

        'trans_remarks',

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

        'birth_weight',
        'weight_status',

        'place_of_delivery',
        'facility_type',
        'bcemoncapable',
        'nonhealth_type',

        'attendant',
        'attendant_others',

        'pnc1',
        'pnc2',
        'pnc3',
        'pnc4',
        'completed_4pnc',

        'pp_td1',
        'pp_td1_dosage',
        'pp_td2',
        'pp_td2_dosage',
        'pp_td3',
        'pp_td3_dosage',
        'pp_td4',
        'pp_td4_dosage',
        'completed_pp_ifa',

        'vita',
        'pp_remarks',

        'system_remarks',

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

    public function facility() {
        return $this->belongsTo(DohFacility::class, 'facility_id');
    }
}
