<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\WorkTask;
use Illuminate\Http\Request;
use App\Models\SyndromicRecords;
use App\Models\AbtcBakunaRecords;
use Illuminate\Support\Facades\Auth;

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
        ->whereDate('created_at', '2024-05-31')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        $open_abtclist = AbtcBakunaRecords::where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->whereNull('ics_grabbedby')
        ->whereDate('created_at', '2024-05-31')
        ->orderBy('created_at', 'ASC')
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
}
