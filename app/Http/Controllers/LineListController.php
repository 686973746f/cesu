<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use App\Models\Records;
use PDF;
use App\Models\LinelistSubs;
use Illuminate\Http\Request;
use App\Models\LinelistMasters;

class LineListController extends Controller
{
    public function index() {
        $list = LinelistMasters::all();

        return view('linelist_index', ['list' => $list]);
    }

    public function createoni() {
        $query = Forms::where('testDateCollected1', date('Y-m-d'))->orWhere('testDateCollected2', date('Y-m-d'))->pluck('records_id')->toArray();

        $query = Records::whereIn('id', $query)->orderBy('lname', 'asc')->get();

        return view('linelist_createoni', ['list' => $query]);
    }

    public function createlasalle() { 
        $query = Forms::where('testDateCollected1', date('Y-m-d'))->orWhere('testDateCollected2', date('Y-m-d'))->pluck('records_id')->toArray();

        $query = Records::whereIn('id', $query)->orderBy('lname', 'asc')->get();

        return view('linelist_createlasalle', ['list' => $query]);
    }

    public function printoni($id) {
        ini_set('max_execution_time', 600);
        
        $details = LineListMasters::find($id);
        $list = LineListSubs::where('linelist_master_id', $id)->orderBy('specNo', 'asc')->get();

        $pdf = PDF::loadView('oni_pdf', ['details' => $details, 'list' => $list])->setPaper('legal', 'landscape');
        return $pdf->download('ONI_LL.pdf');
    }

    public function printlasalle($id) {
        ini_set('max_execution_time', 600);

        $details = LineListMasters::find($id);
        $list = LineListSubs::where('linelist_master_id', $id)->orderBy('specNo', 'asc')->get();

        $pdf = PDF::loadView('lasalle_pdf', ['details' => $details, 'list' => $list])->setPaper('legal', 'landscape');
        return $pdf->download('LaSalle_LL.pdf');
        
        //return view('lasalle_pdf', ['details' => $details, 'list' => $list]);
    }

    public function oniStore(Request $request) {

        $master = $request->user()->linelistmaster()->create([
            'type' => 1, //ONI = 1, LaSalle = 2
            'dru' => $request->dru,
            'contactPerson' => $request->contactPerson,
            'contactMobile' => $request->contactMobile,
        ]);

        for($i=0;$i<count($request->user);$i++) {
            $query = LinelistSubs::create([
                'linelist_master_id' => $master->id,
                'specNo' => $i+1,
                'dateAndTimeCollected' => $request->dateCollected[$i]." ".$request->timeCollected[$i],
                'accessionNo' => $request->accessionNo[$i],
                'records_id' => $request->user[$i],
                'remarks' => $request->remarks[$i],
                'oniSpecType' => $request->oniSpecType[$i],
                'oniReferringHospital' => $request->oniReferringHospital[$i]
            ]);
        }

        $update = Forms::whereIn('records_id', $request->user)
        ->where(function ($query) use ($request) {
            $query->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })->update(['isPresentOnSwabDay' => 1]);

        $update1 = Forms::whereNotIn('records_id', $request->user)
        ->where(function ($query) use ($request) {
            $query->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })
        ->where('isPresentOnSwabDay', '!=', 1)
        ->update(['isPresentOnSwabDay' => 0]);

        return redirect()->action([LineListController::class, 'index'])->with('status', 'ONI Linelist has been created successfully.')->with('statustype', 'success');
    }

    public function lasalleStore(Request $request) {
        $master = $request->user()->linelistmaster()->create([
            'type' => 2, //ONI = 1, LaSalle = 2
            'dru' => $request->dru,
            'laSallePhysician' => $request->laSallePhysician,
            'laSalleDateAndTimeShipment' => date('Y-m-d H:i:s', strtotime($request->shipmentDate." ".$request->shipmentTime)),
            'contactPerson' => $request->contactPerson,
            'email' => $request->email,
            'contactTelephone' => $request->contactTelephone,
            'contactMobile' => $request->contactMobile,
            'laSallePreparedBy' => $request->laSallePreparedBy,
            'laSallePreparedByDate' => date('Y-m-d H:i:s', strtotime($request->laSallePreparedByDate." ".$request->laSallePreparedByTime))
        ]);

        for($i=0;$i<count($request->user);$i++) {
            $query = LinelistSubs::create([
                'linelist_master_id' => $master->id,
                'specNo' => $i+1,
                'records_id' => $request->user[$i],
                'dateAndTimeCollected' => $request->dateCollected[$i]." ".$request->timeCollected[$i],
                'remarks' => $request->remarks[$i],
            ]);
        }

        $update = Forms::whereIn('records_id', $request->user)
        ->where(function ($query) use ($request) {
            $query->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })->update(['isPresentOnSwabDay' => 1]);

        $update1 = Forms::whereNotIn('records_id', $request->user)
        ->where(function ($query) use ($request) {
            $query->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })
        ->where('isPresentOnSwabDay', '!=', 1)
        ->update(['isPresentOnSwabDay' => 0]);

        return redirect()->action([LineListController::class, 'index'])->with('status', 'LaSalle Linelist has been created successfully.')->with('statustype', 'success');
    }

    /*
    
    //Unused Ajax Woring Fetching Script
    
    public function ajaxGetLineList () {
        $query = Forms::where('testDateCollected1', date('Y-m-d'))->pluck('records_id')->toArray();

        $query = Records::whereIn('id', $query)->orderBy('lname', 'asc')->get();

        $sdata['data'] = $query;
        echo json_encode($sdata);
        exit;
    }
    */
}
