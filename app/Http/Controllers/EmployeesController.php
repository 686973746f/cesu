<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BlsMain;
use App\Models\Employee;
use App\Models\HertDuty;
use Carbon\CarbonPeriod;
use App\Models\BlsMember;
use App\Models\DutyCycle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HertDutyMember;
use App\Models\AttendanceSheet;
use App\Models\HertDutyPatient;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use App\Models\AttendanceSheetEvents;
use App\Models\BlsBatchParticipant;
use App\Models\EmployeeAttendanceSheet;
use App\Models\EmploymentStatusUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EmployeesController extends Controller
{
    public function index() {
        if(request()->input('showAll')) {
            $list = Employee::get();
        }
        else {
            $list = Employee::where('employment_status', 'ACTIVE')->get();
        }
    
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
            'profession_suffix' => ($r->profession_suffix) ?$r->profession_suffix : NULL,
            'gender' => $r->gender,
            'bdate' => $r->bdate ?: NULL,
            'contact_number' => $r->contact_number ?: NULL,
            'prc_license_no' => $r->prc_license_no,
            'tin_no' => $r->tin_no,
            'philhealth_pan' => $r->philhealth_pan,
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
            'is_herotrained' => $r->is_herotrained,
            'is_washntrained' => $r->is_washntrained,
            'is_nutriemergtrained' => $r->is_nutriemergtrained,
            'recent_bls_date' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            //'bls_id' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            'bls_typeofrescuer' => $r->bls_typeofrescuer,
            //'bls_codename',
            'duty_canbedeployed' => $r->duty_canbedeployed,
            'duty_canbedeployedagain' => $r->duty_canbedeployedagain,
            'duty_team' => $r->duty_team,
            'duty_completedcycle' => 'N',
            'abtc_vaccinator_branch' => $r->abtc_vaccinator_branch,
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
        $atbc_branch_list = AbtcVaccinationSite::where('enabled', 1)->get();

        if($mode == 'EDIT') {
            $employmentupdate_list = $record->employeestatus()->orderBy('effective_date', 'DESC')->get();
        }

        return view('employees.new_or_edit', [
            'd' => $record,
            'mode' => $mode,
            'emp_access_list' => $emp_access_list,
            'atbc_branch_list' => $atbc_branch_list,
            'employmentupdate_list' => $employmentupdate_list ?? NULL,
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
            'profession_suffix' => ($r->profession_suffix) ? $r->profession_suffix : NULL,
            'gender' => $r->gender,
            'bdate' => $r->bdate ?: NULL,
            'contact_number' => $r->contact_number ?: NULL,
            'prc_license_no' => $r->prc_license_no,
            'tin_no' => $r->tin_no,
            'philhealth_pan' => $r->philhealth_pan,
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
            'is_herotrained' => $r->is_herotrained,
            'is_washntrained' => $r->is_washntrained,
            'is_nutriemergtrained' => $r->is_nutriemergtrained,
            'recent_bls_date' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            //'bls_id' => ($r->is_blstrained == 'Y') ? $r->recent_bls_date : NULL,
            'bls_typeofrescuer' => $r->bls_typeofrescuer,
            //'bls_codename',
            'duty_canbedeployed' => $r->duty_canbedeployed,
            'duty_canbedeployedagain' => $r->duty_canbedeployedagain,
            'duty_team' => $r->duty_team,
            'abtc_vaccinator_branch' => $r->abtc_vaccinator_branch,
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
        ->where('duty_canbedeployed', 'Y')
        ->whereNotNull('duty_team');

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
        $cycle_number = DutyCycle::count() + 3;

        $c = HertDuty::create([
            'event_name' => mb_strtoupper($r->event_name),
            'description' => ($r->description) ? mb_strtoupper($r->description) : NULL,
            'event_date' => $r->event_date,
            'status' => 'OPEN',
            'code' => mb_strtoupper(Str::random(5)),
            'cycle_number' => $cycle_number,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('duty_view', $c->id)
        ->with('msg', 'Duty was successfully created. You may now encode add responders to the list.')
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

            //Increase Count of Duty Balance sa mga di dumuty last cycle
            $u2 = Employee::where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->where('duty_completedcycle', 'N')
            ->where('excess_duty', 0)
            ->whereNotNull('duty_team')
            ->update([
                'duty_balance' => DB::raw('duty_balance + 1'),
            ]);

            //Kapag may Excess Duty tapos hindi dumuty sa current cycle, hindi madadagdagan ng duty_balance pero mababawasan ng excess_duty
            $u3 = Employee::where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->where('duty_completedcycle', 'N')
            ->where('excess_duty', '>', 0)
            ->whereNotNull('duty_team')
            ->update([
                'duty_balance' => DB::raw('excess_duty - 1'),
            ]);

            $u = Employee::query()->update([
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
        ->where('duty_canbedeployed', 'Y');

        if(!request()->input('override')) {
            $duty_qry = $duty_qry->where(function ($q) {
                $q->where('duty_canbedeployedagain', 'Y')
                ->orWhere('duty_completedcycle', 'N')
                ->orWhere('duty_balance', '>', 0);
            });
        }

        $teama_list = (clone $duty_qry)->where('duty_team', 'A')->orderBy('lname', 'ASC')->get();
        $teamb_list = (clone $duty_qry)->where('duty_team', 'B')->orderBy('lname', 'ASC')->get();
        $teamc_list = (clone $duty_qry)->where('duty_team', 'C')->orderBy('lname', 'ASC')->get();
        $teamd_list = (clone $duty_qry)->where('duty_team', 'D')->orderBy('lname', 'ASC')->get();

        $standin_list = (clone $duty_qry)->orderBy('lname', 'ASC')->get();
        
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
            'standin_list' => $standin_list,
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
            $u = Employee::findOrFail($r->employee_id);

            if($r->standin_checkbox && $r->standin_id == $r->employee_id) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to stand-in the same Employee.')
                ->with('msgtype', 'warning');
            }

            $c = HertDutyMember::create([
                'event_id' => $d->id,
                'employee_id' => $r->employee_id,
                'excessduty_beforejoining' => $u->excess_duty,
                'dutybalance_beforejoining' => $u->duty_balance,
                'standin_id' => ($r->standin_checkbox) ? $r->standin_id : NULL,
                'remarks' => $r->remarks,
                'created_by' => Auth::id(),
            ]);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Employee already exists in this Event. Please try another.')
            ->with('msgtype', 'warning');
        }

        //Reduce Duty Balance/Add Excess Duty if pangalawang beses na dumuty for the current Cycle
        if($u->duty_completedcycle == 'Y') {
            if($u->duty_balance > 0) {
                $u->duty_balance = $u->duty_balance - 1;
            }
            else {
                $u->excess_duty = $u->excess_duty + 1;
            }
        }

        $u->duty_completedcycle = 'Y';

        if($u->isDirty()) {
            $u->save();
        }

        //Reset Cycle if All Employees Completed the Cycle
        /*
        $cycle_check = Employee::where('employment_status', 'ACTIVE')
        ->where('duty_canbedeployed', 'Y')
        ->where('duty_completedcycle', 'N')
        ->first();
        */

        $msg = 'Responder '.$u->getName().' was successfully added to the list.';

        /*
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
        */

        return redirect()->back()
        ->with('msg', $msg)
        ->with('msgtype', 'success');
    }

    public function removeEmployeeToDuty($duty_id, $member_id) {
        $d = HertDuty::findOrFail($duty_id);

        $m = HertDutyMember::find($member_id);
        
        $p = Employee::findOrFail($m->employee_id);
        //Return Balance Duty if di nakapag-duty last cycle
        $p->duty_balance = $m->dutybalance_beforejoining;
        $p->excess_duty = $m->excessduty_beforejoining;

        $dutycycle = DutyCycle::count() + 3;

        //Reset Completed Cycle to N
        if($p->duty_balance == 0) {
            //Search Employee in Current Duty Cycle para hindi ma-reset to N
            $duties = HertDuty::where('cycle_number', $dutycycle)
            ->where('id', '!=', $d->id)
            ->whereHas('members', function ($q) use ($m) {
                $q->where('employee_id', $m->employee_id);
            })
            ->exists();

            if($duties) {
                $p->duty_completedcycle = 'Y';
            }
            else {
                $p->duty_completedcycle = 'N';
            }
        }

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

        $complaint_array = $r->chief_complaint;
        
        $table_params = [
            'lname' => $lname,
            'fname' => $fname,
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'age_years' => $r->age_years,
            'sex' => $r->sex,

            'contact_number' => $r->contact_number,
            'street_purok' => ($r->street_purok) ? mb_strtoupper($r->street_purok) : NULL,
            'address_brgy_code' => $r->address_brgy_code,
            'chief_complaint' => implode(",", $complaint_array),
            'lastmeal_taken' => $r->lastmeal_taken,
            'diagnosis' => ($r->diagnosis) ? mb_strtoupper($r->diagnosis) : NULL,
            'actions_taken' => ($r->actions_taken) ? mb_strtoupper($r->actions_taken) : NULL,
            
            'remarks' => ($r->remarks) ? mb_strtoupper($r->remarks) : NULL,
        ];

        if(in_array("CHECK BP", $complaint_array)) {
            $table_params = $table_params + [
                'bp' => $r->bp1.'/'.$r->bp2,
            ];
        }

        if(in_array("OTHERS", $complaint_array)) {
            $table_params = $table_params + [
                'other_complains' => mb_strtoupper($r->other_complains),
            ];
        }

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

    public function blsHomeMasterlist() {
        $total_trained = BlsMember::count();

        $list = BlsMember::where('enabled', 'Y')
        ->orderBy('lname', 'ASC')
        ->get();

        return view('employees.bls.view_master_list', [
            'list' => $list,
            'total_trained' => $total_trained,
        ]);
    }

    public function blsHomeBatches() {
        $list = BlsMain::orderBy('created_at', 'DESC')->paginate(10);

        $list_institutions = BlsMember::distinct()
        ->orderBy('institution', 'asc')
        ->pluck('institution');

        return view('employees.bls.view_batches_list', [
            'list' => $list,
            'list_institutions' => $list_institutions,
        ]);
    }

    public function viewBlsBatch($batch_id) {
        $d = BlsMain::findOrFail($batch_id);

        $qry = BlsBatchParticipant::where('batch_id', $batch_id);

        $member_list = (clone $qry)->get();

        if($member_list->count() != 0) {
            $exclude_ids = (clone $qry)->pluck('member_id')->toArray();

            $possible_participants_list = BlsMember::whereNotIn('id', $exclude_ids)->orderBy('lname', 'DESC')->get();
        }
        else {
            $possible_participants_list = BlsMember::get();
        }

        $list_institutions = BlsMember::distinct()
        ->orderBy('institution', 'asc')
        ->pluck('institution');

        return view('employees.bls.view_batch', [
            'd' => $d,
            'member_list' => $member_list,
            'possible_participants_list' => $possible_participants_list,
            'list_institutions' => $list_institutions,
        ]);
    }

    public function storeBlsBatch(Request $r) {

        $check = BlsMain::where('batch_number', $r->batch_number)->first();
        
        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Batch Number already exists. Please double check and try again.')
            ->with('msgtype', 'warning');
        }

        $check = BlsMain::where('batch_name', mb_strtoupper($r->batch_name))->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Batch Name already exists. Please double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = BlsMain::create([
            'batch_number' => $r->batch_number,
            'batch_name' => mb_strtoupper($r->batch_name),
            'is_refresher' => ($r->is_refresher) ? 'Y' : 'N',
            'agency' => mb_strtoupper($r->agency),
            'training_date_start' => $r->training_date_start,
            'training_date_end' => $r->training_date_end,
            'venue' => mb_strtoupper($r->venue),
            'instructors_list' => mb_strtoupper($r->instructors_list),
            'prepared_by' => mb_strtoupper($r->prepared_by),

            'created_by' => Auth::id(),
        ]);

        return redirect()->route('bls_viewbatch', $c->id)
        ->with('msg', 'BLS Batch was successfully created. You may now encode participants to this batch.')
        ->with('msgtype', 'success');
    }

    public function updateBlsBatch($batch_id, Request $r) {
        
    }

    public function storeBlsMember(Request $r) {
        $check = BlsMember::where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname))
        ->first();

        if($check) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Participant already exists in this batch. Please double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = BlsMember::create([
            'cho_employee' => ($r->cho_employee) ? 'Y' : 'N',
            'employee_id' => ($r->cho_employee) ? $r->employee_id : NULL,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->suffix) ? mb_strtoupper($r->suffix) : NULL,
            'bdate' => $r->bdate,
            'gender' => $r->gender,
            'provider_type' => $r->provider_type,
            'position' => mb_strtoupper($r->position),
            'institution' => ($r->institution != 'UNLISTED') ? mb_strtoupper($r->institution) : mb_strtoupper($r->institution_other),
            'employee_type' => $r->employee_type,
            
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'codename' => mb_strtoupper($r->codename),
            
            'created_by' => Auth::id(),
        ]);

        if($r->autojoin_batchid) {
            $autojoin_params = [
                'batch_id' => $r->autojoin_batchid,
                'member_id' => $c->id,

                'created_by' => Auth::id(),
            ];

            if($r->autopass) {
                $autojoin_params = $autojoin_params + [
                    'sfa_ispassed' => 'P',
                    'bls_cognitive_ispassed' => 'P',
                    'bls_psychomotor_ispassed' => 'P',
                    'bls_finalremarks' => 'P',
                ];
            }
            else {
                $autojoin_params = $autojoin_params + [
                    'sfa_ispassed' => 'W',
                    'bls_cognitive_ispassed' => 'W',
                    'bls_psychomotor_ispassed' => 'W',
                    'bls_finalremarks' => 'W',
                ];
            }

            $d = BlsBatchParticipant::create($autojoin_params);
        }

        return redirect()->back()
        ->with('msg', 'Participant '.$c->getName().' was successfully added to the list.')
        ->with('msgtype', 'success');
    }

    public function joinParticipant($batch_id, Request $r) {
        $check = BlsBatchParticipant::where('batch_id', $batch_id)
        ->where('member_id', $r->member_id)->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Error: Participant '.$check->member->getName().' was already inside the list. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = BlsBatchParticipant::create([
            'batch_id' => $batch_id,
            'member_id' => $r->member_id,

            'sfa_ispassed' => ($r->autopass) ? 'P' : 'W',
            'bls_cognitive_ispassed' => ($r->autopass) ? 'P' : 'W',
            'bls_psychomotor_ispassed' => ($r->autopass) ? 'P' : 'W',
            'bls_finalremarks' => ($r->autopass) ? 'P' : 'W',

            'created_by' => Auth::id(),
        ]);

        return redirect()->back()
        ->with('msg', 'Participant '.$c->member->getName().' was successfully added to the list.')
        ->with('msgtype', 'success');
    }

    public function viewBlsMember($participant_id) {
        $d = BlsBatchParticipant::findOrFail($participant_id);

        $list_institutions = BlsMember::distinct()
        ->orderBy('institution', 'asc')
        ->pluck('institution');

        return view('employees.bls.participant_edit', [
            'd' => $d,
            'list_institutions' => $list_institutions,
        ]);
    }

    public function updateBlsMember($participant_id, Request $r) {
        $d = BlsBatchParticipant::findOrFail($participant_id);

        if($r->bls_finalremarks == 'P' && $d->batch->is_refresher == 'N') {
            if($r->sfa_ispassed != 'P' || $r->bls_cognitive_ispassed != 'P' || $r->bls_psychomotor_ispassed != 'P') {
                return redirect()->back()
                ->withInput()
                ->with('msg', 'Error: You cannot use "Passed" as Final Remarks if SFA or BLS (Cognitive) or BLS Psychomotor is FAILED or PENDING. Kindly double check and try again.')
                ->with('msgtype', 'warning');
            }
        }

        $member_params = [
            'cho_employee' => ($r->cho_employee) ? 'Y' : 'N',
            'employee_id' => ($r->cho_employee) ? $r->employee_id : NULL,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->mname) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->suffix) ? mb_strtoupper($r->suffix) : NULL,
            'bdate' => $r->bdate,
            'gender' => $r->gender,
            'provider_type' => $r->provider_type,
            'position' => mb_strtoupper($r->position),
            'institution' => ($r->institution != 'UNLISTED') ? mb_strtoupper($r->institution) : mb_strtoupper($r->institution_other),
            'employee_type' => $r->employee_type,
            
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'codename' => mb_strtoupper($r->codename),

            'updated_by' => Auth::id(),
        ];

        $batch_params = [
            'sfa_notes' => ($r->filled('sfa_notes')) ? mb_strtoupper($r->sfa_notes) : NULL,
            'bls_pretest' => $r->bls_pretest,
            'bls_posttest' => $r->bls_posttest,
            'bls_remedial' => $r->bls_remedial,
            'bls_cognitive_ispassed' => $r->bls_cognitive_ispassed,
            'bls_cpr_adult' => $r->bls_cpr_adult,
            'bls_cpr_infant' => $r->bls_cpr_infant,
            'bls_fbao_adult' => $r->bls_fbao_adult,
            'bls_fbao_infant' => $r->bls_fbao_infant,
            'bls_rb_adult' => $r->bls_rb_adult,
            'bls_rb_infant' => $r->bls_rb_infant,
            'bls_psychomotor_ispassed' => $r->bls_psychomotor_ispassed,
            'bls_affective' => $r->bls_affective,
            'bls_finalremarks' => $r->bls_finalremarks,
            'bls_notes' => ($r->filled('bls_notes')) ? mb_strtoupper($r->bls_notes) : NULL,
            'bls_id_number' => $r->bls_id_number,
            'sfa_id_number' => $r->sfa_id_number,
            'bls_expiration_date' => $r->bls_expiration_date,

            'updated_by' => Auth::id(),
        ];

        if($d->batch->is_refresher == 'N') {
            $batch_params = $batch_params + [
                'sfa_pretest' => $r->sfa_pretest,
                'sfa_posttest' => $r->sfa_posttest,
                'sfa_remedial' => $r->sfa_remedial,
                'sfa_ispassed' => $r->sfa_ispassed,
            ];
        }

        if($r->hasFile('picture')) {
            if ($r->file('picture')->isValid()) {
                //$file = $r->file('picture');
                $r->validate([
                    'picture' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max size
                ]);
                
                $id_file_name = Str::random(10) . '.' . $r->file('picture')->extension();
                $r->file('picture')->move($_SERVER['DOCUMENT_ROOT'].'/assets/bls/members/', $id_file_name);

                //Delete Old Picture
                $oldPicture = $d->member->picture;

                if(!is_null($oldPicture)) {
                    File::delete('bls/members/'.$oldPicture);
                }

                $batch_params = $batch_params + [
                    'picture' => $id_file_name,
                ];
            }
        }

        $u = BlsMember::where('id', $d->member->id)
        ->update($member_params);

        $u = BlsBatchParticipant::where('id', $d->id)
        ->update($batch_params);
        
        return redirect()->route('bls_viewbatch', $d->batch->id)
        ->with('msg', 'Participant '.$d->member->getName().' was successfully updated.')
        ->with('msgtype', 'success');
    }

    public function ajaxListEmployees(Request $r) {
        $list = [];

        if($r->has('q') && strlen($r->input('q')) > 1) {
            $search = mb_strtoupper($r->q);

            $data = Employee::where(function ($query) use ($search) {
                $query->where(function ($r) use ($search) {
                    $r->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                    ->orWhere('id', $search);
                });
            })
            ->where('employment_status', 'ACTIVE')
            ->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->id.' - '.$item->getName(),
                ]);
            }
        }
        
        return response()->json($list);
    }

    public function downloadBlsDatabase($batch_id) {
        $d = BlsMain::findOrFail($batch_id);

        $list = BlsBatchParticipant::where('batch_id', $d->id)->get();

        $spreadsheet = IOFactory::load(storage_path('BLSDATABASE_2024.xlsx'));
        $sheet = $spreadsheet->getActiveSheet('SFA');

        $c = 12;

        foreach($list as $l) {
            //$imagePath = realpath(public_path('assets/bls/members/'.$d->picture));
            $imagePath = public_path('assets/bls/members/'.$d->picture);

            // Convert forward slashes to backslashes for Windows compatibility
            $imagePath = str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
            //dd($imagePath);

            if (file_exists($imagePath)) {
                $drawing = new Drawing();
                $drawing->setName('Member Image');
                $drawing->setDescription('BLS Member Photo');
                $drawing->setPath($imagePath);
                $drawing->setHeight(100); // Adjust the image height
                $drawing->setCoordinates('L'.$c); // Adjust position as needed
                $drawing->setWorksheet($sheet);
            } else {
                $sheet->setCellValue('L'.$c, 'Image not found');
            }

            //$sheet->setCellValue('L'.$c, 'PICTURE HERE');
            $c++;
        }
        

        $fileName = 'bls_test_'.strtolower(Str::random(5)).'.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function viewEmployeesDutyOnline() {
        if(is_null(request()->input('masterlistView'))) {
            $team_a = Employee::where('duty_team', 'A')
            ->where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->orderBy('lname', 'ASC')
            ->get();

            $team_b = Employee::where('duty_team', 'B')
            ->where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->orderBy('lname', 'ASC')
            ->get();

            $team_c = Employee::where('duty_team', 'C')
            ->where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->orderBy('lname', 'ASC')
            ->get();

            $team_d = Employee::where('duty_team', 'D')
            ->where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->orderBy('lname', 'ASC')
            ->get();

            $list = NULL;
        }
        else {
            $team_a = NULL;
            $team_b = NULL;
            $team_c = NULL;
            $team_d = NULL;

            $list = Employee::where('employment_status', 'ACTIVE')
            ->where('duty_canbedeployed', 'Y')
            ->orderBy('lname', 'ASC')
            ->get();
        }

        $duty_qry = Employee::where('employment_status', 'ACTIVE')
        ->where('duty_canbedeployed', 'Y')
        ->whereNotNull('duty_team');

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

        $cycle_count = DutyCycle::count() + 3;

        return view('employees.duty_online_view', [
            'team_a' => $team_a,
            'team_b' => $team_b,
            'team_c' => $team_c,
            'team_d' => $team_d,

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

            'list' => $list,
        ]);
    }

    public function viewAttendanceSheet($id) {
        $month = request()->input('month', date('m'));
        $year = request()->input('year', date('Y'));

        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        $dates = CarbonPeriod::create($start, $end);

        $employee = Employee::findOrFail($id);

        // Get saved attendance for this employee
        $records = EmployeeAttendanceSheet::where('employee_id', $id)
            ->whereBetween('for_date', [$start, $end])
            ->get()
            ->keyBy('for_date'); // so you can access like $records['2025-09-01']

        return view('employees.attendance_sheet', compact('employee', 'dates', 'records', 'month', 'year'));
    }

    public function storeAttendanceSheet($id, Request $r) {
        $attendanceData = $r->input('attendance', []);

        foreach ($attendanceData as $for_date => $status) {
            //Check if Saturday, Sunday, Holiday or Listed on TO

            $selected_date = Carbon::parse($for_date);

            //AttendanceSheetEvents::

            if($selected_date->dayOfWeek != Carbon::SATURDAY && $selected_date->dayOfWeek != Carbon::SUNDAY) {
                
            }

            if (isset($status['is_present'])) {
                // Insert/Update record
                EmployeeAttendanceSheet::updateOrCreate(
                    [
                        'employee_id' => $id,
                        'for_date' => $for_date,
                    ],
                    [
                        'is_halfday' => isset($status['is_halfday']) ? 1 : 0,
                        'timein_am'  => $status['timein_am'] ?? '08:00',
                        'timeout_am' => $status['timeout_am'] ?? '12:00',
                        'timein_pm'  => $status['timein_pm'] ?? '13:00',
                        'timeout_pm' => $status['timeout_pm'] ?? '17:00',
                    ]
                );
            } else {
                // Delete record = absent
                EmployeeAttendanceSheet::where('employee_id', $id)
                    ->where('for_date', $for_date)
                    ->delete();
            }
        }

    return redirect()->back()->with('success', 'Attendance saved successfully!');
    }

    public function updateEmploymentStatus($id, Request $r) {
        $d = Employee::findOrFail($id);

        $duplicate_check = EmploymentStatusUpdate::where('request_uuid', $r->request_uuid)->first();

        if($duplicate_check) {
            return redirect()->back()
            ->with('msg', 'ERROR: Duplicate transaction detected.')
            ->with('msgtype', 'warning');
        }

        if($r->update_type == 'RESIGNED' || $r->update_type == 'RETIRED' || $r->update_type == 'END OF CONTRACT' || $r->update_type == 'TERMINATED') {
            $status = 'INACTIVE';
            $d->employment_status = 'INACTIVE';
        }
        else {
            $status = 'ACTIVE';
            $d->employment_status = 'ACTIVE';
        }

        $table_params = [
            'request_uuid' => $r->request_uuid,
            'status' => $status,

            'employee_id' => $d->id,

            'update_type' => $r->update_type,
            'effective_date' => $r->effective_date,
            'resigned_remarks' => ($r->update_type == "RESIGNED") ? mb_strtoupper($r->up_resigned_remarks) : NULL,
            'terminated_remarks' => ($r->update_type == "TERMINATED") ? mb_strtoupper($r->up_terminated_remarks) : NULL,

            //'source',
            'created_by' => Auth::id(),
        ];

        if($r->update_type == 'INITIAL' || $r->update_type == 'CHANGE' || $r->update_type == 'PROMOTION') {
            $table_params = $table_params + [
                'job_type' => $r->up_job_type,
                'job_position' => mb_strtoupper($r->up_job_position),
                'office' => $r->up_office,
                'sub_office' => ($r->filled('up_sub_office')) ? mb_strtoupper($r->up_sub_office) : NULL,
            ];
        }

        $c = EmploymentStatusUpdate::create($table_params);

        $d->save();

        return redirect()->back()
        ->with('msg', 'Employment Status Update request for '.$d->getName().' was successfully submitted.')
        ->with('msgtype', 'success');
    }
}
