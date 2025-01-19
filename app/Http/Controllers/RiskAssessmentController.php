<?php

namespace App\Http\Controllers;

use App\Models\RiskAssessmentForm;
use Illuminate\Http\Request;

class RiskAssessmentController extends Controller
{
    public function createFromSyndromic($syndromic_record_id) {

    }

    public function createFromScratch() {
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
