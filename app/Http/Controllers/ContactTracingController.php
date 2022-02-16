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
}
