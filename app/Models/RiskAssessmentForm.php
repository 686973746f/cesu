<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskAssessmentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'from_online',
        'link_opdpatient_id',
        'assessment_date',
        'is_newrecord',
        'is_followup',
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'is_pregnant',
        'bdate',
        'age_years',
        'age_months',
        'age_days',
        'street_purok',
        'address_brgy_code',
        'occupation',
        'educational_attainment',
        
        'fh_hypertension',
        'fh_stroke',
        'fh_heartattack',
        'fh_diabetes',
        'fh_asthma',
        'fh_cancer',
        'fh_kidneydisease',
        'smoking',
        'alcohol_intake',
        'excessive_alcohol_intake',
        'obese',
        'overweight',
        'height',
        'weight',
        'bmi',
        'weight_classification',
        'central_adiposity',
        'waist_cm',
        'raised_bp',
        'systolic',
        'diastolic',
        'high_fatsalt_intake',
        'vegetable_serving',
        'fruits_serving',
        'physical_activity',
        'heart_attack',
        'question1',
        'question2',
        'question3',
        'question4',
        'question5',
        'question6',
        'question7',
        'stroke_ortia',
        'question8',
        'diabetes',
        'diabetes_medication',
        'polyphagia',
        'polydipsia',
        'polyuria',
        'raised_bloodglucose',
        'fbs_rbs',
        'fbs_rbs_date',
        'raised_bloodlipids',
        'cholesterol',
        'cholesterol_date',
        'urine_protein',
        'protein',
        'protein_date',
        'urine_ketones',
        'ketones',
        'ketones_date',
        'management',
        'meds',
        'sleep_greaterthan6',
        'senior_blurryeyes',
        'senior_diagnosedeyedisease',
        'female_hasbreastmass',
        'date_followup',
        'risk_level',
        'finding',
        'assessed_by',
        'created_by',
        'facility_id',
        'from_facility',
        'qr',
        'remarks',
    ];

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
}
