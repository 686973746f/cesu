<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Forms;
use App\Models\WorkTask;
use App\Models\LiveBirth;
use Illuminate\Http\Request;
use App\Models\VaxcertConcern;
use App\Models\DeathCertificate;
use App\Models\SyndromicRecords;
use App\Models\AbtcBakunaRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\MonthlyAccomplishmentChecker;

class TaskController extends Controller
{
    public function index() {
        if(!auth()->user()->itr_facility_id) {
            return redirect()->back()
            ->with('msg', 'Error: User has not yet assigned into an OPD Facility. Please contact CESU Admin for assistance.')
            ->with('msgtype', 'warning');
        }
        
        if(!auth()->user()->abtc_default_vaccinationsite_id) {
            return redirect()->back()
            ->with('msg', 'Error: User has not yet assigned into an ABTC Facility. Please contact CESU Admin for assistance.')
            ->with('msgtype', 'warning');
        }

        $open_worklist = WorkTask::where('status', 'OPEN')
        ->paginate(10);

        $open_opdlist = SyndromicRecords::where('facility_id', auth()->user()->itr_facility_id)
        ->whereNull('ics_grabbedby')
        ->whereDate('created_at', '>=', '2024-05-31')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        $open_abtclist = AbtcBakunaRecords::where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->whereNull('ics_grabbedby')
        ->whereDate('created_at', '>=', '2024-10-01')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);
        
        return view('tasks.index', [
            'open_worklist' => $open_worklist,
            'open_opdlist' => $open_opdlist,
            'open_abtclist' => $open_abtclist,
        ]);
    }

    public function grabTicket(Request $r) {
        $id = $r->ticket_id;

        $now = Carbon::now();

        if($r->type == 'opd') {
            $update = SyndromicRecords::findOrFail($id);

            //get latest grabbed ticket and compare time
            $latest_ticket = SyndromicRecords::where('ics_grabbedby', Auth::id())
            ->where('ics_ticketstatus', 'PENDING')
            ->whereNull('ics_finishedby')
            ->exists();

            if($latest_ticket) {
                return redirect()->route('task_index')
                ->with('msg', 'Error: Please finish your Pending OPD to iClinicSys Ticket first before getting a new one. You can view your Pending tasks on "My Tasks" Page.')
                ->with('msgtype', 'warning');
            }
            else {
                //Search latest grab
                $check_latest_grab = SyndromicRecords::where('ics_grabbedby', Auth::id())->orderBy('ics_grabbed_date', 'DESC')->first();
                if($check_latest_grab) {
                    $latest_grab_date = Carbon::parse($check_latest_grab->ics_grabbed_date)->addMinutes(5);

                    if($now->lt($latest_grab_date)) {
                        return redirect()->route('task_index')
                        ->with('msg', 'Error: Ang bilis mo naman mag-grab ng panibagong ticket. Try mo ulit maya-maya.')
                        ->with('msgtype', 'warning');
                    }
                }
            }

            $ticket_grabbedby = $update->ics_grabbedby;
            $ticket_status = $update->ics_ticketstatus;

        }
        else if($r->type == 'work') {
            $update = WorkTask::findOrFail($id);

            $ticket_grabbedby = $update->grabbed_by;
            $ticket_status = $update->status;
        }
        else if($r->type == 'abtc') {
            $update = AbtcBakunaRecords::findOrFail($id);

            $latest_ticket = AbtcBakunaRecords::where('ics_grabbedby', Auth::id())
            ->where('ics_ticketstatus', 'PENDING')
            ->whereNull('ics_finishedby')
            ->exists();

            if($latest_ticket) {
                return redirect()->route('task_index')
                ->with('msg', 'Error: Please finish your Pending ABTC to iClinicSys Ticket first before getting a new one. You can view your Pending tasks on "My Tasks" Page.')
                ->with('msgtype', 'warning');
            }

            $ticket_grabbedby = $update->ics_grabbedby;
            $ticket_status = $update->ics_ticketstatus;
        }
        else {
            return redirect()->route('task_index')
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        if(!is_null($ticket_grabbedby) && $ticket_grabbedby != Auth::id()) {
            return redirect()->route('task_index')
            ->with('msg', 'Error: Ticket was already grabbed by the other user. Please try another.')
            ->with('msgtype', 'warning');
        }
        else if($ticket_status == 'CLOSED' || $ticket_status == 'FINISHED') {
            return redirect()->route('task_index')
            ->with('msg', 'Error: Ticket was already been closed or finished. Please try another.')
            ->with('msgtype', 'warning');
        }
        else {
            if($r->type == 'opd' || $r->type == 'abtc') {
                $update->ics_grabbedby = Auth::id();
                $update->ics_grabbed_date = date('Y-m-d H:i:s');
                $update->ics_ticketstatus = 'PENDING';
            }
            else if($r->type == 'work') {
                $update->grabbed_by = Auth::id();
                $update->grabbed_date = date('Y-m-d H:i:s');
                $update->status = 'PENDING';
            }

            if($update->isDirty()) {
                $update->save();
            }
        }

        if($r->type == 'opd') {
            $msg = 'Successfully grabbed the ticket. Please transfer the OPD Patient details to iClinicSys before closing this ticket.';
            $msgtype = 'success';

            return redirect()->route('opdtask_view', $id)
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
        else if($r->type == 'work') {
            $msg = 'Successfully grabbed the work ticket. Please perform the task before closing the ticket.';
            $msgtype = 'success';

            return redirect()->route('worktask_view', $id)
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
        else if($r->type == 'abtc') {
            $msg = 'Successfully grabbed the ticket. Please transfer the ABTC Patient details to iClinicSys before closing this ticket.';
            $msgtype = 'success';

            return redirect()->route('abtctask_view', $id)
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
    }

    public function viewOpdTicket(SyndromicRecords $syndromicRecords) {
        if(is_null($syndromicRecords->ics_grabbedby)) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        if(!auth()->user()->isGlobalAdmin()) {
            if($syndromicRecords->ics_grabbedby != Auth::id()) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        return view('tasks.viewopdticket', [
            'd' => $syndromicRecords,
        ]);
    }

    public function viewWorkTicket(WorkTask $workTask) {
        if(is_null($workTask->grabbed_by)) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        if(!auth()->user()->isGlobalAdmin()) {
            if($workTask->grabbed_by != Auth::id()) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        return view('tasks.viewworkticket', [
            'd' => $workTask,
        ]);
    }

    public function viewAbtcTicket(AbtcBakunaRecords $abtcBakunaRecords) {
        if(is_null($abtcBakunaRecords->ics_grabbedby)) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        if(!auth()->user()->isGlobalAdmin()) {
            if($abtcBakunaRecords->ics_grabbedby != Auth::id()) {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        return view('tasks.viewabtcticket', [
            'd' => $abtcBakunaRecords,
        ]);
    }

    public function closeOpdTicket(SyndromicRecords $syndromicRecords, Request $r) {
        $syndromicRecords->update([
            'ics_finishedby' => Auth::id(),
            'ics_finished_date' => date('Y-m-d H:i:s'),
            'ics_ticketstatus' => 'FINISHED',
        ]);

        $msg = 'OPD Ticket #'.$syndromicRecords->id.' was marked as done successfully. Please proceed to other OPEN Tickets.';
        $msgtype = 'success';

        return redirect()->route('task_index')
        ->with('msg', $msg)
        ->with('msgtype', $msgtype);
    }

    public function closeWorkTicket(WorkTask $workTask, Request $r) {
        if($workTask->areYouTheOwner()) {
            $workTask->update([
                'finished_by' => Auth::id(),
                'finished_date' => date('Y-m-d H:i:s'),
                'remarks' => $r->remarks,
                'encodedcount' => $r->encodedcount ?: NULL,
                'status' => 'FINISHED',
            ]);

            $msg = 'Work Task ID #'.$workTask->id.' was marked as done successfully. Please proceed to other OPEN Tickets.';
            $msgtype = 'success';

            return redirect()->route('task_index')
            ->with('msg', $msg)
            ->with('msgtype', $msgtype);
        }
    }

    public function closeAbtcTicket(AbtcBakunaRecords $abtcBakunaRecords, Request $r) {
        $abtcBakunaRecords->update([
            'ics_finishedby' => Auth::id(),
            'ics_finished_date' => date('Y-m-d H:i:s'),
            'ics_ticketstatus' => 'FINISHED',
        ]);

        $msg = 'ABTC Ticket #'.$abtcBakunaRecords->id.' was marked as done successfully. Please proceed to other OPEN Tickets.';
        $msgtype = 'success';

        return redirect()->route('task_index')
        ->with('msg', $msg)
        ->with('msgtype', $msgtype);
    }

    public function moreOpdTask() {

    }

    public function moreCesuTask() {
        
    }

    public function moreAbtcTask() {
        
    }

    public function myTaskIndex() {
        $grabbed_opdlist = SyndromicRecords::where('facility_id', auth()->user()->itr_facility_id)
        ->where('ics_grabbedby', Auth::id())
        ->orderBy('ics_grabbed_date', 'DESC')
        ->paginate(10);

        $grabbed_worklist = WorkTask::where('grabbed_by', Auth::id())
        ->orderBy('grabbed_date', 'DESC')
        ->paginate(10);

        $grabbed_abtclist = AbtcBakunaRecords::where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->where('ics_grabbedby', Auth::id())
        ->orderBy('ics_grabbed_date', 'DESC')
        ->paginate(10);

        return view('tasks.mytask', [
            'grabbed_opdlist' => $grabbed_opdlist,
            'grabbed_worklist' => $grabbed_worklist,
            'grabbed_abtclist' => $grabbed_abtclist,
        ]);
    }

    public function cancelTicket($type, $id) {
        if($type == 'opd') {
            $update = SyndromicRecords::where('id', $id)
            ->where('ics_ticketstatus', 'PENDING')
            ->update([
                'ics_ticketstatus' => 'OPEN',
                'ics_grabbedby' => NULL,
                'ics_grabbed_date' => NULL,
            ]);
        }
        else if($type == 'abtc') {
            $update = AbtcBakunaRecords::where('id', $id)
            ->where('ics_ticketstatus', 'PENDING')
            ->update([
                'ics_ticketstatus' => 'OPEN',
                'ics_grabbedby' => NULL,
                'ics_grabbed_date' => NULL,
            ]);
        }
        else if($type == 'work') {
            $update = WorkTask::where('id', $id)
            ->where('status', 'PENDING')
            ->update([
                'status' => 'OPEN',
                'grabbed_by' => NULL,
                'grabbed_date' => NULL,
            ]);
        }
        else {
            return abort(401);
        }

        return redirect()->route('task_index')
        ->with('msg', mb_strtoupper($type).' Ticket was cancelled and returned to list of OPEN Tickets.')
        ->with('msgtype', 'success');
    }

    public function userDashboard($id) {
        $d = User::findOrFail($id);

        return view('tasks.userdashboard', [
            'd' => $d,
        ]);
    }

    public function viewUserMonthlyAr($user) {
        if(auth()->user()->isArChecker() || auth()->user()->isArApprover()) {
            $countwork_proceed = 1;

            $month = request()->input('month');
            $year = request()->input('year');

            $createDate = Carbon::createFromDate($year, $month, 1);

            //$month = $createDate->subMonth(1)->format('m');
            //$year = $createDate->subMonth(1)->format('Y');

            $ar = MonthlyAccomplishmentChecker::where('employee_id', $user)
            ->where('month', $createDate->format('m'))
            ->where('year', $createDate->format('Y'))
            ->first();
        }
        else {
            if($user == Auth::id()) {
                if(request()->input('month') && request()->input('year')) {
                    $month = request()->input('month');
                    $year = request()->input('year');

                    $createDate = Carbon::createFromDate($year, $month, 1);
                    
                    $ar = MonthlyAccomplishmentChecker::where('employee_id', Auth::id())
                    ->where('month', $createDate->format('m'))
                    ->where('year', $createDate->format('Y'))
                    ->first();

                    if($ar) {
                        $countwork_proceed = 1;
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Error: Your Monthly Accomplishment report is not yet checked by your Supervisor. Please try again later.')
                        ->with('msgtype', 'warning');
                    }
                }
                else {
                    $countwork_proceed = 0;
                }
            }
            else {
                return redirect()->back()
                ->with('msg', 'Error: You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }

        //Load Work Counters here
        if($countwork_proceed == 1) {
            $getUser = User::findOrFail($user);

            //Encoder Stats in Monthly Format
            $suspected_count = Forms::where('user_id', $getUser->id)
            ->where(function($q) use ($createDate) {
                $q->whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')]);
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->count();

            $confirmed_count = Forms::where(function ($q) use ($getUser) {
                $q->where('user_id', $getUser->id)
                ->orWhere('updated_by', $getUser->id);
            })
            ->whereBetween('morbidityMonth', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->where('caseClassification', 'Confirmed')
            ->count();

            $recovered_count = Forms::where(function ($q) use ($getUser) {
                $q->where(function ($r) use ($getUser) {
                    $r->where('user_id', $getUser->id)
                    ->where('updated_by', '!=', $getUser->id);
                })
                ->orWhere(function ($s) use ($getUser) {
                    $s->where('user_id', '!=', $getUser->id)
                    ->where('updated_by', $getUser->id);
                })
                ->orWhere(function ($t) use ($getUser) {
                    $t->where('user_id', $getUser->id)
                    ->where('updated_by', $getUser->id);
                });
            })
            ->whereBetween('morbidityMonth', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->count();
            
            $negative_count = Forms::where(function ($q) use ($getUser) {
                $q->where('user_id', $getUser->id)
                ->orWhere('updated_by', $getUser->id);
            })
            ->where(function ($q) use ($createDate) {
                $q->whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
                ->orWhereBetween('updated_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')]);
            })
            ->where('caseClassification', 'Non-COVID-19 Case')
            ->count();

            $covid_count_final = $suspected_count + $confirmed_count + $recovered_count + $negative_count;

            $abtc_count = AbtcBakunaRecords::where('d0_done_by', $getUser->id)
            ->whereBetween('d0_done_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtc_count_ff1 = AbtcBakunaRecords::where('d3_done_by', $getUser->id)
            ->whereBetween('d3_done_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtc_count_ff2 = AbtcBakunaRecords::where('d7_done_by', $getUser->id)
            ->whereBetween('d7_done_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtc_count_ff3 = AbtcBakunaRecords::where('d14_done_by', $getUser->id)
            ->whereBetween('d14_done_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtc_count_ff4 = AbtcBakunaRecords::where('d28_done_by', $getUser->id)
            ->whereBetween('d28_done_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtc_ffup_gtotal = $abtc_count_ff1 + $abtc_count_ff2 + $abtc_count_ff3 + $abtc_count_ff4;

            $vaxcert_count = VaxcertConcern::where('processed_by', $getUser->id)
            ->whereBetween('updated_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $opd_count = SyndromicRecords::where('created_by', $getUser->id)
            ->whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $lcr_livebirth = LiveBirth::whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->where('created_by', $getUser->id)
            ->count();
            
            $disease_list = PIDSRController::listDiseasesTables();

            //Add Laboratory data table for counting
            $disease_list = $disease_list + [
                'EdcsLaboratoryData',
            ];

            $edcs_count = 0;

            foreach($disease_list as $d) {
                $modelClass = "App\\Models\\$d";

                $model_count = $modelClass::where('created_by', $getUser->id)
                ->whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
                ->count();

                $edcs_count += $model_count;
            }

            /*
            $death_count = WorkTask::where('name', 'DAILY ENCODE OF DEATH CERTIFICATES TO FHSIS')
            ->where('finished_by', $getUser->id)
            ->whereBetween('finished_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->sum('encodedcount');
            */

            $death_count = DeathCertificate::whereBetween('created_at', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->where('created_by', $getUser->id)
            ->count();

            $opdtoics_count = SyndromicRecords::where('ics_finishedby', $getUser->id)
            ->whereBetween('ics_finished_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            $abtctoics_count = AbtcBakunaRecords::where('ics_finishedby', $getUser->id)
            ->whereBetween('ics_finished_date', [$createDate->startOfMonth()->format('Y-m-d'), $createDate->endOfMonth()->format('Y-m-d')])
            ->count();

            return view('tasks.monthly_userdashboard', [
                'countwork_proceed' => $countwork_proceed,

                'id' => $getUser->id,
                'name' => $getUser->name,
                'covid_count_final' => $covid_count_final,
                'abtc_count' => $abtc_count,
                'abtc_ffup_gtotal' => $abtc_ffup_gtotal,
                'vaxcert_count' => $vaxcert_count,
                'opd_count' => $opd_count,
                'lcr_livebirth' => $lcr_livebirth,
                'edcs_count' => $edcs_count,
                'death_count' => $death_count,
                'opdtoics_count' => $opdtoics_count,
                'abtctoics_count' => $abtctoics_count,

                'ar' => $ar,
                'year' => $year,
                'month' => $month,
            ]);
        }
        else {
            return view('tasks.monthly_userdashboard', [
                'name' => auth()->user()->name,
                'countwork_proceed' => $countwork_proceed,
            ]);
        }
    }

    public function approveMonthlyAr($user, $year, $month, Request $r) {
        if(auth()->user()->isArChecker() || auth()->user()->isArApprover()) {
            $createDate = Carbon::createFromDate($year, $month, 1);
            $u = User::findOrFail($user);

            $c = MonthlyAccomplishmentChecker::create([
                'employee_id' => $user,
                'year' => $year,
                'month' => $month,
                'remarks' => $r->remarks,

                'checked_by' => Auth::id(),
                'approved_by' => Auth::id(),
            ]);

            //Goto next CESU Staff na hindi pa na-check ang accomplishment
            $users = User::where('permission_list', 'LIKE', '%TASK_MEMBER%')
            ->where('id', '!=', $u->id)
            ->get();

            foreach($users as $uu) {
                $check_count = MonthlyAccomplishmentChecker::where('employee_id', $uu->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

                if(!$check_count) {
                    return redirect()->route('encoderstats_viewar', [
                        'id' => $uu->id,
                        'year' => $year,
                        'month' => $month,
                    ])
                    ->with('msg', 'Monthly Accomplishment of '.$u->name.' for '.$createDate->format('M Y').' has been successfully approved. Proceed to '.$uu->name.' below for approval.')
                    ->with('msgtype', 'success');
                }
            }

            return redirect()->route('encoder_stats_index')
            ->with('msg', 'Monthly Accomplishment of '.$u->name.' for '.$createDate->format('M Y').' has been successfully approved. All CESU Staff Accomplishments were checked and approved.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->with('msg', 'Error: You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
    }
}
