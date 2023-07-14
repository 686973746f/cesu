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
        'fever_remarks',
        'rash',
        'rash_remarks',
        'cough',
        'cough_remarks',
        'colds',
        'colds_remarks',
        'conjunctivitis',
        'conjunctivitis_remarks',
        'mouthsore',
        'mouthsore_remarks',
        'lossoftaste',
        'lossoftaste_remarks',
        'lossofsmell',
        'lossofsmell_remarks',
        'headache',
        'headache_remarks',
        'jointpain',
        'jointpain_remarks',
        'musclepain',
        'musclepain_remarks',
        'diarrhea',
        'diarrhea_remarks',
        'abdominalpain',
        'abdominalpain_remarks',
        'vomiting',
        'vomiting_remarks',
        'weaknessofextremities',
        'weaknessofextremities_remarks',
        'paralysis',
        'paralysis_remarks',
        'alteredmentalstatus',
        'alteredmentalstatus_remarks',
        'animalbite',
        'animalbite_remarks',
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
}
