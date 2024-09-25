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
        'date_investigation',
        'laboratory_id',
        'epi_id',
        'enabled',
        'match_casedef',
        'dru_name',
        'dru_region',
        'dru_province',
        'dru_muncity',
        'dru_street',
        'dru_type',
        'patient_number',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'is_pregnant',
        'is_pregnant_weeks',
        'other_medical_information',
        'is_ip',
        'is_ip_specify',
        'nationality',
        'contact_number',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'address_street',
        'address_houseno',
        'perm_address_region_code',
        'perm_address_region_text',
        'perm_address_province_code',
        'perm_address_province_text',
        'perm_address_muncity_code',
        'perm_address_muncity_text',
        'perm_address_brgy_code',
        'perm_address_brgy_text',
        'perm_address_street',
        'perm_address_houseno',
        'occupation',
        'workplace_name',
        'workplace_address',
        'workplace_contactnumber',
        'informant_name',
        'informant_relationship',
        'informant_contactnumber',
        'date_admitted_seen_consulted',
        'admission_er',
        'admission_ward',
        'admission_icu',
        'ifhashistory_blooddonation_transfusion',
        'ifhashistory_blooddonation_transfusion_place',
        'ifhashistory_blooddonation_transfusion_date',
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
        'brgy_remarks',
        'age_years',
        'age_months',
        'age_days',
        'morbidity_month',
        'morbidity_week',
        'year',
        'gps_x',
        'gps_y',
        'created_by',
        'updated_by',
    ];

    public function getName() {
        $full = $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            $full = $full.' '.$this->mname;
        }

        if(!is_null($this->suffix)) {
            $full = $full.' '.$this->suffix;
        }

        return $full;
    }
}
