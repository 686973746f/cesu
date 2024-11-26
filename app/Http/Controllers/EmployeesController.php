<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HertDuty;
use Illuminate\Http\Request;
use App\Models\HertDutyMember;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function index() {
        $list = Employee::where('employment_status', 'ACTIVE')->get();

        return view('employees.index', [
            'list' => $list,
        ]);
    }

    public function addEmployee() {
        return $this->newOrEdit(new Employee(), 'NEW');
    }

    public function storeEmployee(Request $r) {
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);

        $check = Employee::where('lname', $lname)
        ->where('fname', $fname)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Employee data already exists. Please double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = Employee::create([
            'lname' => $lname,
            'fname' => $fname,
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->suffix) ? mb_strtoupper($r->suffix) : NULL,
            'profession_suffix' => ($r->profession_suffix) ? mb_strtoupper($r->profession_suffix) : NULL,
            'gender' => $r->gender,
            'bdate' => $r->bdate ?: NULL,
            'contact_number' => $r->contact_number ?: NULL,
            'prc_license_no' => ($r->prc_license_no) ? mb_strtoupper($r->prc_license_no) : NULL,
            'tin_no' => ($r->tin_no) ? mb_strtoupper($r->tin_no) : NULL,
            'weight_kg' => $r->weight_kg,
            'height_cm' => $r->height_cm,
            'shirt_size' => $r->shirt_size,
            //'email',
            //'address_region_code',
            //'address_region_text',
            //'address_province_code',
            //'address_province_text',
            //'address_muncity_code',
            //'address_muncity_text',
            //'address_brgy_code',
            //'address_brgy_text',
            //'address_street',
            //'address_houseno',
            'type' => $r->type,
            'job_position' => ($r->job_position) ? mb_strtoupper($r->job_position) : NULL,
            'office' => $r->office,
            'sub_office' => ($r->sub_office) ? mb_strtoupper($r->sub_office) : NULL,
            'date_hired' => $r->date_hired ?: NULL,
            'employment_status' => $r->employment_status,
            'date_resigned' => ($r->employment_status == 'RESIGNED' || $r->employment_status == 'RETIRED') ? $r->date_resigned : NULL,
            'remarks' => $r->remarks ?: NULL,
            //'picture_file',
            //'fingerprint_hash',
            'is_blstrained' => $r->is_blstrained,
            'recent_bls_date' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            //'bls_id' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            'bls_typeofrescuer' => ($r->is_blstrained == 'Y') ? $r->bls_typeofrescuer : NULL,
            //'bls_codename',
            'duty_canbedeployed' => $r->duty_canbedeployed,
            'duty_canbedeployedagain' => $r->duty_canbedeployedagain,
            'duty_team' => $r->duty_team,
            'duty_completedcycle' => 'N',
            'created_by' => auth()->user()->id,

            'emp_access_list' => (!is_null($r->emp_access_list)) ? implode(",", $r->emp_access_list) : NULL,
        ]);

        return redirect()->route('employees_index')
        ->with('msg', 'Employee '.$lname.', '.$fname.' was successfully added.')
        ->with('msgtype', 'success');
    }

    public function editEmployee($id) {
        $employee = Employee::findOrFail($id);

        return $this->newOrEdit($employee, 'EDIT');
    }

    public function newOrEdit(Employee $record, $mode) {
        $emp_access_list = Employee::getEmpAccessList();

        return view('employees.new_or_edit', [
            'd' => $record,
            'mode' => $mode,
            'emp_access_list' => $emp_access_list,
        ]);
    }

    public function updateEmployee($id, Request $r) {
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);

        $check = Employee::where('id', '!=', $id)
        ->where('lname', $lname)
        ->where('fname', $fname)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Employee data already exists. Please double check and try again.')
            ->with('msgtype', 'warning');
        }

        $u = Employee::where('id', $id)
        ->update([
            'lname' => $lname,
            'fname' => $fname,
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->suffix) ? mb_strtoupper($r->suffix) : NULL,
            'profession_suffix' => ($r->profession_suffix) ? mb_strtoupper($r->profession_suffix) : NULL,
            'gender' => $r->gender,
            'bdate' => $r->bdate ?: NULL,
            'contact_number' => $r->contact_number ?: NULL,
            'prc_license_no' => ($r->prc_license_no) ? mb_strtoupper($r->prc_license_no) : NULL,
            'tin_no' => ($r->tin_no) ? mb_strtoupper($r->tin_no) : NULL,
            'weight_kg' => $r->weight_kg,
            'height_cm' => $r->height_cm,
            'shirt_size' => $r->shirt_size,
            //'email',
            //'address_region_code',
            //'address_region_text',
            //'address_province_code',
            //'address_province_text',
            //'address_muncity_code',
            //'address_muncity_text',
            //'address_brgy_code',
            //'address_brgy_text',
            //'address_street',
            //'address_houseno',
            'type' => $r->type,
            'job_position' => ($r->job_position) ? mb_strtoupper($r->job_position) : NULL,
            'office' => $r->office,
            'sub_office' => ($r->sub_office) ? mb_strtoupper($r->sub_office) : NULL,
            'date_hired' => $r->date_hired ?: NULL,
            'employment_status' => $r->employment_status,
            'date_resigned' => ($r->employment_status == 'RESIGNED' || $r->employment_status == 'RETIRED') ? $r->date_resigned : NULL,
            'remarks' => $r->remarks ?: NULL,
            //'picture_file',
            //'fingerprint_hash',
            'is_blstrained' => $r->is_blstrained,
            'recent_bls_date' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            //'bls_id' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            'bls_typeofrescuer' => ($r->is_blstrained == 'Y') ? $r->bls_typeofrescuer : NULL,
            //'bls_codename',
            'duty_canbedeployed' => $r->duty_canbedeployed,
            'duty_canbedeployedagain' => $r->duty_canbedeployedagain,
            'duty_team' => $r->duty_team,
            //'duty_completedcycle' => 'N',
            //'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,

            'emp_access_list' => (!is_null($r->emp_access_list)) ? implode(",", $r->emp_access_list) : NULL,
        ]);

        return redirect()->route('employees_index')
        ->with('msg', 'Employee ID: '.$id.' - '.$lname.', '.$fname.' was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function dutyIndex() {
        $duty_qry = Employee::where('employment_status', 'ACTIVE')
        ->where('duty_canbedeployed', 'Y');

        $tot_emp_duty = (clone $duty_qry)->count();

        $list = HertDuty::orderBy('created_at', 'DESC')->paginate(10);

        return view('employees.duty_index', [
            'list' => $list,
        ]);
    }

    public function storeDuty(Request $r) {
        $c = HertDuty::create([
            'event_name' => mb_strtoupper($r->event_name),
            'description' => ($r->description) ? mb_strtoupper($r->description) : NULL,
            'event_date' => $r->event_date,
            'status' => 'OPEN',

            'created_by' => Auth::id(),
        ]);

        return redirect()->route('duty_view', $c->id)
        ->with('msg', 'Duty was successfully created. You may now encode employees to deploy as responders.')
        ->with('msgtype', 'success');
    }

    public function viewDuty($duty_id) {
        $d = HertDuty::findOrFail($duty_id);

        $duty_qry = Employee::where('employment_status', 'ACTIVE')
        ->where('duty_canbedeployed', 'Y')
        ->where(function ($q) {
            $q->where('duty_canbedeployedagain', 'Y')
            ->orWhere('duty_completedcycle', 'N');
        });

        $teama_list = (clone $duty_qry)->where('duty_team', 'A')->orderBy('lname', 'ASC')->get();
        $teamb_list = (clone $duty_qry)->where('duty_team', 'B')->orderBy('lname', 'ASC')->get();
        $teamc_list = (clone $duty_qry)->where('duty_team', 'C')->orderBy('lname', 'ASC')->get();
        $teamd_list = (clone $duty_qry)->where('duty_team', 'D')->orderBy('lname', 'ASC')->get();
        
        $current_list = HertDutyMember::where('event_id', $d->id)->get();

        return view('employees.duty_edit', [
            'd' => $d,
            'teama_list' => $teama_list,
            'teamb_list' => $teamb_list,
            'teamc_list' => $teamc_list,
            'teamd_list' => $teamd_list,

            'current_list' => $current_list,
        ]);
    }

    public function updateDuty($duty_id, Request $r) {
        
    }

    public function storeEmployeeToDuty($duty_id, Request $r) {
        $d = HertDuty::findOrFail($duty_id);

        $check = HertDutyMember::where('event_id', $d->id)
        ->where('employee_id', $r->employee_id)
        ->first();

        if(!$check) {
            $c = HertDutyMember::create([
                'event_id' => $d->id,
                'employee_id' => $r->employee_id,
                'created_by' => Auth::id(),
            ]);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Employee already exists in this Event. Please try another.')
            ->with('msgtype', 'warning');
        }

        $u = Employee::findOrFail($r->employee_id);

        $u->duty_completedcycle = 'Y';

        if($u->isDirty()) {
            $u->save();
        }

        return redirect()->back()
        ->with('msg', 'Successfully added as responder.')
        ->with('msgtype', 'success');
    }

    public function removeEmployeeToDuty($duty_id, Request $r) {

    }
}
