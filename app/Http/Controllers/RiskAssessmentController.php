<?php

namespace App\Http\Controllers;

use App\Models\BarangayHealthStation;
use Carbon\Carbon;
use App\Models\Brgy;
use App\Models\EdcsBrgy;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SyndromicRecords;
use App\Models\RiskAssessmentForm;
use App\Models\SyndromicPatient;
use Illuminate\Support\Facades\Auth;

class RiskAssessmentController extends Controller
{
    public function createFromSyndromic($syndromic_record_id) {
        $d = SyndromicRecords::findOrFail($syndromic_record_id);

        if($d->syndromic_patient->getAgeInt() < 20) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient Age should be greater than or equal to 20 years old in order to proceed encoding.')
            ->with('msgtype', 'warning');
        }

        /*
        $check = RiskAssessmentForm::where('link_opdpatient_id', $d->syndromic_patient->id)
        ->where('year', Carbon::parse($d->consultation_date)->format('Y'))
        ->first();
        */

        $check = RiskAssessmentForm::where('link_opdpatient_id', $d->syndromic_patient->id)
        ->where(function ($q) use ($d) {
            $q->whereDate('assessment_date', Carbon::parse($d->consultation_date)->format('Y-m-d'))
            ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Duplicate OPD Patient already risk assessed on the consultation date.')
            ->with('msgtype', 'warning');
        }

        return redirect()->route('raf_create', [
            'link_opdpatient_id' => $d->syndromic_patient->id,
            'lname' => $d->syndromic_patient->lname,
            'fname' => $d->syndromic_patient->fname,
            'bdate' => $d->syndromic_patient->bdate,
            'sex' => substr($d->syndromic_patient->gender,0,1),
        ]);
    }

    public function nonCommOnlineIndex() {
        if(request()->input('code')) {
            //BHS Unique Code
            $code = request()->input('code');

            $s = BarangayHealthStation::where('sys_code1', $code)->first();

            if(!$s) {
                return abort(401);
            }

            $facility_code = $s;
        }
        else {
            $facility_code = NULL;
        }

        return view('efhsis.riskassessment.online_home', [
            'f' => $facility_code,
        ]);
    }

    public function createFromScratch(Request $r) {
        $r->validate([
            'sex' => 'required|in:M,F',
        ]);

        if(request()->input('facility_code')) {
            //BHS Unique Code
            $code = request()->input('facility_code');

            $s = BarangayHealthStation::where('sys_code1', $code)->first();

            if(!$s) {
                return abort(401);
            }

            $facility_code = $s;
        }
        else {
            $facility_code = NULL;
        }

        if(request()->input('link_opdpatient_id')) {
            $d = SyndromicPatient::findOrFail(request()->input('link_opdpatient_id'));

            $check = RiskAssessmentForm::where('link_opdpatient_id', $d->id)
            ->whereDate('assessment_date', Carbon::parse($d->getLastCheckup()->consultation_date)->format('Y-m-d'))
            ->first();

            if($check) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: Duplicate OPD Patient already risk assessed on the consultation date.')
                ->with('msgtype', 'warning');
            }
        }

        if(!request()->input('lname') || !request()->input('fname') || !request()->input('bdate') || !request()->input('sex')) {
            return abort(401);
        }

        $brgy_list = EdcsBrgy::where('city_id', 388)
        ->orderBy('name', 'ASC')
        ->get();

        $lname = mb_strtoupper(request()->input('lname'));
        $fname = mb_strtoupper(request()->input('fname'));
        $mname = (request()->input('mname')) ? mb_strtoupper(request()->input('mname')) : NULL;
        $suffix = (request()->input('suffix')) ? mb_strtoupper(request()->input('suffix')) : NULL;
        $bdate = request()->input('bdate');

        $age_check = Carbon::parse($bdate)->age;

        if($age_check < 20) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient Age should be greater than or equal to 20 years old in order to proceed encoding.')
            ->with('msgtype', 'warning');
        }

        $check = RiskAssessmentForm::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->whereDate('assessment_date', date('Y-m-d')) //should be ranged from 1-3 Days, pero tsaka na muna
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient Risk Assessment Data was already encoded today.')
            ->with('msgtype', 'warning');
        }

        if(Auth::guest()) {
            $route = route('onlinenc_store');
        }
        else {
            $route = route('raf_store');
        }
        
        return view('efhsis.riskassessment.new', [
            'f' => $facility_code,
            'brgy_list' => $brgy_list,
            'age_check' => $age_check,
            'route' => $route,
        ]);
    }

    public function store(Request $r) {
        $is_followup = false;
        $is_newrecord = true;
        $foundUnique = false;

        while(!$foundUnique) {
            $qr = mb_strtoupper(Str::random(7));

            $qr_check = RiskAssessmentForm::where('qr', $qr)->first();

            if(!$qr_check) {
                $foundUnique = true;
            }
        }

         if($r->facility_code) {
            //BHS Unique Code
            $code = $r->facility_code;

            $s = BarangayHealthStation::where('sys_code1', $code)->first();

            if(!$s) {
                return abort(401);
            }

            $facility_code = $s;
        }
        else {
            $facility_code = NULL;
        }

        if(isset($r->link_opdpatient_id)) {
            $d = SyndromicPatient::findOrFail($r->link_opdpatient_id);

            /*
            $check = RiskAssessmentForm::where('link_opdpatient_id', $d->id)
            ->where('year', date('Y'))
            ->first();

            if($check) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: OPD Patient already has Risk Assessment Form Existing for this Year.')
                ->with('msgtype', 'warning');
            }
            */

            $lname = mb_strtoupper($d->lname);
            $fname = mb_strtoupper($d->fname);
            $mname = ($d->mname) ? mb_strtoupper($d->mname) : NULL;
            $suffix = ($d->suffix) ? mb_strtoupper($d->suffix) : NULL;
            $bdate = $d->bdate;
        }
        else {
            $lname = mb_strtoupper($r->lname);
            $fname = mb_strtoupper($r->fname);
            $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
            $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;
            $bdate = $r->bdate;
        }

        if(Auth::guest()) {
            $assessment_date = date('Y-m-d');
        }
        else {
            $assessment_date = $r->assessment_date;
        }

        $birthdate = Carbon::parse($bdate);
        $currentDate = Carbon::parse($assessment_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $check = RiskAssessmentForm::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->where(function ($q) use ($currentDate) {
            $q->whereDate('assessment_date', $currentDate->format('Y-m-d'))
            ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient Risk Assessment Data was already encoded today.')
            ->with('msgtype', 'warning');
        }

        $check = RiskAssessmentForm::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->where('year', $currentDate->format('Y'))
        ->first();

        if($check) {
            $is_followup = true;
        }

        $check = RiskAssessmentForm::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->first();

        if($check) {
            $is_newrecord = false;
        }

        if(Auth::guest()) {
            $created_by = NULL;
        }
        else {
            $created_by = Auth::id();
        }

        $height_m = $r->height / 100; // Convert cm to meters
        $bmi = round($r->weight / ($height_m * $height_m), 2);

        //heart_attack
        if($r->question1 == 'Y' && $r->question2 == 'Y') {
            if($r->question3 == 'Y' || $r->question4 == 'Y' || $r->question5 == 'Y' || $r->question6 == 'Y' || $r->question7 == 'Y') {
                $heart_attack = 'Y';
            }
            else {
                $heart_attack = 'N';
            }
        }
        else {
            $heart_attack = 'N';
        }

        //Weight Classification
        if($bmi < 18.5) {
            $weight_classification = 'UNDERWEIGHT';
            $central_adiposity = 'N';
        }
        else if($bmi >= 18.5 && $bmi <= 24.9) {
            $weight_classification = 'NORMAL';
            $central_adiposity = 'N';
        }
        else if($bmi >= 25 && $bmi <= 29.9) {
            $weight_classification = 'OVERWEIGHT';
            $central_adiposity = 'Y';
        }
        else {
            $weight_classification = 'OBESE';
            $central_adiposity = 'Y';
        }

        //Raised BP
        if ($r->systolic >= 120 && $r->systolic <= 129 && $r->diastolic < 80) {
            //return "Raised BP (Elevated)";
            
            $raised_bp = 'Y';
        } elseif (($r->systolic >= 130 && $r->systolic <= 139) && ($r->diastolic >= 80 && $r->diastolic <= 89)) {
            //return "Hypertension Stage 1";

            $raised_bp = 'Y';
        } elseif ($r->systolic >= 140 || $r->diastolic >= 90) {
            //return "Hypertension Stage 2";

            $raised_bp = 'Y';
        } elseif ($r->systolic > 180 || $r->diastolic > 120) {
            //return "Hypertensive Crisis (Seek Emergency Care)";

            $raised_bp = 'Y';
        } else {
            //return "Normal BP";

            $raised_bp = 'N';
        }

        $meds_array = [];

        if($raised_bp == 'Y') {
            $meds_array[] = 'LOSARTAN 50/100MG';
            $meds_array[] = 'AMLODIPINE 5/10MG';
            $meds_array[] = 'SIMVASTATIN 20MG';
            $meds_array[] = 'GLICLAZIDE 30MG/80MG';
            $meds_array[] = 'METFORMIN 500MG';
        }

        if($r->diabetes == 'Y') {
            $meds_array[] = 'BIPHASIC 70/30 (RED/BROWN)';
            $meds_array[] = 'ISOPHANE (GREEN)';
            $meds_array[] = 'REGULAR (YELLOW)';
        }

        $table_params = [
            'year' => $currentDate->format('Y'),
            'month' => $currentDate->format('n'),
            'link_opdpatient_id' => $r->link_opdpatient_id ?: NULL,
            'assessment_date' => $assessment_date,
            'is_newrecord' => ($is_newrecord) ? 'Y' : 'N',
            'is_followup' => ($is_followup) ? 'Y' : 'N',
            'lname' => $lname,
            'fname' => $fname,
            'mname'  => $mname,
            'suffix'  => $suffix,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'bdate' => $r->bdate,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->brgy_id,
            'occupation' => ($r->occupation) ? mb_strtoupper($r->occupation) : NULL,
            'educational_attainment' => $r->educational_attainment,
            
            'height' => $r->height,
            'weight' => $r->weight,
            'waist_cm' => $r->waist_cm,
            'bmi' => $bmi,
            'weight_classification' => $weight_classification,
            'systolic' => $r->systolic,
            'diastolic' => $r->diastolic,
            'raised_bp' => $raised_bp,

            'fh_hypertension' => ($r->fh_hypertension) ? 'Y' : 'N',
            'fh_stroke' => ($r->fh_stroke) ? 'Y' : 'N',
            'fh_heartattack' => ($r->fh_heartattack) ? 'Y' : 'N',
            'fh_diabetes' => ($r->fh_diabetes) ? 'Y' : 'N',
            'fh_asthma' => ($r->fh_asthma) ? 'Y' : 'N',
            'fh_cancer' => ($r->fh_cancer) ? 'Y' : 'N',
            'fh_kidneydisease' => ($r->fh_kidneydisease) ? 'Y' : 'N',
            'smoking' => $r->smoking,
            'alcohol_intake' => ($r->alcohol_intake) ? 'Y' : 'N',
            'excessive_alcohol_intake' => ($r->excessive_alcohol_intake) ? 'Y' : 'N',
            
            //'obese' => ($r->obese) ? 'Y' : 'N',
            //'overweight' => ($r->overweight) ? 'Y' : 'N',
            
            'central_adiposity' => $central_adiposity,
            'high_fatsalt_intake' => ($r->high_fatsalt_intake) ? 'Y' : 'N',
            'vegetable_serving' => ($r->vegetable_serving) ? 'Y' : 'N',
            'fruits_serving' => ($r->fruits_serving) ? 'Y' : 'N',
            'physical_activity' => ($r->physical_activity) ? 'Y' : 'N',
            'heart_attack' => $heart_attack,
            'question1' => $r->question1,
            'question2' => ($r->question1 == 'Y') ? $r->question2 : 'N',
            'question3' => ($r->question2 == 'Y') ? $r->question3 : 'N',
            'question4' => ($r->question2 == 'Y') ? $r->question4 : 'N',
            'question5' => ($r->question2 == 'Y') ? $r->question5 : 'N',
            'question6' => ($r->question2 == 'Y') ? $r->question6 : 'N',
            'question7' => ($r->question2 == 'Y') ? $r->question7 : 'N',
            'stroke_ortia' => ($r->question8 == 'Y') ? 'Y' : 'N',
            'question8' => $r->question8,
            'diabetes' => $r->diabetes,
            'diabetes_medication' => ($r->diabetes == 'Y') ? $r->diabetes_medication : 'N',
            'polyphagia' => ($r->diabetes == 'N' || $r->diabetes == 'U') ? $r->polyphagia : 'N',
            'polydipsia' => ($r->diabetes == 'N' || $r->diabetes == 'U') ? $r->polydipsia : 'N',
            'polyuria' => ($r->diabetes == 'N' || $r->diabetes == 'U') ? $r->polyuria : 'N',
            'raised_bloodglucose' => $r->raised_bloodglucose,
            'fbs_rbs' => ($r->raised_bloodglucose == 'Y') ? $r->fbs_rbs : NULL,
            'fbs_rbs_date' => ($r->raised_bloodglucose == 'Y') ? $r->fbs_rbs_date : NULL,
            'raised_bloodlipids' => $r->raised_bloodlipids,
            'cholesterol' => ($r->raised_bloodlipids == 'Y') ? $r->cholesterol : NULL,
            'cholesterol_date' => ($r->raised_bloodlipids == 'Y') ? $r->cholesterol_date : NULL,
            'urine_protein' => $r->urine_protein,
            'protein' => ($r->urine_protein == 'Y') ? $r->protein : NULL,
            'protein_date' => ($r->urine_protein == 'Y') ? $r->protein_date : NULL,
            'urine_ketones' => $r->urine_ketones,
            'ketones' => ($r->urine_ketones == 'Y') ? $r->ketones : NULL,
            'ketones_date' => ($r->urine_ketones == 'Y') ? $r->ketones_date : NULL,
            'management' => $r->management,
            'meds' => !empty($meds_array) ? implode(", ", $meds_array) : NULL,
            'date_followup' => ($r->date_followup) ? mb_strtoupper($r->date_followup) : NULL,
            //'risk_level',
            'finding' => $r->finding,
            'assessed_by' => ($r->assessed_by) ? mb_strtoupper($r->assessed_by) : NULL,
            'created_by' => $created_by,
            'facility_id' => isset(auth()->user()->itr_facility_id) ? auth()->user()->itr_facility_id : 10886,
            'from_facility' => (!is_null($facility_code)) ? $facility_code->name : NULL,
            'qr' => $qr,
        ];

        if($get_ageyears >= 60) {
            $table_params = $table_params + [
                'senior_blurryeyes' => $r->senior_blurryeyes,
                'senior_diagnosedeyedisease' => $r->senior_diagnosedeyedisease,
            ];
        }

        if($r->sex == 'F') {
            $table_params = $table_params + [
                'female_hasbreastmass' => $r->female_hasbreastmass,
            ];
        }

        $c = RiskAssessmentForm::create($table_params);

        if(isset($r->link_opdpatient_id)) {
            $record_id = $d->getLastCheckup()->id;

            return redirect()->route('syndromic_viewRecord', $record_id)
            ->with('msg', 'Risk Assessment Form was successfully created and linked to this patient.')
            ->with('msgtype', 'success');
        }
        else {
            if(Auth::guest()) {
                if((!is_null($facility_code))) {
                    return redirect()->route('onlinenc_home', [
                        'code' => $facility_code->sys_code1,
                    ])
                    ->with('msg', 'Risk Assessment Form was successfully created to '.$c->getName().'. For another submission, you may fill-out the form again.')
                    ->with('msgtype', 'success');
                }
                else {
                    return redirect()->route('onlinenc_home')
                    ->with('msg', 'Risk Assessment Form was successfully created to '.$c->getName().'. For another submission, you may fill-out the form again.')
                    ->with('msgtype', 'success');
                }
                
            }
            else {
                return redirect()->route('home')
                ->with('msg', 'Risk Assessment Form was successfully created.')
                ->with('msgtype', 'success');
            }
            
        }
    }

    public function edit($id) {

    }

    public function update($id, Request $r) {
        
    }

    public function reportV1() {
        if(!request()->input('year') || !(request()->input('brgy'))) {
            return abort(401);
        }

        $year = request()->input('year');
        $brgy = request()->input('brgy');
        $final_arr = [];

        if($year == 'WHOLE_YEAR') {
            $query = RiskAssessmentForm::where('year', $year)
            ->where('is_followup', 'N')
            ->where('address_brgy_code', $brgy);

            $b = EdcsBrgy::findOrFail($brgy);

            if($year == date('Y')) {
                $maxMonth = date('n');
            }
            else {
                $maxMonth = 12;
            }

            for($i = 1; $i <= $maxMonth; $i++) {
                if($i == 1) {
                    $month = 'JANUARY';
                }
                else if($i == 2) {
                    $month = 'FEBRUARY';
                }
                else if($i == 3) {
                    $month = 'MARCH';
                }
                else if($i == 4) {
                    $month = 'APRIL';
                }
                else if($i == 5) {
                    $month = 'MAY';
                }
                else if($i == 6) {
                    $month = 'JUNE';
                }
                else if($i == 7) {
                    $month = 'JULY';
                }
                else if($i == 8) {
                    $month = 'AUGUST';
                }
                else if($i == 9) {
                    $month = 'SEPTEMBER';
                }
                else if($i == 10) {
                    $month = 'OCTOBER';
                }
                else if($i == 11) {
                    $month = 'NOVEMBER';
                }
                else if($i == 12) {
                    $month = 'DECEMBER';
                }

                $pen_m = (clone $query)->where('month', $i)
                ->where('sex', 'M')
                ->count();

                $pen_f = (clone $query)->where('month', $i)
                ->where('sex', 'F')
                ->count();

                $pen_total = $pen_m + $pen_f;

                $current_smoker_m = (clone $query)->where('month', $i)
                ->where('sex', 'M')
                ->whereIn('smoking', ['CURRENT', 'MASSIVE'])
                ->count();

                $current_smoker_f = (clone $query)->where('month', $i)
                ->where('sex', 'F')
                ->whereIn('smoking', ['CURRENT', 'MASSIVE'])
                ->count();

                $current_smoker_total = $current_smoker_m + $current_smoker_f;

                $binge_drinker_m = (clone $query)->where('month', $i)
                ->where('sex', 'M')
                ->where('excessive_alcohol_intake', 'Y')
                ->count();

                $binge_drinker_f = (clone $query)->where('month', $i)
                ->where('sex', 'F')
                ->where('excessive_alcohol_intake', 'Y')
                ->count();

                $bringe_drinker_total = $binge_drinker_m + $binge_drinker_f;

                $over_obese_m = (clone $query)->where('month', $i)
                ->where('sex', 'M')
                ->whereIn('weight_classification', ['OVERWEIGHT', 'OBESE'])
                ->count();

                $over_obese_f = (clone $query)->where('month', $i)
                ->where('sex', 'F')
                ->whereIn('weight_classification', ['OVERWEIGHT', 'OBESE'])
                ->count();

                $over_obese_total = $over_obese_m + $over_obese_f;

                //NEW - bagong received
                //UPDATED - may lumang record na pero yung new record nila ay naging hypertensive/diabetic na

                $newly_hypertensive_m_new = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'Y')
                ->where('sex', 'M')
                ->where('raised_bp', 'Y')
                ->count();

                $newly_hypertensive_f_new = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'Y')
                ->where('sex', 'F')
                ->where('raised_bp', 'Y')
                ->count();

                $newly_hypertensive_m_updated = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'N')
                ->where('sex', 'M')
                ->where('raised_bp', 'Y')
                ->count();

                $newly_hypertensive_f_updated = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'N')
                ->where('sex', 'F')
                ->where('raised_bp', 'Y')
                ->count();

                $newly_hypertensive_total = $newly_hypertensive_m_new + $newly_hypertensive_f_new + $newly_hypertensive_m_updated + $newly_hypertensive_f_updated;

                $newly_diabetes_m_new = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'Y')
                ->where('sex', 'M')
                ->where('diabetes', 'Y')
                ->count();

                $newly_diabetes_f_new = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'Y')
                ->where('sex', 'F')
                ->where('diabetes', 'Y')
                ->count();

                $newly_diabetes_m_updated = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'N')
                ->where('sex', 'M')
                ->where('diabetes', 'Y')
                ->count();

                $newly_diabetes_f_updated = (clone $query)->where('month', $i)
                ->where('is_newrecord', 'N')
                ->where('sex', 'F')
                ->where('diabetes', 'Y')
                ->count();

                $newlly_diabetes_total = $newly_diabetes_m_new + $newly_diabetes_f_new + $newly_diabetes_m_updated + $newly_diabetes_f_updated;

                $susp_breastmass = (clone $query)->where('month', $i)
                ->where('sex', 'F')
                ->where('female_hasbreastmass', 'Y')
                ->count();

                $senior_visual_m = (clone $query)->where('month', $i)
                ->where('age_years', '>=', 60)
                ->where('sex', 'M')
                ->count();

                $senior_visual_f = (clone $query)->where('month', $i)
                ->where('age_years', '>=', 60)
                ->where('sex', 'F')
                ->count();

                $senior_visual_total = $senior_visual_m + $senior_visual_f;

                $senior_eyedisease_m = (clone $query)->where('month', $i)
                ->where('age_years', '>=', 60)
                ->where('senior_diagnosedeyedisease', 'Y')
                ->where('sex', 'M')
                ->count();

                $senior_eyedisease_f = (clone $query)->where('month', $i)
                ->where('age_years', '>=', 60)
                ->where('senior_diagnosedeyedisease', 'Y')
                ->where('sex', 'F')
                ->count();

                $senior_eyedisease_total = $senior_eyedisease_m + $senior_eyedisease_f;

                $final_arr[] = [
                    'var' => $month,
                    'pen_m' => $pen_m,
                    'pen_f' => $pen_f,
                    'pen_total' => $pen_total,
                    'current_smoker_m' => $current_smoker_m,
                    'current_smoker_f' => $current_smoker_f,
                    'current_smoker_total' => $current_smoker_total,
                    'binge_drinker_m' => $binge_drinker_m,
                    'binge_drinker_f' => $binge_drinker_f,
                    'bringe_drinker_total' => $bringe_drinker_total,
                    'over_obese_m' => $over_obese_m,
                    'over_obese_f' => $over_obese_f,
                    'over_obese_total' => $over_obese_total,
                    'newly_hypertensive_m_new' => $newly_hypertensive_m_new,
                    'newly_hypertensive_f_new' => $newly_hypertensive_f_new,
                    'newly_hypertensive_m_updated' => $newly_hypertensive_m_updated,
                    'newly_hypertensive_f_updated' => $newly_hypertensive_f_updated,
                    'newly_hypertensive_total' => $newly_hypertensive_total,
                    'newly_diabetes_m_new' => $newly_diabetes_m_new,
                    'newly_diabetes_f_new' => $newly_diabetes_f_new,
                    'newly_diabetes_m_updated' => $newly_diabetes_m_updated,
                    'newly_diabetes_f_updated' => $newly_diabetes_f_updated,
                    'newlly_diabetes_total' => $newlly_diabetes_total,
                    'susp_breastmass' => $susp_breastmass,
                    'senior_visual_m' => $senior_visual_m,
                    'senior_visual_f' => $senior_visual_f,
                    'senior_visual_total' => $senior_visual_total,
                    'senior_eyedisease_m' => $senior_eyedisease_m,
                    'senior_eyedisease_f' => $senior_eyedisease_f,
                    'senior_eyedisease_total' => $senior_eyedisease_total,
                ];
            }
        }
        else {
            if($brgy == 'ALL_BRGY') {
                $query = RiskAssessmentForm::where('year', $year)
                ->where('is_followup', 'N')
                ->where('month', request()->input('month'));

                $brgy_list = EdcsBrgy::where('city_id', 388)->get();

                $i = request()->input('month');

                foreach($brgy_list as $b) {
                    $pen_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'M')
                    ->count();

                    $pen_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'F')
                    ->count();

                    $pen_total = $pen_m + $pen_f;

                    $current_smoker_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'M')
                    ->whereIn('smoking', ['CURRENT', 'MASSIVE'])
                    ->count();

                    $current_smoker_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'F')
                    ->whereIn('smoking', ['CURRENT', 'MASSIVE'])
                    ->count();

                    $current_smoker_total = $current_smoker_m + $current_smoker_f;

                    $binge_drinker_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'M')
                    ->where('excessive_alcohol_intake', 'Y')
                    ->count();

                    $binge_drinker_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'F')
                    ->where('excessive_alcohol_intake', 'Y')
                    ->count();

                    $bringe_drinker_total = $binge_drinker_m + $binge_drinker_f;

                    $over_obese_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'M')
                    ->whereIn('weight_classification', ['OVERWEIGHT', 'OBESE'])
                    ->count();

                    $over_obese_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'F')
                    ->whereIn('weight_classification', ['OVERWEIGHT', 'OBESE'])
                    ->count();

                    $over_obese_total = $over_obese_m + $over_obese_f;

                    //NEW - bagong received
                    //UPDATED - may lumang record na pero yung new record nila ay naging hypertensive/diabetic na

                    $newly_hypertensive_m_new = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'Y')
                    ->where('sex', 'M')
                    ->where('raised_bp', 'Y')
                    ->count();

                    $newly_hypertensive_f_new = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'Y')
                    ->where('sex', 'F')
                    ->where('raised_bp', 'Y')
                    ->count();

                    $newly_hypertensive_m_updated = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'N')
                    ->where('sex', 'M')
                    ->where('raised_bp', 'Y')
                    ->count();

                    $newly_hypertensive_f_updated = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'N')
                    ->where('sex', 'F')
                    ->where('raised_bp', 'Y')
                    ->count();

                    $newly_hypertensive_total = $newly_hypertensive_m_new + $newly_hypertensive_f_new + $newly_hypertensive_m_updated + $newly_hypertensive_f_updated;

                    $newly_diabetes_m_new = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'Y')
                    ->where('sex', 'M')
                    ->where('diabetes', 'Y')
                    ->count();

                    $newly_diabetes_f_new = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'Y')
                    ->where('sex', 'F')
                    ->where('diabetes', 'Y')
                    ->count();

                    $newly_diabetes_m_updated = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'N')
                    ->where('sex', 'M')
                    ->where('diabetes', 'Y')
                    ->count();

                    $newly_diabetes_f_updated = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('is_newrecord', 'N')
                    ->where('sex', 'F')
                    ->where('diabetes', 'Y')
                    ->count();

                    $newlly_diabetes_total = $newly_diabetes_m_new + $newly_diabetes_f_new + $newly_diabetes_m_updated + $newly_diabetes_f_updated;

                    $susp_breastmass = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('sex', 'F')
                    ->where('female_hasbreastmass', 'Y')
                    ->count();

                    $senior_visual_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('age_years', '>=', 60)
                    ->where('sex', 'M')
                    ->count();

                    $senior_visual_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('age_years', '>=', 60)
                    ->where('sex', 'F')
                    ->count();

                    $senior_visual_total = $senior_visual_m + $senior_visual_f;

                    $senior_eyedisease_m = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('age_years', '>=', 60)
                    ->where('senior_diagnosedeyedisease', 'Y')
                    ->where('sex', 'M')
                    ->count();

                    $senior_eyedisease_f = (clone $query)->where('address_brgy_code', $b->id)
                    ->where('age_years', '>=', 60)
                    ->where('senior_diagnosedeyedisease', 'Y')
                    ->where('sex', 'F')
                    ->count();

                    $senior_eyedisease_total = $senior_eyedisease_m + $senior_eyedisease_f;

                    $final_arr[] = [
                        'var' => $b->name,
                        'pen_m' => $pen_m,
                        'pen_f' => $pen_f,
                        'pen_total' => $pen_total,
                        'current_smoker_m' => $current_smoker_m,
                        'current_smoker_f' => $current_smoker_f,
                        'current_smoker_total' => $current_smoker_total,
                        'binge_drinker_m' => $binge_drinker_m,
                        'binge_drinker_f' => $binge_drinker_f,
                        'bringe_drinker_total' => $bringe_drinker_total,
                        'over_obese_m' => $over_obese_m,
                        'over_obese_f' => $over_obese_f,
                        'over_obese_total' => $over_obese_total,
                        'newly_hypertensive_m_new' => $newly_hypertensive_m_new,
                        'newly_hypertensive_f_new' => $newly_hypertensive_f_new,
                        'newly_hypertensive_m_updated' => $newly_hypertensive_m_updated,
                        'newly_hypertensive_f_updated' => $newly_hypertensive_f_updated,
                        'newly_hypertensive_total' => $newly_hypertensive_total,
                        'newly_diabetes_m_new' => $newly_diabetes_m_new,
                        'newly_diabetes_f_new' => $newly_diabetes_f_new,
                        'newly_diabetes_m_updated' => $newly_diabetes_m_updated,
                        'newly_diabetes_f_updated' => $newly_diabetes_f_updated,
                        'newlly_diabetes_total' => $newlly_diabetes_total,
                        'susp_breastmass' => $susp_breastmass,
                        'senior_visual_m' => $senior_visual_m,
                        'senior_visual_f' => $senior_visual_f,
                        'senior_visual_total' => $senior_visual_total,
                        'senior_eyedisease_m' => $senior_eyedisease_m,
                        'senior_eyedisease_f' => $senior_eyedisease_f,
                        'senior_eyedisease_total' => $senior_eyedisease_total,
                    ];
                }
            }
            else {

            }
        }

        return view('efhsis.riskassessment.reportv1', [
            'final_arr' => $final_arr,
            'b' => $b,
        ]);
    }
}
