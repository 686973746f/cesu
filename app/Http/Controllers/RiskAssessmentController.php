<?php

namespace App\Http\Controllers;

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

        $check = RiskAssessmentForm::where('link_opdpatient_id', $d->syndromic_patient->id)
        ->where('year', Carbon::parse($d->consultation_date)->format('Y'))
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: OPD Patient already has Risk Assessment Form Existing for this Year.')
            ->with('msgtype', 'warning');
        }

        return redirect()->route('raf_create', [
            'link_opdpatient_id' => $d->syndromic_patient->id,
            'lname' => $d->syndromic_patient->lname,
            'fname' => $d->syndromic_patient->fname,
            'bdate' => $d->syndromic_patient->bdate,
        ]);
    }

    public function nonCommOnlineIndex() {
        return view('efhsis.riskassessment.online_home');
    }

    public function createFromScratch() {
        if(request()->input('link_opdpatient_id')) {
            $d = SyndromicPatient::findOrFail(request()->input('link_opdpatient_id'));

            $check = RiskAssessmentForm::where('link_opdpatient_id', $d->id)->first();

            if($check) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: OPD Patient already has Risk Assessment Form Existing for this Year.')
                ->with('msgtype', 'warning');
            }
        }

        if(!request()->input('lname') || !request()->input('fname') || !request()->input('bdate')) {
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
        ->where('year', date('Y'))
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Patient already has risk assessment record for this year. Patient can fillout the risk assessment form again next year.')
            ->with('msgtype', 'warning');
        }
        
        return view('efhsis.riskassessment.new', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function store(Request $r) {
        $foundUnique = false;

        while(!$foundUnique) {
            $qr = mb_strtoupper(Str::random(7));

            $qr_check = RiskAssessmentForm::where('qr', $qr)->first();

            if(!$qr_check) {
                $foundUnique = true;
            }
        }

        if(isset($r->link_opdpatient_id)) {
            $d = SyndromicPatient::findOrFail($r->link_opdpatient_id);

            $check = RiskAssessmentForm::where('link_opdpatient_id', $d->id)
            ->where('year', date('Y'))
            ->first();

            if($check) {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: OPD Patient already has Risk Assessment Form Existing for this Year.')
                ->with('msgtype', 'warning');
            }

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

        $birthdate = Carbon::parse($bdate);
        $currentDate = Carbon::parse($r->assessment_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $check = RiskAssessmentForm::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->where('year', $currentDate->format('Y'))
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Record already has risk assessed this year. There is no need to encode again.')
            ->with('msgtype', 'warning');
        }

        if(Auth::guest()) {
            $created_by = NULL;
        }
        else {
            $created_by = Auth::id();
        }

        $height_m = $r->height / 100; // Convert cm to meters
        $bmi = $r->weight / ($height_m * $height_m);

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
        
        $c = RiskAssessmentForm::create([
            'year' => $currentDate->format('Y'),
            'month' => $currentDate->format('n'),
            'link_opdpatient_id' => $r->link_opdpatient_id ?: NULL,
            'assessment_date' => $r->assessment_date,
            'lname' => $lname,
            'fname' => $fname,
            'mname'  => $mname,
            'suffix'  => $suffix,
            'sex' => $r->sex,
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
            'bmi' => round($bmi, 2),
            'systolic' => $r->systolic,
            'diastolic' => $r->diastolic,

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
            'obese' => ($r->obese) ? 'Y' : 'N',
            'overweight' => ($r->overweight) ? 'Y' : 'N',
            
            'central_adiposity' => ($r->overweight) ? 'Y' : 'N',
            'waist_cm' => $r->waist_cm,
            'raised_bp' => ($r->raised_bp) ? 'Y' : 'N',
            
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
            'diabetes' => ($r->diabetes) ? 'Y' : 'N',
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
            'meds' => $r->meds,
            'date_followup' => ($r->date_followup) ? mb_strtoupper($r->date_followup) : NULL,
            //'risk_level',
            'finding' => $r->finding,
            'assessed_by' => mb_strtoupper($r->assessed_by),
            'created_by' => $created_by,
            'facility_id' => isset(auth()->user()->itr_facility_id) ? auth()->user()->itr_facility_id : 10886,
            'qr' => $qr,
        ]);

        if(isset($r->link_opdpatient_id)) {
            $record_id = $d->getLastCheckup()->id;

            return redirect()->route('syndromic_viewRecord', $record_id)
            ->with('msg', 'Risk Assessment Form was successfully created and linked to this patient.')
            ->with('msgtype', 'success');
        }
        else {
            if(Auth::guest()) {
                return redirect()->route('onlinenc_home')
                ->with('msg', 'Risk Assessment Form was successfully created to '.$c->getName().'. For another submission, you may fill-out the form again.')
                ->with('msgtype', 'success');
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
}
