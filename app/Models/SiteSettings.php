<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'paswab_enabled',
        'paswab_antigen_enabled',
        'paswab_message_en',
        'paswab_message_fil',
        'oniStartTime_pm',
        'oniStartTime_am',
        'lockencode_enabled',
        'lockencode_start_time',
        'lockencode_end_time',
        'lockencode_positive_enabled',
        'lockencode_positive_start_time',
        'lockencode_positive_end_time',
        'listMobiles',
        'listTelephone',
        'listEmail',
        'listLinkNames',
        'listLinkURL',
        'dilgCustomRespondentName',
        'dilgCustomOfficeName',
        'unvaccinated_days_of_recovery',
        'partialvaccinated_days_of_recovery',
        'fullyvaccinated_days_of_recovery',
        'booster_days_of_recovery',
        'in_hospital_days_of_recovery',
        'severe_days_of_recovery',
        'paswab_auto_schedule_if_symptomatic',
        'cifpage_auto_schedule_if_symptomatic',
        'system_type',
        'default_dru_name',
        'default_dru_region',
        'default_dru_region_json',
        'default_dru_province',
        'default_dru_province_json',
        'default_dru_citymun',
        'default_dru_citymun_json',
        'pidsr_early_sent',

        'default_holiday_dates',
        'custom_holiday_dates',
    ];
}
