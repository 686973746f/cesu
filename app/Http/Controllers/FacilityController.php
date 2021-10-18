<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index() {
        //Facility Account
        $currentWeek = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('W');
        
        $list = Forms::where('dispoType', 6)
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->get();
        
        $list = $list->sortBy('records.lname');

        return view('home_facility', ['currentWeek' => $currentWeek, 'list' => $list]);
    }

    public function viewPatient($id) {
        $data = Forms::findOrFail($id);
        if($data->status == 'approved' && $data->caseClassification == 'Confirmed' && $data->outcomeCondition == 'Active' && $data->dispoType == 6) {
            return view('facility_viewpatient', ['data' => $data]);
        }
        else {
            return redirect()->route('facility.home')->with('msg', 'You are not allowed to do that.')->with('msgtype', 'warning');
        }
    }

    public function update($id, Request $request) {
        $data = Forms::findOrFail($id);

        if($data->status == 'approved' && $data->caseClassification == 'Confirmed' && $data->outcomeCondition == 'Active' && $data->dispoType == 6) {
            $data->SAS = (!is_null($request->symptoms)) ? implode(',', $request->symptoms) : NULL;
            $data->SASFeverDeg = (!is_null($request->symptoms) && in_array('Fever', $request->symptoms)) ? $request->SASFeverDeg : NULL;
            $data->SASOtherRemarks = (!is_null($request->symptoms) && in_array('Others', $request->symptoms)) ? $request->SASOtherRemarks : NULL;
            $data->COMO = (!is_null($request->comorbidities)) ? implode(",", $request->comorbidities) : 'None';
            $data->COMOOtherRemarks = (!is_null($request->comorbidities) && in_array('Others', $request->comorbidities)) ? $request->COMOOtherRemarks : NULL;
            $data->facility_remarks = $request->facility_remarks;

            if($data->isDirty()) {
                $data->save();
            }

            return redirect()->route('facility.home')->with('msg', 'Patient '.$data->records->getName().' (#'.$data->records->id.') has been updated successfully.')->with('msgtype', 'success');
        }
        else {
            return redirect()->route('facility.home')->with('msg', 'You are not allowed to do that.')->with('msgtype', 'warning');
        }
    }

    public function initDischarge($id, Request $request) {
        $data = Forms::findOrFail($id);
        
        if($data->status == 'approved' && $data->caseClassification == 'Confirmed' && $data->outcomeCondition == 'Active' && $data->dispoType == 6) {
            $request->validate([
                'dispoDate' => 'required|date|after_or_equal:'.date('Y-m-d', strtotime('-14 Days')).'|before_or_equal:today',
                'remarks' => 'nullable',
            ]);

            $data->updated_by = auth()->user()->id;
            $data->outcomeCondition = 'Recovered';
            $data->outcomeRecovDate = $request->dispoDate;
            $data->dispoType = 4;
            $data->dispoDate = date('Y-m-d 08:00:00', strtotime($request->dispoDate));

            $data->save();

            return redirect()->route('facility.home')->with('msg', 'Patient '.$data->records->getName().' (#'.$data->records->id.') has been discharged successfully.')->with('msgtype', 'success');
        }
        else {
            return redirect()->route('facility.home')->with('msg', 'You are not allowed to do that.')->with('msgtype', 'warning');
        }
    }
}
