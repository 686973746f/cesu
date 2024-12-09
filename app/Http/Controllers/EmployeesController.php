<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HertDuty;
use App\Models\DutyCycle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HertDutyMember;
use App\Models\HertDutyPatient;
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
            'bls_typeofrescuer' => $r->bls_typeofrescuer,
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
            'bls_typeofrescuer' => $r->bls_typeofrescuer,
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
        $tot_emp_duty_male = (clone $duty_qry)->where('gender', 'M')->count();
        $tot_emp_duty_female = (clone $duty_qry)->where('gender', 'F')->count();
        $tot_emp_duty_alreadyassigned = (clone $duty_qry)->where('duty_completedcycle', 'Y')->count();
        $tot_emp_duty_notyetassigned = $tot_emp_duty - $tot_emp_duty_alreadyassigned;

        $ta_total = (clone $duty_qry)->where('duty_team', 'A')->count();
        $tb_total = (clone $duty_qry)->where('duty_team', 'B')->count();
        $tc_total = (clone $duty_qry)->where('duty_team', 'C')->count();
        $td_total = (clone $duty_qry)->where('duty_team', 'D')->count();

        $ta_deployed = (clone $duty_qry)->where('duty_team', 'A')->where('duty_completedcycle', 'Y')->count();
        $ta_notdeployed = $ta_total - $ta_deployed;

        $tb_deployed = (clone $duty_qry)->where('duty_team', 'B')->where('duty_completedcycle', 'Y')->count();
        $tb_notdeployed = $tb_total - $tb_deployed;

        $tc_deployed = (clone $duty_qry)->where('duty_team', 'C')->where('duty_completedcycle', 'Y')->count();
        $tc_notdeployed = $tc_total - $tc_deployed;

        $td_deployed = (clone $duty_qry)->where('duty_team', 'D')->where('duty_completedcycle', 'Y')->count();
        $td_notdeployed = $td_total - $td_deployed;

        $list = HertDuty::orderBy('created_at', 'DESC')->paginate(10);

        $cycle_count = DutyCycle::count() + 3;

        return view('employees.duty_index', [
            'list' => $list,
            'tot_emp_duty' => $tot_emp_duty,
            'tot_emp_duty_male' => $tot_emp_duty_male,
            'tot_emp_duty_female' => $tot_emp_duty_female,
            'tot_emp_duty_alreadyassigned' => $tot_emp_duty_alreadyassigned,
            'tot_emp_duty_notyetassigned' => $tot_emp_duty_notyetassigned,
            'cycle_count' => $cycle_count,

            'ta_total' => $ta_total,
            'tb_total' => $tb_total,
            'tc_total' => $tc_total,
            'td_total' => $td_total,

            'ta_deployed' => $ta_deployed,
            'ta_notdeployed' => $ta_notdeployed,
            'tb_deployed' => $tb_deployed,
            'tb_notdeployed' => $tb_notdeployed,
            'tc_deployed' => $tc_deployed,
            'tc_notdeployed' => $tc_notdeployed,
            'td_deployed' => $td_deployed,
            'td_notdeployed' => $td_notdeployed,
        ]);
    }

    public function storeDuty(Request $r) {
        $c = HertDuty::create([
            'event_name' => mb_strtoupper($r->event_name),
            'description' => ($r->description) ? mb_strtoupper($r->description) : NULL,
            'event_date' => $r->event_date,
            'status' => 'OPEN',
            'code' => mb_strtoupper(Str::random(5)),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('duty_view', $c->id)
        ->with('msg', 'Duty was successfully created. You may now encode employees to deploy as responders.')
        ->with('msgtype', 'success');
    }

    public function dutyMainOptions(Request $r) {
        if($r->submit == 'reset_cycle') {
            //Create Duty Cycle Mark
            $s = DutyCycle::latest()->first();
            if($s) {
                $s->date_ended = date('Y-m-d');
                if($s->isDirty()) {
                    $s->save();
                }

                $c = DutyCycle::create([
                    'date_started' => date('Y-m-d'),
                ]);
            }
            else {
                $c = DutyCycle::create([
                    'date_started' => date('Y-m-d'),
                    'date_ended' => date('Y-m-d'),
                ]);
            }

            $u = Employee::where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->where('duty_completedcycle', 'Y')
            ->update([
                'duty_completedcycle' => 'N',
            ]);

            return redirect()->back()
            ->with('msg', 'Reset of Duty Cycle was processed successfully. New Cycle Count was added.')
            ->with('msgtype', 'success');
        }
        else {

        }
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
        
        $current_list = HertDutyMember::with('employee')
        ->where('event_id', $d->id)
        ->get()
        ->sortBy(function ($duty) {
            return $duty->employee->lname;
        })
        ->values();

        return view('employees.duty_edit', [
            'd' => $d,
            'teama_list' => $teama_list,
            'teamb_list' => $teamb_list,
            'teamc_list' => $teamc_list,
            'teamd_list' => $teamd_list,

            'current_list' => $current_list,
        ]);
    }

    public function updateDuty($event_id, Request $r) {
        $d = HertDuty::findOrFail($event_id);

        $d->event_name = mb_strtoupper($r->event_name);
        $d->description = ($r->description) ? mb_strtoupper($r->description) : NULL;
        $d->event_date = $r->event_date;
        $d->status = $r->status;

        if($d->isDirty('status')) {
            if($r->status == 'PENDING' || $r->status == 'COMPLETED') {
                //Lock in all Responders

                $u = HertDutyMember::where('event_id', $event_id)
                ->update([
                    'locked_in' => 'Y',
                ]);
            }
        }

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()->back()
        ->with('msg', 'Event details were successfully updated.')
        ->with('msgtype', 'success');
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

        //Reset Cycle if All Employees Completed the Cycle
        $cycle_check = Employee::where('employment_status', 'ACTIVE')
        ->where('duty_canbedeployed', 'Y')
        ->where('duty_completedcycle', 'N')
        ->first();

        $msg = 'Responder '.$u->getName().' was successfully added to the list.';

        if(!$cycle_check) {
            //Create Duty Cycle Mark
            $s = DutyCycle::latest()->first();
            if($s) {
                $s->date_ended = date('Y-m-d');
                if($s->isDirty()) {
                    $s->save();
                }

                $c = DutyCycle::create([
                    'date_started' => date('Y-m-d'),
                ]);
            }
            else {
                $c = DutyCycle::create([
                    'date_started' => date('Y-m-d'),
                    'date_ended' => date('Y-m-d'),
                ]);
            }

            $u = Employee::where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->where('duty_completedcycle', 'Y')
            ->update([
                'duty_completedcycle' => 'N',
            ]);

            $msg = $msg.' Duty Cycle Reset was also processed.';
        }

        return redirect()->back()
        ->with('msg', $msg)
        ->with('msgtype', 'success');
    }

    public function removeEmployeeToDuty($duty_id, $member_id) {
        $d = HertDuty::findOrFail($duty_id);

        $m = HertDutyMember::find($member_id);

        //Reset Completed Cycle to N
        $p = Employee::findOrFail($m->employee_id);
        $p->duty_completedcycle = 'N';
        if($p->isDirty()) {
            $p->save();
        }

        $m->delete();

        return redirect()->back()
        ->with('msg', 'Responder '.$p->getName().' has been removed from the list.')
        ->with('msgtype', 'success');
    }

    public function viewDutyPatients($id) {
        if(auth()->check()) {
            $d = HertDuty::findOrFail($id);

            $index_route = route('duty_viewpatients', $d->id);
            $store_route = route('duty_storepatient', $d->id);
            $update_route = 'duty_updatepatient';
            $edit_route = 'duty_editpatient';
        }
        else {
            $d = HertDuty::where('code', $id)->first();

            if(!$d) {
                return abort(401);
            }

            $index_route = route('online_duty_viewpatients', $d->id);
            $store_route = route('online_duty_storepatient', $d->id);
            $update_route = 'online_duty_updatepatient';
            $update_route = 'online_duty_updatepatient';
        }

        $list = HertDutyPatient::where('event_id', $d->id)
        ->get();

        return view('employees.duty_patient_index', [
            'd' => $d,
            'list' => $list,

            'index_route' => $index_route,
            'store_route' => $store_route,
            'update_route' => $update_route,
        ]);
    }

    public function storeDutyPatients($id, Request $r) {
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        
        $table_params = [
            'lname' => $lname,
            'fname' => $fname,
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'age_years' => $r->age_years,
            'sex' => $r->sex,

            'contact_number' => $r->contact_number,
            'street_purok' => $r->street_purok,
            'address_brgy_code' => $r->address_brgy_code,
            'chief_complaint' => mb_strtoupper($r->chief_complaint),
            'actions_taken' => $r->actions_taken,
            'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
        ];

        if(auth()->check()) {
            $d = HertDuty::findOrFail($id);

            $table_params = $table_params + [
                'event_id' => $d->id,
                'created_by' => Auth::id(),
            ];            
        }
        else {
            $d = HertDuty::where('code', $id)->first();

            if($d) {
                $table_params = $table_params + [
                    'event_id' => $d->id,
                ];
            }
            else {
                return abort(401);
            }
        }

        $check = HertDutyPatient::where('event_id', $d->id)
        ->where('lname', $lname)
        ->where('fname', $fname)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Error: Patient already exists in this event. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }
        else {
            $c = HertDutyPatient::create($table_params);
        }

        return redirect()->back()
        ->with('msg', 'Patient '.$c->getName().' was added to the list successfully.')
        ->with('msgtype', 'success');
    }

    public function editDutyPatients($id, $patient_id) {
        if(auth()->check()) {
            $d = HertDuty::findOrFail($id);
        }
        else {
            $d = HertDuty::where('code', $id)->first();
        }
    }

    public function updateDutyPatients($patient_id, Request $r) {
        if($r->submit == 'update') {

        }
        else if($r->submit == 'delete') {

        }
        else {
            return abort(401);
        }
    }

    public function blsIndex() {

    }

    public function viewBlsMasterlist() {

    }

    public function storeBlsEvent(Request $r) {
        
    }

    public function viewBlsEvent($bls_id) {

    }

    public function updateBlsEvent($bls_id, Request $r) {
        
    }

    public function storeBlsMemberOnEvent($bls_id, Request $r) {

    }
}
