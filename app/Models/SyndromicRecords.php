<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

        'is_hospitalized',
        'date_admitted',
        'date_released',

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

        if($this->diarrhea == 1 && $this->bloody_stool == 1) {
            $list_arr[] = 'Acute Bloody Diarrhea (ABD)';
        }

        if($this->fever == 1 && $this->alteredmentalstatus == 1) {
            $list_arr[] = 'Acute Encephalitis';
        }

        if($this->fever == 1 && $this->is_hospitalized == 1 && $this->bloody_stool == 1) {
            $list_arr[] = 'Acute Hemorrhagic Fever Syndrome';
        }

        if($this->jaundice == 1 && $this->fatigue == 1 && $this->weaknessofextremities == 1) {
            $list_arr[] = 'Acute Viral Hepatitis';
        }

        if($this->age_years < 15 && $this->paralysis == 1) {
            $list_arr[] = 'Acute Flaccid Paralysis';
        }
 
        if($this->fever == 1) {
            $bdate = Carbon::parse($this->syndromic_patient->fever_onset);
            $dengue_case_date = Carbon::parse($this->consulation_date);

            $dengue_getdays = $bdate->diffInDays($dengue_case_date);

            if($dengue_getdays >= 2 && $dengue_getdays <= 7) {
                $count = 0;

                if($this->headache == 1) {
                    $count++;
                }

                if($this->musclepain == 1) {
                    $count++;
                }

                if($this->anorexia == 1) {
                    $count++;
                }

                if($this->vomiting == 1) {
                    $count++;
                }

                if($this->nausea == 1) {
                    $count++;
                }

                if($this->diarrhea == 1) {
                    $count++;
                }

                if($this->rash == 1) {
                    $count++;
                }

                if($count >= 2) {
                    $list_arr[] = 'Dengue';
                }
            }
        }

        if($this->fever == 1 && $this->rashes == 1 && $this->temperature >= 38) {
            $list_arr[] = 'HFMD';
        }
        
        if($this->fever == 1) {
            if($this->cough == 1 || $this->sorethroat == 1) {
                $list_arr[] = 'Influenza-like Illness (ILI)';
            }
        }

        if($this->musclepain == 1 && $this->fever == 1) {
            if($this->jaundice == 1 || $this->rashes == 1 || $this->nausea == 1 || $this->vomiting == 1 || $this->diarrhea == 1) {
                $list_arr[] = 'Leptospirosis';
            }
        }
    }

    public function permittedToEdit() {
        
    }
}
