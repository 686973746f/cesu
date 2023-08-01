<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SyndromicRecords extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'other_symptoms',
        'other_symptoms_onset',
        'other_symptoms_onset_remarks',

        'is_hospitalized',
        'date_admitted',
        'date_released',

        'bigmessage',
        'name_of_interviewer',
        'name_of_physician',
        'dru_name',
        'status',
        'brgy_verified',
        'brgy_verified_date',
        'brgy_verified_by',

        'cesu_verified',
        'cesu_verified_date',
        'cesu_verified_by',

        'age_years',
        'age_months',
        'age_days',

        'outcome',
        'outcome_recovered_date',
        'outcome_died_date',

        'document_file',

        'qr',
    ];

    public function syndromic_patient() {
        return $this->belongsTo(SyndromicPatient::class, 'syndromic_patient_id');
    }

    public function listSymptoms() {
        $list = [];

        if($this->fever == 1) {
            $list[] = 'Fever';
        }
        if($this->rash == 1) {
            $list[] = 'Rash';
        }
        if($this->cough == 1) {
            $list[] = 'Cough';
        }
        if($this->colds == 1) {
            $list[] = 'Colds';
        }
        if($this->conjunctivitis == 1) {
            $list[] = 'Conjunctivitis';
        }
        if($this->mouthsore == 1) {
            $list[] = 'Mouth sore';
        }
        if($this->sorethroat == 1) {
            $list[] = 'Sore throat';
        }
        if($this->lossoftaste == 1) {
            $list[] = 'Loss of Taste';
        }
        if($this->lossofsmell == 1) {
            $list[] = 'Loss of Smell';
        }
        if($this->headache == 1) {
            $list[] = 'Headache';
        }
        if($this->jointpain == 1) {
            $list[] = 'Joint Pain';
        }
        if($this->musclepain == 1) {
            $list[] = 'Muscle Pain';
        }
        if($this->diarrhea == 1) {
            if($this->bloody_stool == 1) {
                $list[] = 'Diarrhea (Bloody Stool)';
            }
            else {
                $list[] = 'Diarrhea';
            }
        }
        if($this->abdominalpain == 1) {
            $list[] = 'Abdominal Pain';
        }
        if($this->vomiting == 1) {
            $list[] = 'Vomiting';
        }
        if($this->weaknessofextremities == 1) {
            $list[] = 'Weakness of Extremities';
        }
        if($this->paralysis == 1) {
            $list[] = 'Paralysis';
        }
        if($this->alteredmentalstatus == 1) {
            $list[] = 'Altered Mental Status';
        }
        if($this->animalbite == 1) {
            $list[] = 'Animal Bite';
        }
        if($this->anorexia == 1) {
            $list[] = 'Anorexia (Eating Disorder)';
        }
        if($this->jaundice == 1) {
            $list[] = 'Jaundice';
        }
        if($this->nausea == 1) {
            $list[] = 'Nausea';
        }
        if($this->fatigue == 1) {
            $list[] = 'Fatigue';
        }
        if($this->dyspnea == 1) {
            $list[] = 'Dyspnea';
        }
        if($this->other_symptoms == 1) {
            $list[] = 'Others ('.$this->other_symptoms_onset_remarks.')';
        }

        return implode(", ", $list);
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
            if(!is_null($this->fever_onset)) {
                $bdate = Carbon::parse($this->fever_onset);
            }
            else {
                $bdate = Carbon::parse($this->created_at);
            }
            
            $dengue_case_date = Carbon::parse($this->consultation_date);

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

        if($this->fever == 1 || $this->cough == 1) {
            $list_arr[] = 'COVID-19';
        }

        return implode(", ", $list_arr);
    }

    public function permittedToEdit() {
        
    }
}
