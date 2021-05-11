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

        return view('linelist_createoni');
    }

    public function createlasalle() {
        return view('linelist_createlasalle');
    }

    public function printoni($id) {
        $details = LineListMasters::find($id);
        $list = LineListSubs::where('linelist_master_id', $id)->orderBy('specNo', 'asc')->get();

        
        $pdf = PDF::loadView('oni_pdf', ['details' => $details, 'list' => $list])->setPaper('legal', 'landscape');
        return $pdf->download('ONI_LL.pdf');
        

        //return view('oni_pdf', ['details' => $details, 'list' => $list]);
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

        return redirect()->action([LineListController::class, 'index'])->with('status', 'Linelist has been created successfully.')->with('statustype', 'success');
    }

    public function lasalleStore(Request $request) {
        $master = $request->user()->linelistmaster()->create([
            'type' => 2, //ONI = 1, LaSalle = 2
            'dru' => $request->dru,
            'laSallePhysician' => $request->laSallePhysician,
            'contactPerson' => $request->contactPerson,
            'email' => $request->email,
            'contactTelephone' => $request->contactTelephone,
            'contactMobile' => $request->contactMobile,
        ]);
    }

    public function ajaxGetLineList () {
        $query = Forms::where('testDateCollected1', date('Y-m-d'))->pluck('id')->toArray();

        $query = Records::whereIn('id', $query)->orderBy('lname', 'asc')->get();

        $sdata['data'] = $query;
        echo json_encode($sdata);
        exit;
    }
}
