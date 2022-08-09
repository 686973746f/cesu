<?php

namespace App\Models;

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
        'dru_address',

        'criteria',
        'source',
        'type',
        'laboratory_id',
        
        'date_onset',
        'date_admitted',

        'admission_er',
        'admission_er_date',
        'admission_ward',
        'admission_ward_date',
        'admission_icu',
        'admission_icu_date',
        'date_discharge',

        'q1_yn',
        'q1_specify',
        'q1_date_travel',
        'q1_flightno',
        'q1_date_arrival',
        'q1_pointandexitentry',

        'q2_yn',
        'q2_specify',
        'q2_date_travel',
        'q2_flightno',
        'q2_date_arrival',
        'q2_pointandexitentry',

        'q3_yn',
        'q3_date_onset',

        'q4_yn',
        'q4_date_onset',
        'q4_days_duration',

        'q5_list',
        'q51_yn',
        'q52_yn',
        'q53_yn',
        'q54_yn',

        'q6_localisaiton',
        'q6_otherareas',

        'symptoms_list',

        'hexp_i1_yn',
        'hexp_i1_lname',
        'hexp_i1_fname',
        'hexp_i1_relationship',

        'hexp_i2_yn',
        'hexx_i2_specify',
        'hexp_i2_date',
        'hexp_i2_type_of_contact',
        'hexp_i2_type_of_contact_ifothers',

        'remarks',

        'user_id',
        'records_id',
        'updated_by',
    ];
}
