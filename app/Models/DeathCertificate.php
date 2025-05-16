<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeathCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'if_fetaldeath',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'date_died',
        'age_death_years',
        'age_death_months',
        'age_death_days',
        'fetald_dateofdelivery',
        'fetald_typeofdelivery',
        'fetald_ifmultipledeliveries_fetuswas',
        'fetald_methodofdelivery',
        'fetald_methodofdelivery_others',
        'fetald_birthorder',
        'fetald_fetusweight',
        'fetald_fetusdiedwhen',
        'fetald_lenghthpregnancyweeks',
        'fetald_mother_lname',
        'fetald_mother_fname',
        'fetald_mother_mname',
        'name_placeofdeath',
        'pod_insidecity',
        'pod_address_region_code',
        'pod_address_region_text',
        'pod_address_province_code',
        'pod_address_province_text',
        'pod_address_muncity_code',
        'pod_address_muncity_text',
        'pod_address_brgy_code',
        'pod_address_brgy_text',
        'pod_address_street',
        'pod_address_houseno',
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
        'maternal_condition',
        'immediate_cause',
        'antecedent_cause',
        'underlying_cause',
        'created_by',
        'updated_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getName() {
        $fullname = $this->lname.", ".$this->fname;

        if(!is_null($this->mname)) {
            $fullname = $fullname." ".$this->mname;
        }

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function getPlaceOfResidence() {
        return 'BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }

    public function getPlaceOfDeathString() {
        return $this->name_placeofdeath.' (BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text.')';
    }
}
