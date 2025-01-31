<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SyndromicRecords;
use App\Models\RiskAssessmentForm;

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
        
        return view('efhsis.riskassessment.new');
    }

    public function store(Request $r) {
        $c = RiskAssessmentForm::create([

        ]);
    }

    public function edit($id) {

    }

    public function update($id, Request $r) {
        
    }
}
