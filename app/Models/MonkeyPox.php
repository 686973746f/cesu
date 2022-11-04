<?php

namespace App\Models;

use App\Models\Records;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonkeyPox extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'morbidity_month',
        'date_reported',
        'epid_number',
        'date_investigation',
        'dru_name',
        'dru_region',
        'dru_province',
        'dru_muncity',
        'dru_street',

        'type',
        'laboratory_id',

        'informant_name',
        'informant_relationship',
        'informant_contactnumber',

        'date_admitted',
        'admission_er',
        'admission_ward',
        'admission_icu',
        //'date_discharge',

        'ifhashistory_blooddonation_transfusion',
        'ifhashistory_blooddonation_transfusion_place',
        'ifhashistory_blooddonation_transfusion_date',

        'other_medicalinformation',

        'date_onsetofillness',

        'have_cutaneous_rash',
        'have_cutaneous_rash_date',

        'have_fever',
        'have_fever_date',
        'have_fever_days_duration',

        'have_activedisease_lesion_samestate',
        'have_activedisease_lesion_samesize',
        'have_activedisease_lesion_deep',
        'have_activedisease_develop_ulcers',
        'have_activedisease_lesion_type',
        'have_activedisease_lesion_localization',
        'have_activedisease_lesion_localization_otherareas',

        'symptoms_list',
        'symptoms_lymphadenopathy_localization',

        'history1_yn',
        'history1_specify',
        'history1_date_travel',
        'history1_flightno',
        'history1_date_arrival',
        'history1_pointandexitentry',

        'history2_yn',
        'history2_specify',
        'history2_date_travel',
        'history2_flightno',
        'history2_date_arrival',
        'history2_pointandexitentry',

        'history3_yn',

        'history4_yn',
        'history4_typeofanimal',
        'history4_firstexposure',
        'history4_lastexposure',
        'history4_type',
        'history4_type_others',

        'history5_genderidentity',

        'history6_yn',
        'history6_mtm',
        'history6_mtm_nosp',
        'history6_mtf',
        'history6_mtf_nosp',
        'history6_uknown',
        'history6_uknown_nosp',

        'history7_yn',

        'history8_yn',

        'history9_choice',
        'history9_choice_othercountry',

        'test_npsops',
        'test_npsops_date_collected',
        'test_npsops_laboratory',
        'test_npsops_result',
        'test_npsops_date_released',
        'test_lesionfluid',
        'test_lesionfluid_date_collected',
        'test_lesionfluid_laboratory',
        'test_lesionfluid_result',
        'test_lesionfluid_date_released',
        'test_lesionroof',
        'test_lesionroof_date_collected',
        'test_lesionroof_laboratory',
        'test_lesionroof_result',
        'test_lesionroof_date_released',
        'test_lesioncrust',
        'test_lesioncrust_date_collected',
        'test_lesioncrust_laboratory',
        'test_lesioncrust_result',
        'test_lesioncrust_date_released',
        'test_serum',
        'test_serum_date_collected',
        'test_serum_laboratory',
        'test_serum_result',
        'test_serum_date_released',
        
        'health_status',
        'health_status_date_discharged',
        'health_status_final_diagnosis',

        'outcome',
        'outcome_unknown_type',
        'outcome_date_recovered',
        'outcome_date_died',
        'outcome_causeofdeath',
        'case_classification',

        'remarks',
        
        'user_id',
        'records_id',
        'updated_by',
    ];

    public function records() {
        return $this->belongsTo(Records::class);
    }
}
