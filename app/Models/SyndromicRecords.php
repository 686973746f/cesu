<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndromicRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'syndromic_patient_id',
        'opdno',
        'consulation_date',
        'chief_complain',
        'temperature',
        'bloodpressure',
        'weight',
        'respiratoryrate',
        'pulserate',
        'saturationperioxigen',
        'fever',
        'fever_onset',
        'fever_remarks',
        'rash',
        'rash_onset',
        'rash_remarks',
        'cough',
        'cough_onset',
        'cough_remarks',
        'colds',
        'colds_onset',
        'colds_remarks',
        'conjunctivitis',
        'conjunctivitis_onset',
        'conjunctivitis_remarks',
        'mouthsore',
        'mouthsore_onset',
        'mouthsore_remarks',
        'sorethroat',
        'sorethroat_onset',
        'sorethroat_remarks',
        'lossoftaste',
        'lossoftaste_onset',
        'lossoftaste_remarks',
        'lossofsmell',
        'lossofsmell_onset',
        'lossofsmell_remarks',
        'headache',
        'headache_onset',
        'headache_remarks',
        'jointpain',
        'jointpain_onset',
        'jointpain_remarks',
        'musclepain',
        'musclepain_onset',
        'musclepain_remarks',
        'diarrhea',
        'bloody_stool',
        'diarrhea_onset',
        'diarrhea_remarks',
        'abdominalpain',
        'abdominalpain_onset',
        'abdominalpain_remarks',
        'vomiting',
        'vomiting_onset',
        'vomiting_remarks',
        'weaknessofextremities',
        'weaknessofextremities_onset',
        'weaknessofextremities_remarks',
        'paralysis',
        'paralysis_onset',
        'paralysis_remarks',
        'alteredmentalstatus',
        'alteredmentalstatus_onset',
        'alteredmentalstatus_remarks',
        'animalbite',
        'animalbite_onset',
        'animalbite_remarks',
        'anorexia',
        'anorexia_onset',
        'anorexia_remarks',
        'jaundice',
        'jaundice_onset',
        'jaundice_remarks',
        'nausea',
        'nausea_onset',
        'nausea_remarks',
        'fatigue',
        'fatigue_onset',
        'fatigue_remarks',
        'dyspnea',
        'dyspnea_onset',
        'dyspnea_remarks',

        'bigmessage',
        'status',
        'brgy_verified',
        'verified_by',

        'age_years',
        'age_months',
        'age_days',
    ];

    public function syndromic_patient() {
        return $this->belongsTo(SyndromicPatient::class, 'syndromic_patient_id');
    }

    public function getListOfSuspDiseases() {
        $list_arr = [];

        if($this->diarrhea == 1) {
            $list_arr[] = 'Acute Bloody Diarrhea (ABD)';
        }

        if($this->fever == 1 && $this->alteredmentalstatus == 1) {
            $list_arr[] = 'Acute Encephalitis';
        }
        
        
    }

    public function permittedToEdit() {
        
    }
}
