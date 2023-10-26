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
        'line_number',
        'last_checkup_date',
        'consultation_date',
        'nature_of_visit',
        'consultation_type',
        'checkup_type',
        'chief_complain',
        'rx_outsidecho',
        'outsidecho_name',
        'temperature',
        'bloodpressure',
        'weight',
        'height',
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

        'dcnote',
        'diagnosis_type',
        'dcnote_assessment',
        'main_diagnosis',
        'dcnote_plan',
        'dcnote_diagprocedure',
        'other_diagnosis',
        'rx', //NOT BEING USED ANYMORE
        'remarks',

        'prescribe_option',
        'prescription_list',

        'name_of_interviewer',
        'name_of_physician',
        'other_doctor',
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

        'medcert_enabled',
        'medcert_generated_date',
        'medcert_validity_date',
        'medcert_start_date',
        'medcert_end_date',
    ];

    public static function refConsultationType() {
        $array = [
            'Adult Immunization',
            'Animal Bite',
            'Child Care',
            'Child Immunization',
            'Child Nutrition',
            'Covid Form',
            'Dental Care',
            'Family Planning',
            'Firecracker Injury',
            'General',
            'Injury',
            'Post Partum',
            'Prenatal',
            'Sick Children',
            'Tuberculosis',
        ];

        return $array;
    }

    public function syndromic_patient() {
        return $this->belongsTo(SyndromicPatient::class, 'syndromic_patient_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public function getPharmacyDetails() {
        $f = PharmacyPatient::where('itr_id', $this->syndromic_patient_id)
        ->latest()
        ->first();

        return $f;
    }

    public function getCesuVerified() {
        if($this->cesu_verified == 1) {
            return date('m/d/Y h:i A', strtotime($this->cesu_verified_date)).' by '.$this->getCesuVerifiedBy->name;
        }
        else {
            return 'NO';
        }
    }
    
    public function getCesuVerifiedBy() {
        return $this->belongsTo(User::class, 'cesu_verified_by');
    }

    public function getBrgyVerified() {
        if($this->brgy_verified == 1) {
            return date('m/d/Y h:i A', strtotime($this->brgy_verified_date)).' by '.$this->getBrgyVerifiedBy->name;
        }
        else {
            return 'NO';
        }
    }

    public function getBrgyVerifiedBy() {
        return $this->belongsTo(User::class, 'brgy_verified_by');
    }

    /*
    public function canAccessRecord() {
        $perm_list = explode(',', auth()->user()->permission_list);

        if(in_array('GLOBAL_ADMIN', $perm_list) || in_array('ITR_ADMIN', $perm_list) || in_array('ITR_ENCODER', $perm_list)) {
            return true;
        }
        else {
            if($this->syndromic_patient->address_brgy_text == auth()->user()->brgy->brgyName && $this->syndromic_patient->address_muncity_text == auth()->user()->brgy->city->cityName) {
                return true;
            }
            else {
                return false;
            }
        }
    }
    */

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

        if(!is_null($this->other_symptoms_onset_remarks) && $this->other_symptoms == 1) {
            $osymp_list = explode(",", mb_strtoupper($this->other_symptoms_onset_remarks));
        }
        else {
            $osymp_list = [];
        }

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

        if($this->sorethroat == 1) {
            if($this->fever == 1 || $this->rash == 1) {
                $list_arr[] = 'HFMD';
            }
        }

        /*
        if($this->fever == 1 || $this->temperature >= 38) {
            if($this->rash == 1) {
                $list_arr[] = 'HFMD';
            }
        }
        */
        
        if($this->fever == 1) {
            if($this->cough == 1 || $this->sorethroat == 1) {
                $list_arr[] = 'Influenza-like Illness (ILI)';
            }
        }

        if($this->musclepain == 1 && $this->fever == 1) {
            if($this->jaundice == 1 || $this->rash == 1 || $this->nausea == 1 || $this->vomiting == 1 || $this->diarrhea == 1) {
                $list_arr[] = 'Leptospirosis';
            }
        }

        //MALARIA
        if($this->fever == 1) {
            if(in_array("SPLENOMEGALY", $osymp_list) || in_array("ANEMIC", $osymp_list) || in_array("ANEMIA", $osymp_list)) {
                $list_arr[] = 'Malaria';
            }
        }

        if($this->fever == 1 && $this->rash == 1) {
            if($this->cough == 1 || $this->colds == 1 || $this->conjunctivitis == 1) {
                $list_arr[] = 'Measles';
            }
        }

        if($this->fever == 1) {
            if(in_array('STIFF NECK', $osymp_list) || $this->alteredmentalstatus == 1 || $this->rash == 1 || $this->headache == 1 || in_array('PHOTOPHOBIA', $osymp_list)) {
                $list_arr[] = 'Meningococcal Disease';
            }
        }

        if($this->cough == 1) {
            if(!is_null($this->cough_onset)) {
                $base_date = Carbon::parse($this->cough_onset);
            }
            else {
                $base_date = Carbon::parse($this->created_at);
            }

            $case_date = Carbon::parse($this->consultation_date);

            $get_days = $base_date->diffInDays($case_date);

            if($get_days >= 13) {
                if($this->cough == 1 || $this->vomiting == 1) {
                    $list_arr[] = 'Pertussis';
                }
            }
        }

        if($this->animalbite == 1 || in_array('ENCEPHALITIS', $osymp_list)) {
            $list_arr[] = 'Rabies';
        }

        if($this->fever == 1 && $this->headache == 1) {
            if(in_array('MALAISE', $osymp_list) || $this->anorexia == 1 || in_array('BRADYCARDIA', $osymp_list) || in_array('CONSTIPATION', $osymp_list) || $this->diarrhea == 1) {
                $list_arr[] = 'Typhoid and Paratyphoid Fever';
            }
        }

        if($this->fever == 1 && $this->cough == 1) {
            $list_arr[] = 'COVID-19';
        }
        else {
            $covid_count = 0;

            if($this->fever == 1) {
                $covid_count++;
            }

            if($this->cough == 1) {
                $covid_count++;
            }

            if($this->weaknessofextremities == 1 || $this->fatigue == 1) {
                $covid_count++;
            }

            if($this->headache == 1) {
                $covid_count++;
            }

            if($this->musclepain == 1) {
                $covid_count++;
            }

            if($this->sorethroat == 1) {
                $covid_count++;
            }

            if($this->colds == 1) {
                $covid_count++;
            }

            if($this->dyspnea == 1) {
                $covid_count++;
            }

            if($this->anorexia == 1 || $this->nausea == 1 || $this->vomiting == 1) {
                $covid_count++;
            }

            if($this->diarrhea == 1) {
                $covid_count++;
            }

            if($this->alteredmentalstatus == 1) {
                $covid_count++;
            }

            if($covid_count >= 3) {
                $list_arr[] = 'COVID-19';
            }
        }

        if($list_arr) {
            return implode(", ", $list_arr);
        }
        else {
            return 'N/A';
        }
    }

    public function getMedCertStartDate() {
        if(!is_null($this->medcert_start_date)) {
            return date('m/d/Y', strtotime($this->medcert_start_date));
        }
        else {
            return '____________________';
        }
    }

    public function getMedCertEndDate() {
        if(!is_null($this->medcert_end_date)) {
            return date('m/d/Y', strtotime($this->medcert_end_date));
        }
        else {
            return '____________________';
        }
    }

    public function getPhysicianDetails() {
        $d = SyndromicDoctor::where('doctor_name', $this->name_of_physician)->first();

        return $d;
    }

    public function permittedToEdit() {
        
    }

    public function getIcd10CodeString($code) {
        $d = Icd10Code::where('ICD10_CODE', $code)->first();

        return $d->ICD10_CODE.' - '.$d->ICD10_DESC;
    }
}
