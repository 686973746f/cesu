<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brgy;
use Illuminate\Http\Request;
use App\Models\SyndromicRecords;
use App\Models\RiskAssessmentForm;
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

    }

    public function createFromScratch() {
        if(request()->input('syndromic_record_id')) {
            
        }

        if(!request()->input('lname') || !request()->input('fname') || !request()->input('bdate')) {
            return abort(401);
        }

        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
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
        
        return view('efhsis.riskassessment.new', [
            'brgy_list' => $brgy_list,
        ]);
    }

    public function store(Request $r) {
        $c = RiskAssessmentForm::create([
            'link_opdrecord_id',
            'assessment_date' => $r->assessment_date,
            'lname' => $r->lname,
            'fname' => $r->fname,
            'mname'  => $r->mname,
            'suffix'  => $r->suffix,
            'sex' => $r->sex,
            'bdate' => $r->bdate,
            'street_purok' => $r->street_purok,
            'address_brgy_code' => $r->brgy_id,
            'educational_attainment' => $r->educational_attainment,
            
            'height' => $r->height,
            'weight' => $r->weight,
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
            'heart_attack' => $r->heart_attack,
            'question1' => $r->question1,
            'question2' => ($r->question1 == 'Y') ? $r->question2 : 'N',
            'question3' => ($r->question2 == 'Y') ? $r->question3 : 'N',
            'question4' => ($r->question2 == 'Y') ? $r->question4 : 'N',
            'question5' => ($r->question2 == 'Y') ? $r->question5 : 'N',
            'question6' => ($r->question2 == 'Y') ? $r->question6 : 'N',
            'question7' => ($r->question2 == 'Y') ? $r->question7 : 'N',
            'stroke_ortia' => $r->stroke_ortia,
            'question8' => $r->question8,
            'diabetes' => ($r->diabetes) ? 'Y' : 'N',
            'diabetes_medication' => ($r->diabetes_medication) ? 'Y' : 'N',
            'polyphagia' => $r->polyphagia,
            'polydipsia' => $r->polydipsia,
            'polyuria' => $r->polyuria,
            'raised_bloodglucose' => $r->raised_bloodglucose,
            'fbs_rbs',
            'fbs_rbs_date',
            'raised_bloodlipids' => $r->raised_bloodglucose,
            'cholesterol',
            'cholesterol_date',
            'urine_protein' => $r->urine_protein,
            'protein',
            'protein_date',
            'urine_ketones' => $r->urine_ketones,
            'ketones',
            'ketones_date',
            'management' => $r->management,
            'meds' => $r->meds,
            'date_followup' => ($r->date_followup) ? mb_strtoupper($r->date_followup) : NULL,
            'risk_level',
            'finding' => $r->finding,
            'assessed_by',
            'created_by' => Auth::id(),
            'facility_id' => auth()->user()->itr_facility_id,
        ]);

        if(isset($r->syndromic_record_id)) {
            
        }
        else {
            
        }
    }

    public function edit($id) {

    }

    public function update($id, Request $r) {
        
    }
}
