<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidVaccinePatientMasterlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_name',
        'category',
        'comorbidity',
        'unique_person_id',
        'pwd',
        'indigenous_member',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'contact_no',
        'guardian_name',
        'region',
        'province',
        'muni_city',
        'barangay',
        'sex',
        'birthdate',
        'deferral',
        'reason_for_deferral',
        'vaccination_date',
        'vaccine_manufacturer_name',
        'batch_number',
        'lot_no',
        'bakuna_center_cbcr_id',
        'vaccinator_name',
        'first_dose',
        'second_dose',
        'additional_booster_dose',
        'second_additional_booster_dose',
        'adverse_event',
        'adverse_event_condition',
        'row_hash',
    ];
}
