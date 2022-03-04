<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;
use App\Models\ExposureHistory;

class ContactTracingController extends Controller
{
    public function dashboard_index() {

        if(request()->input('pid')) {
            $pid = request()->input('pid');

            $check = Forms::where('id', $pid)
            ->whereNotNull('ccid_list')
            ->first();

            if($check) {
                $siv = true;
            }
            else {
                $siv = false;
            }
        }
        else {
            $check = NULL;
            $siv = false;
        }

        return view('ct_dashboard_index', [
            'search_is_valid' => $siv,
            'form' => $check,
        ]);
    }

    public function ctFormsExposureCreate($form_id) {
        $data = Forms::findOrFail($form_id);

        return view('forms_ct_create', [
            'data' => $data,
        ]);
    }

    public function ctFormsExposureStore(Request $request, $form_id) {
        $request->validate([
            'is_primarycc' => 'sometimes',
            'is_secondarycc' => 'sometimes',
            'is_tertiarycc' => 'sometimes',
            'is_primarycc_date' => ($request->is_primarycc) ? 'required' : 'nullable',
            'is_secondarycc_date' => ($request->is_secondarycc) ? 'required' : 'nullable',
            'is_tertiarycc_date' => ($request->is_tertiarycc) ? 'required' : 'nullable',
        ]);

        $form = Forms::findOrFail($form_id);

        if(time() >= strtotime('13:00:00')) {
            $date_set = date('Y-m-d 08:00:00', strtotime('+1 Day'));
        }
        else {
            $date_set = date('Y-m-d H:i:s');
        }

        $request->user()->exposureHistory()->create([
            'form_id' => $form_id,
            'is_primarycc' => ($request->is_primarycc) ? 1 : 0,
            'is_secondarycc' => ($request->is_secondarycc) ? 1 : 0,
            'is_tertiarycc' => ($request->is_tertiarycc) ? 1 : 0,
            'is_primarycc_date' => ($request->is_primarycc) ? $request->is_primarycc_date : NULL,
            'is_secondarycc_date' => ($request->is_secondarycc) ? $request->is_secondarycc_date : NULL,
            'is_tertiarycc_date' => ($request->is_tertiarycc) ? $request->is_tertiarycc_date : NULL,
            'is_primarycc_date_set' => ($request->is_primarycc) ? $date_set : NULL,
            'is_secondarycc_date_set' => ($request->is_secondarycc) ? $date_set : NULL,
            'is_tertiarycc_date_set' => ($request->is_tertiarycc) ? $date_set : NULL,
        ]);

        return redirect()->route('forms.edit', ['form' => $form_id])
        ->with('msg', 'Exposure History Data has been Added successfully.')
        ->with('msgType', 'success');
    }

    public function ctFormsExposureEdit($form_id, $ct_id) 
    {
        $data = Forms::findOrFail($form_id);
        $ctdata = ExposureHistory::findOrFail($ct_id);

        return view('forms_ct_edit', [
            'data' => $data,
            'ctdata' => $ctdata,
        ]);
    }

    public function ctFormsExposureUpdate(Request $request, $form_id, $ct_id) {
        $update = ExposureHistory::findOrFail($ct_id);

        if($update->form_id == $form_id) {
            $update->is_primarycc = ($request->is_primarycc) ? 1 : 0;
            $update->is_secondarycc = ($request->is_secondarycc) ? 1 : 0;
            $update->is_tertiarycc = ($request->is_tertiarycc) ? 1 : 0;
            $update->is_primarycc_date = ($request->is_primarycc) ? $request->is_primarycc_date : NULL;
            $update->is_secondarycc_date = ($request->is_secondarycc) ? $request->is_secondarycc_date : NULL;
            $update->is_tertiarycc_date = ($request->is_tertiarycc) ? $request->is_tertiarycc_date : NULL;
            $update->is_primarycc_date_set = ($request->is_primarycc) ? date('Y-m-d H:i:s') : NULL;
            $update->is_secondarycc_date_set = ($request->is_secondarycc) ? date('Y-m-d H:i:s') : NULL;
            $update->is_tertiarycc_date_set = ($request->is_tertiarycc) ? date('Y-m-d H:i:s') : NULL;

            if(time() >= strtotime('13:00:00')) {
                $date_set = date('Y-m-d 08:00:00', strtotime('+1 Day'));
            }
            else {
                $date_set = date('Y-m-d H:i:s');
            }

            if($request->is_primarycc && $update->isDirty('is_primarycc_date')) {
                $update->is_primarycc_date_set = $date_set;
            }
    
            if($request->is_secondarycc && $update->isDirty('is_secondarycc_date')) {
                $update->is_secondarycc_date_set = $date_set;
            }
    
            if($request->is_tertiarycc && $update->isDirty('is_tertiarycc_date')) {
                $update->is_tertiarycc_date_set = $date_set;
            }

            if($update->isDirty()) {
                $update->save();
            }

            return redirect()->route('forms.edit', ['form' => $update->form_id])
            ->with('msg', 'Exposure History Data has been Updated successfully.')
            ->with('msgType', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function ctlgureport() {
        //No. of Suspect/Probable case of the day
        $item1 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->count();

        //No. of Suspect/Probable case of the day traced within 24 hours
        $item2 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('interviewDate', '<=', date('Y-m-d', strtotime('-1 Day')))
        ->count();

        //% of Suspect/ Probable case of the day traced within 24 hours
        $item3 = ($item1 != 0 && $item2 != 0) ? ($item1 / $item2) * 100 : 0;

        //No. of Suspect/ Probable case traced and isolated within 24 hours
        $item4 = Forms::where('status', 'approved')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereIn('dispoType', [2,6,7])
        ->count();
        
        //% of Suspect/ Probable case isolated within 24 hours
        $item5 = ($item4 != 0 && $item4 != 0) ? ($item4 / $item4) * 100 : 0;

        //No. of Confirmed/ Active Cases of the day
        $item6 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //No. of Confirmed/ Active Cases of the day traced within 24 hours
        $item7 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->whereDate('dateReported', date('Y-m-d'))
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->count();

        //% of Confirmed/ Active Cases of the day traced within 24 hours
        $item8 = ($item6 != 0 && $item7 != 0) ? ($item6 / $item7) * 100 : 0;
        
        //No. of Pending Confirmed/ Active Cases still to be traced
        $item9  = 0;

        //No. of Pending Confirmed/ Active Cases traced
        $item10 = 0;

        //% of pending Confirmed/ Active Cases still to be traced traced within 24 hours
        $item11 = ($item9 != 0 && $item10 != 0) ? ($item9 / $item10) * 100 : 0;

        //No. of Confirmed/ Active Cases traced and quarantined/isolated within 24 hours
        $item12 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->whereIn('dispoType', [2,6,7])
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of Confirmed/ Active Cases isolated/quarantined within 24 hours
        $item13 = ($item12 != 0 && ($item6 + $item9) != 0) ? ($item12 / ($item6 + $item9)) * 100 : 0;

        //No. of CCs listed from the Confirmed/ Active Cases
        $item14 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //No. of CCs listed Traced and Assessed within 24 hours
        $item15 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('pType', 'CLOSE CONTACT')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->whereDate('dateReported', date('Y-m-d'))
        ->count();

        //% of CCs listed Traced and Assesed within 24 hours
        $item16 = ($item15 != 0 && $item14 != 0) ? ($item15 / $item14) * 100 : 0;

        //Case: Close Contact Ratio
        $item17 = ($item6/$item6).':'.($item14/$item6);

        //No. of CCs placed under home quarantine within 24 hours
        $item18 = Forms::where('status', 'approved')
        ->where('ptype', 'CLOSE CONTACT')
        ->whereIn('caseClassification', ['Suspect', 'Probable'])
        ->where('outcomeCondition', 'Active')
        ->whereDate('morbidityMonth', date('Y-m-d'))
        ->count();
        
        //% of CCs placed under home quarantine within 24 hours
        $item19 = ($item18 != 0 && $item14 != 0) ? ($item18 / $item14) * 100 : 0;

        //Total no. of active asymptomatic or mild with no comorbidities, confirmed cases
        $item20 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();
        
        //Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Home Quarantine
        $item21 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->where('dispoType', 3)
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total no. of active asymptomatic, mild with no comorbidity, confirmed cases under Home Quarantine
        $item22 = ($item21 != 0 && $item20 != 0) ? ($item21 / $item20) * 100 : 0;

        //Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility
        $item23 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Asymptomatic', 'Mild'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', 'None')
        ->whereIn('dispoType', [2,6,7])
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility
        $item24 = ($item23 != 0 && $item20 != 0) ? ($item23 / $item20) * 100 : 0;

        //Total number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases
        $item25 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->whereIn('healthStatus', ['Mild', 'Moderate', 'Severe', 'Critical'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', '!=', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //Total Number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital
        $item26 = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('dispoType', 1)
        ->whereIn('healthStatus', ['Mild', 'Moderate', 'Severe', 'Critical'])
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->where('COMO', '!=', 'None')
        ->whereDate('morbidityMonth', '<=', date('Y-m-d'))
        ->count();

        //% of total number Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital
        $item27 = ($item26 != 0 && $item25 != 0) ? ($item26 / $item25) * 100 : 0;

        return view('report_ctlgu', [
            'item1' => $item1,
            'item2' => $item2,
            'item3' => $item3,
            'item4' => $item4,
            'item5' => $item5,
            'item6' => $item6,
            'item7' => $item7,
            'item8' => $item8,
            'item9' => $item9,
            'item10' => $item10,
            'item11' => $item11,
            'item12' => $item12,
            'item13' => $item13,
            'item14' => $item14,
            'item15' => $item15,
            'item16' => $item16,
            'item17' => $item17,
            'item18' => $item18,
            'item19' => $item19,
            'item20' => $item20,
            'item21' => $item21,
            'item22' => $item22,
            'item23' => $item23,
            'item24' => $item24,
            'item25' => $item25,
            'item26' => $item26,
            'item27' => $item27,
        ]);
    }
}
