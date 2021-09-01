<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Forms;
use App\Models\Records;
use App\Imports\CifImport;
use App\Models\CifUploads;
use App\Exports\FormsExport;
use App\Imports\ExcelImport;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PragmaRX\Countries\Package\Countries;
use App\Http\Requests\FormValidationRequest;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->input('view')) {
            if(request()->input('view') == 1) {
                if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                    if(!is_null(auth()->user()->brgy_id)) {
                        $forms = Forms::with('user')
                        ->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })
                        ->where(function ($query) {
                            $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                            ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                        })
                        ->orderBy('testDateCollected1', 'desc')->orderBy('created_at', 'desc')->get();
                    }
                    else {
                        $forms = Forms::with('user')
                        ->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })
                        ->where(function ($query) {
                            $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                            ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                        })
                        ->orderBy('testDateCollected1', 'desc')->orderBy('created_at', 'desc')->get();
                    }
                }
                else {
                    $forms = Forms::where(function ($query) {
                        $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                        ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                    })->orderBy('created_at', 'desc')->get();
                }
            }
            else if(request()->input('view') == 2) {
                if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                    if(!is_null(auth()->user()->brgy_id)) {
                        $forms = Forms::with('user')
                        ->whereDate('expoDateLastCont', '<=', date('Y-m-d', strtotime("-5 Days")))
                        ->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })->orderBy('created_at', 'desc')->get();
                    }
                    else {
                        $forms = Forms::with('user')
                        ->whereDate('expoDateLastCont', '<=', date('Y-m-d', strtotime("-5 Days")))
                        ->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })->orderBy('created_at', 'desc')->get();
                    }
                }
                else {
                    $forms = Forms::whereDate('expoDateLastCont', '<=', date('Y-m-d', strtotime("-5 Days")))->orderBy('created_at', 'desc')->get();
                }
                
            }
            else if(request()->input('view') == 3) {
                if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                    if(!is_null(auth()->user()->brgy_id)) {
                        $forms = Forms::with('user')
                        ->where(function ($query) {
                            $query->where('isExported', 0)
                            ->orWhereNull('isExported');
                        })->where(function ($query) {
                            $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                            ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                        })->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })->orderBy('created_at', 'desc')->get();
                    }
                    else {
                        $forms = Forms::with('user')
                        ->where(function ($query) {
                            $query->where('isExported', 0)
                            ->orWhereNull('isExported');
                        })->where(function ($query) {
                            $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                            ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                        })->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })->orderBy('created_at', 'desc')->get();
                    }
                }
                else {
                    $forms = Forms::where(function ($query) {
                        $query->where('isExported', 0)
                        ->orWhereNull('isExported');
                    })->where(function ($query) {
                        $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                        ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                    })->orderBy('created_at', 'desc')->get();
                }
            }
        }
        else {
            if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                if(!is_null(auth()->user()->brgy_id)) {
                    $forms = Forms::with('user')
                    ->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })
                    ->whereHas('user', function ($query) {
                        $query->where('brgy_id', auth()->user()->brgy_id);
                    })
                    ->orderBy('created_at', 'desc')->get();
                }
                else {
                    $forms = Forms::with('user')
                    ->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })
                    ->whereHas('user', function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    })
                    ->orderBy('created_at', 'desc')->get();
                }
            }
            else {
                $forms = Forms::where('testDateCollected1', date('Y-m-d'))->orWhere('testDateCollected2', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
            }
        }

        if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
            if(!is_null(auth()->user()->brgy_id)) {
                if(request()->input('view') != null) {
                    $formsctr = Forms::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('brgy_id', auth()->user()->brgy_id);
                    })->where(function ($query) {
                        $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                        ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                    })->get();
                }
                else {
                    $formsctr = Forms::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('brgy_id', auth()->user()->brgy_id);
                    })->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })->get();
                }
            }
            else {
                if(request()->input('view') != null) {
                    $formsctr = Forms::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    })->where(function ($query) {
                        $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                        ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                    })->get();
                }
                else {
                    $formsctr = Forms::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    })->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })->get();
                }   
            }
        }
        else {
            if(request()->input('view') != null) {
                $formsctr = Forms::where(function ($query) {
                    $query->whereBetween('testDateCollected1', [request()->input('sdate'), request()->input('edate')])
                    ->orWhereBetween('testDateCollected2', [request()->input('sdate'), request()->input('edate')]);
                })->get();
            }
            else {
                $formsctr = Forms::where('testDateCollected1', date('Y-m-d'))
                ->orWhere('testDateCollected2', date('Y-m-d'))->get();
            }
        }

        $paswabctr = PaSwabDetails::where('status', 'pending')->count();

        return view('forms', ['forms' => $forms, 'formsctr' => $formsctr, 'paswabctr' => $paswabctr]);
    }

    public function ajaxList(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) != 0) {
            $search = $request->q;
            /*
            $data = Records::select("id","lname")->where(function ($query) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%$search%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%$search%");
            })->get();
            */
            //$data = Records::where('lname', 'LIKE', "%$search%")->get();

            if(auth()->user()->isCesuAccount()) {
                $data = Records::where(function ($query) use ($search) {
                    $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
                })->get();
            }
            else {
                if(auth()->user()->isBrgyAccount()) {
                    $data = Records::with('user')
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
                    })->whereHas('user', function($q) {
                        $q->where('brgy_id', auth()->user()->brgy_id);
                    })->get();
                }
                else if(auth()->user()->isCompanyAccount()) {
                    $data = Records::with('user')
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
                    })->whereHas('user', function($q) {
                        $q->where('company_id', auth()->user()->company_id);
                    })->get();
                }
            }

            //$data = Records::select("id","lname")->where('lname','LIKE',"%$search%")->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => $item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                ]);
            }
        }
        
        return response()->json($list);
    }

    public function importIndex() {
        return view('forms_import');
    }

    public function importInit(Request $request) {
        $request->validate([
            'thefile' => 'required'
        ]);
        
        Excel::import(new ExcelImport(), request()->file('thefile'));
    }

    public function soloExport($id) {
        return Excel::download(new FormsExport([$id]), 'CIF_'.date("m_d_Y").'.csv');
    }

    public function printAntigenLinelist() {
        ini_set('max_execution_time', 600);
        
        if(auth()->user()->isCesuAccount()) {
            $data = Forms::join('records', 'records_id', '=', 'records.id')
            ->where(function ($query) {
                $query->where('testDateCollected1', date('Y-m-d'))
                ->orWhere('testDateCollected2', date('Y-m-d'));
            })->where(function ($query) {
                $query->where('testType1', 'ANTIGEN')
                ->orWhere('testType2', 'ANTIGEN');
            })->orderBy('records.lname', 'ASC')->get();
        }
        else {
            if(auth()->user()->isBrgyAccount()) {
                $data = Forms::join('records', 'records_id', '=', 'records.id')
                ->where(function ($query) {
                    $query->where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'));
                })->where(function ($query) {
                    $query->where('testType1', 'ANTIGEN')
                    ->orWhere('testType2', 'ANTIGEN');
                })->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->orderBy('records.lname', 'ASC')->get();
            }
            else if(auth()->user()->isCompanyAccount()) {
                $data = Forms::join('records', 'records_id', '=', 'records.id')
                ->where(function ($query) {
                    $query->where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'));
                })->where(function ($query) {
                    $query->where('testType1', 'ANTIGEN')
                    ->orWhere('testType2', 'ANTIGEN');
                })->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->orderBy('records.lname', 'ASC')->get();
            }
        }

        $pdf = PDF::loadView('pdf_antigen_linelist',['data' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('Antigen_Linelist_'.date('m_d_Y').'.pdf');
    }

    public function printCIFList() {
        //ini_set('max_execution_time', 600);

        if(auth()->user()->isCesuAccount()) {
            $data = Forms::join('records', 'records_id', '=', 'records.id')
            ->where(function ($query) {
                $query->where('testDateCollected1', date('Y-m-d'))
                ->orWhere('testDateCollected2', date('Y-m-d'));
            })->orderBy('records.lname', 'ASC')->get();
        }
        else {
            if(auth()->user()->isBrgyAccount()) {
                $data = Forms::join('records', 'records_id', '=', 'records.id')
                ->where(function ($query) {
                    $query->where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'));
                })->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->orderBy('records.lname', 'ASC')->get();
            }
            else if(auth()->user()->isCompanyAccount()) {
                $data = Forms::join('records', 'records_id', '=', 'records.id')
                ->where(function ($query) {
                    $query->where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'));
                })->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->orderBy('records.lname', 'ASC')->get();
            }
        }

        return view('pdf_cif_list',['data' => $data]);

        /*
        $pdf = PDF::loadView()->setPaper('a4', 'landscape');
        return $pdf->download('CIFList_'.date('m_d_Y').'.pdf');
        */
    }

    public function printAntigen($id, $testType) {
        ini_set('max_execution_time', 600);

        $details = Forms::find($id);

        if(auth()->user()->isCesuAccount()) {
            if($details->testType1 == "ANTIGEN" || $details->testType2 == "ANTIGEN") {
                $pdf = PDF::loadView('pdf_antigen', ['details' => $details, 'testType' => $testType])->setPaper('a4', 'portrait');
                return $pdf->download('ANTIGEN_RESULT_'.$details->records->lname.'.pdf');
            }
            else {
                return redirect()->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgType', 'warning');
            }
        }
        else {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgType', 'warning');
        }
    }

    public function options(Request $request)
    {
        $list = $request->listToPrint;

        asort($list);
        
        if($request->submit == 'export') {
            $request->validate([
                'listToPrint' => 'required',
            ]);

            $models = Forms::whereIn('id', $list)
            ->update(['isExported'=>'1', 'exportedDate'=>NOW()]);
            
            return Excel::download(new FormsExport($request->listToPrint), 'CIF_'.date("m_d_Y").'.csv');
        }
        else if($request->submit == 'resched') {
            $request->validate([
                'listToPrint' => 'required',
                'reschedDate' => 'required|date',
            ]);

            $models = Forms::whereIn('id', $list)->get();
            foreach($models as $item) {
                if(!is_null($item->testDateCollected2)) {
                    $query = Forms::where('id', $item->id)->update([
                        'testDateCollected2' => $request->reschedDate,
                        'isExported' => '0'
                    ]);
                }
                else {
                    $query = Forms::where('id', $item->id)->update([
                        'testDateCollected1' => $request->reschedDate,
                        'isExported' => '0'
                    ]);
                }
            }

            if($request->changeToMorning) {
                foreach($models as $item) {
                    if(!is_null($item->records->philhealth)) {
                        if(!is_null($item->testDateCollected2)) {
                            if($item->testType2 == "OPS" || $item->testType2 == "NPS" || $item->testType2 == "OPS AND NPS") {
                                $trigger = 0;
                                $addMinutes = 0;

                                while ($trigger != 1) {
                                    $oniStartTime = date('H:i:s', strtotime('09:30:00 + '. $addMinutes .' minutes'));

                                    $query = Forms::with('records')
                                    ->where('testDateCollected2', $item->testDateCollected2)
                                    ->whereIn('testType2', ['OPS', 'NPS', 'OPS AND NPS'])
                                    ->whereHas('records', function ($q) {
                                        $q->whereNotNull('philhealth');
                                    })
                                    ->where('oniTimeCollected2', $oniStartTime)->get();

                                    if($query->count()) {
                                        if($query->count() < 5) {
                                            $oniTimeFinal2 = $oniStartTime;
                                            $trigger = 1;
                                        }
                                        else {
                                            $addMinutes = $addMinutes + 5;
                                        }
                                    }
                                    else {
                                        $oniTimeFinal2 = $oniStartTime;
                                        $trigger = 1;
                                    }
                                }
                            }

                            $updateTime = Forms::where('id', $item->id)->update([
                                'oniTimeCollected2' => $oniTimeFinal2,
                            ]);
                        }
                        else {
                            if($item->testType1 == "OPS" || $item->testType1 == "NPS" || $item->testType1 == "OPS AND NPS") {
                                $trigger = 0;
                                $addMinutes = 0;
        
                                while ($trigger != 1) {
                                    $oniStartTime = date('H:i:s', strtotime('09:30:00 + '. $addMinutes .' minutes'));
        
                                    $query = Forms::with('records')
                                    ->where('testDateCollected1', $item->testDateCollected1)
                                    ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                                    ->whereHas('records', function ($q) {
                                        $q->whereNotNull('philhealth');
                                    })
                                    ->where('oniTimeCollected1', $oniStartTime)->get();
        
                                    if($query->count()) {
                                        if($query->count() < 5) {
                                            $oniTimeFinal = $oniStartTime;
                                            $trigger = 1;
                                        }
                                        else {
                                            $addMinutes = $addMinutes + 5;
                                        }
                                    }
                                    else {
                                        $oniTimeFinal = $oniStartTime;
                                        $trigger = 1;
                                    }
                                }
    
                                $updateTime = Forms::where('id', $item->id)->update([
                                    'oniTimeCollected1' => $oniTimeFinal,
                                ]);
                            }
                        }
                    }
                }
            }

            return redirect()->action([FormsController::class, 'index'])->with('status', 'Re-sched successful.')->with('statustype', 'success');
        }
        else if($request->submit == 'changetype') {
            $request->validate([
                'listToPrint' => 'required',
                'changeType' => 'required|in:OPS,NPS,OPS AND NPS,ANTIGEN,ANTIBODY,OTHERS',
                'reasonRemarks' => ($request->changeType == "ANTIGEN" || $request->changeType == "OTHERS") ? 'required' : 'nullable',
                'antigenKit' => ($request->changeType == "ANTIGEN") ? 'required' : 'nullable',
            ]);

            if ($request->changeType == "ANTIGEN" || $request->changeType == "OTHERS") {
                if($request->changeType == "ANTIGEN") {
                    $antigenReason = mb_strtoupper($request->reasonRemarks);
                    $antigenKit = mb_strtoupper($request->antigenKit);
                    $otherReason = null;
                }
                else {
                    $antigenReason = null;
                    $antigenKit = null;
                    $otherReason = mb_strtoupper($request->reasonRemarks);
                }
            }
            else {
                $antigenReason = null;
                $otherReason = null;
                $antigenKit = null;
            }

            $models = Forms::whereIn('id', $list)->get();
            foreach($models as $item) {
                if(!is_null($item->testDateCollected2)) {
                    $query = Forms::where('id', $item->id)->update([
                        'testType2' => $request->changeType,
                        'isExported' => '0',
                        'testTypeAntigenRemarks2' => $antigenReason,
                        'antigenKit2' => $antigenKit,
                        'testTypeOtherRemarks2' => $otherReason
                    ]);
                }
                else {
                    $query = Forms::where('id', $item->id)->update([
                        'testType1' => $request->changeType,
                        'isExported' => '0',
                        'testTypeAntigenRemarks1' => $antigenReason,
                        'antigenKit1' => $antigenKit,
                        'testTypeOtherRemarks1' => $otherReason
                    ]);
                }
            }

            return redirect()->action([FormsController::class, 'index'])->with('status', 'Change Test Type was successful.')->with('statustype', 'success');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function new($id) {
        if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
            if(!is_null(auth()->user()->brgy_id)) {
                $check = Records::with('user')
                ->where('id', $id)
                ->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->first();
            }
            else {
                $check = Records::with('user')
                ->where('id', $id)
                ->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->first();
            }
        }
        else {
            $check = Records::findOrFail($id);
        }

        if($check) {
            if(Forms::where('records_id', $id)->exists()) {
                //existing na
                $ex_id = Forms::where('records_id', $id)->first();
                return redirect()->back()
                ->with('modalmsg', 'CIF Record already exists for ')
                ->with('eName', $check->lname.", ".$check->fname." ".$check->mname)
                ->with('philhealth', (!is_null($check->philhealth)) ? $check->philhealth : 'N/A')
                ->with('exist_id', $ex_id->id)
                ->with('attended', ($ex_id->isPresentOnSwabDay == 1) ? "YES" : "NO")
                ->with('recordno', (!is_null($ex_id->testType2)) ? 2 : 1)
                ->with('eType', (!is_null($ex_id->testType2)) ? $ex_id->testType2 : $ex_id->testType1)
                ->with('eResult', (!is_null($ex_id->testType2)) ? $ex_id->testResult2 : $ex_id->testResult1)
                ->with('encodedBy', $ex_id->user->name)
                ->with('editedBy', (!is_null($ex_id->updated_by)) ? $ex_id->getEditedBy() : NULL)
                ->with('encodedDate', date('m/d/Y h:i A (D)', strtotime($ex_id->created_at)))
                ->with('editedDate', (!is_null($ex_id->updated_by)) ? date('m/d/Y h:i A (D)', strtotime($ex_id->updated_at)) : NULL)
                ->with('dateCollected', (!is_null($ex_id->testDateCollected2)) ? date('m/d/Y (D)', strtotime($ex_id->testDateCollected2)) : date('m/d/Y (D)', strtotime($ex_id->testDateCollected1)));
            }
            else {
                $interviewers = Interviewers::orderBy('lname', 'asc')->get();
                
                $countries = new Countries();
                $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
                $all = $countries->all()->pluck('name.common')->toArray();
                return view('formscreate', ['countries' => $all, 'records' => $check, 'interviewers' => $interviewers, 'id' => $id]);
            }
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(FormValidationRequest $request, $id)
    {
        $rec = Records::findOrFail($id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        $request->validated();

        if(Forms::where('records_id', $rec->id)->exists()) {
            return redirect()->action([FormsController::class, 'index'])
            ->with('status', 'Double Entry Detected! Error: CIF Record for '.$rec->lname.", ".$rec->fname." ".$rec->mname." already exists at ".date('m/d/Y'))
            ->with('statustype', 'danger');
        }
        else {
            if(!is_null($rec->philhealth)) {
                if($request->filled('oniTimeCollected1')) {
                    $oniTimeFinal = $request->oniTimeCollected1;
                }
                else {
                    if($request->testType1 == "OPS" || $request->testType1 == "NPS" || $request->testType1 == "OPS AND NPS") {
                        $trigger = 0;
                        $addMinutes = 0;

                        while ($trigger != 1) {
                            $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                            $query = Forms::with('records')
                            ->where('testDateCollected1', $request->testDateCollected1)
                            ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                            ->whereHas('records', function ($q) {
                                $q->whereNotNull('philhealth');
                            })
                            ->where('oniTimeCollected1', $oniStartTime)->get();

                            if($query->count()) {
                                if($query->count() < 5) {
                                    $oniTimeFinal = $oniStartTime;
                                    $trigger = 1;
                                }
                                else {
                                    $addMinutes = $addMinutes + 5;
                                }
                            }
                            else {
                                $oniTimeFinal = $oniStartTime;
                                $trigger = 1;
                            }
                        }
                    }
                    else {
                        $oniTimeFinal = $request->oniTimeCollected1;
                    }
                }

                if($request->filled('testType2')) {
                    if($request->filled('oniTimeCollected2')) {
                        $oniTimeFinal2 = $request->oniTimeCollected2;
                    }
                    else {
                        if($request->testType2 == "OPS" || $request->testType2 == "NPS" || $request->testType2 == "OPS AND NPS") {
                            $trigger = 0;
                            $addMinutes = 0;

                            while ($trigger != 1) {
                                $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                                $query = Forms::with('records')
                                ->where('testDateCollected2', $request->testDateCollected2)
                                ->whereIn('testType2', ['OPS', 'NPS', 'OPS AND NPS'])
                                ->whereHas('records', function ($q) {
                                    $q->whereNotNull('philhealth');
                                })
                                ->where('oniTimeCollected2', $oniStartTime)->get();

                                if($query->count()) {
                                    if($query->count() < 5) {
                                        $oniTimeFinal2 = $oniStartTime;
                                        $trigger = 1;
                                    }
                                    else {
                                        $addMinutes = $addMinutes + 5;
                                    }
                                }
                                else {
                                    $oniTimeFinal2 = $oniStartTime;
                                    $trigger = 1;
                                }
                            }
                        }
                        else {
                            $oniTimeFinal2 = $request->oniTimeCollected2;
                        }
                    }
                }
                else {
                    $oniTimeFinal2 = $request->oniTimeCollected2;
                }
            }
            else {
                $oniTimeFinal = ($request->filled('oniTimeCollected1')) ? $request->oniTimeCollected1 : NULL;
                $oniTimeFinal2 = ($request->filled('oniTimeCollected2')) ? $request->oniTimeCollected2 : NULL;
            }

            //For late encoding ng CIF, automatic ilalagay sa tamang Case Classification base sa Resulta ng Test Type
            if($request->testResult1 != "PENDING") {
                if($request->testResult1 == "POSITIVE") {
                    $caseClassi = 'Confirmed';
                }
                else if($request->testResult1 == "NEGATIVE") {
                    $caseClassi = 'Non-COVID-19 Case';
                }
                else {
                    //Equivocal and others will be placed here
                    $caseClassi = $request->caseClassification;
                }
                $attended = 1;
            }
            else {
                $caseClassi = $request->caseClassification;
                $attended = null;
            }

            if($request->filled('testType2')) {
                if($request->testResult2 != "PENDING") {
                    if($request->testResult2 == "POSITIVE") {
                        $caseClassi = 'Confirmed';
                    }
                    else if($request->testResult2 == "NEGATIVE") {
                        $caseClassi = 'Non-COVID-19 Case';
                    }
                    else {
                        //Equivocal and others will be placed here
                        $caseClassi = $request->caseClassification;
                    }
                    $attended = 1;
                }
                else {
                    $caseClassi = $request->caseClassification;
                    $attended = null;
                }
            }

            $request->user()->form()->create([
                'status' => 'approved',
                'isPresentOnSwabDay' => $attended,
                'records_id' => $id,
                'drunit' => mb_strtoupper($request->drunit),
                'drregion' => mb_strtoupper($request->drregion),
                'drprovince' => mb_strtoupper($request->drprovince),
                'interviewerName' => $request->interviewerName,
                'interviewerMobile' => $request->interviewerMobile,
                'interviewDate' => $request->interviewDate,
                'informantName' => $request->informantName,
                'informantRelationship' => $request->informantRelationship,
                'informantMobile' => $request->informantMobile,
                'existingCaseList' => implode(",", $request->existingCaseList),
                'ecOthersRemarks' => $request->ecOthersRemarks,
                'pType' => $request->pType,
                'isForHospitalization' => $request->isForHospitalization,
                'testingCat' => implode(",",$request->testingCat),
                'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                'dateOfFirstConsult' => $request->dateOfFirstConsult,
                'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,

                'vaccinationDate1' => (!is_null($request->howManyDoseVaccine)) ? $request->vaccinationDate1 : NULL,
                'haveAdverseEvents1' => (!is_null($request->howManyDoseVaccine)) ? $request->haveAdverseEvents1 : NULL,
                'vaccinationName1' => (!is_null($request->howManyDoseVaccine)) ? $request->vaccineName : NULL,
                'vaccinationNoOfDose1' => (!is_null($request->howManyDoseVaccine)) ? 1 : NULL,
                'vaccinationFacility1' => (!is_null($request->howManyDoseVaccine)) ? mb_strtoupper($request->vaccinationFacility1) : NULL,
                'vaccinationRegion1' => (!is_null($request->howManyDoseVaccine)) ? mb_strtoupper($request->vaccinationRegion1) : NULL,

                'vaccinationDate2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->vaccinationDate2 : NULL,
                'haveAdverseEvents2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents2 : NULL,
                'vaccinationName2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
                'vaccinationNoOfDose2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? 2 : NULL,
                'vaccinationFacility2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility2) : NULL,
                'vaccinationRegion2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion2) : NULL,
                
                'dispoType' => $request->dispositionType,
                'dispoName' => $request->dispositionName,
                'dispoDate' => $request->dispositionDate,
                'healthStatus' => $request->healthStatus,
                'caseClassification' => $caseClassi,
                'isHealthCareWorker' => $request->isHealthCareWorker,
                'healthCareCompanyName' => $request->healthCareCompanyName,
                'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
                'isOFW' => $request->isOFW,
                'OFWCountyOfOrigin' => ($request->isOFW == 1) ? $request->OFWCountyOfOrigin : NULL,
                'OFWPassportNo' => ($request->isOFW == 1) ? $request->OFWPassportNo : NULL,
                'ofwType' => ($request->isOFW == 1) ? $request->ofwType : NULL,
                'isFNT' => $request->isFNT,
                'FNTCountryOfOrigin' => ($request->isFNT == 1) ? $request->FNTCountryOfOrigin : NULL,
                'FNTPassportNo' => ($request->isFNT == 1) ? $request->FNTPassportNo : NULL,
                'lsiType' => $request->lsiType,
                'isLSI' => $request->isLSI,
                'LSICity' => $request->LSICity,
                'LSIProvince' => $request->LSIProvince,
                'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
                'institutionType' => $request->institutionType,
                'institutionName' => $request->institutionName,
                'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
                'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                'SASFeverDeg' => $request->SASFeverDeg,
                'SASOtherRemarks' => $request->SASOtherRemarks,
                'COMO' => implode(",", $request->comCheck),
                'COMOOtherRemarks' => $request->COMOOtherRemarks,
                'PregnantLMP' => $request->PregnantLMP,
                'PregnantHighRisk' => $hrp,
                'diagWithSARI' => $request->diagWithSARI,
                'imagingDoneDate' => $request->imagingDoneDate,
                'imagingDone' => $request->imagingDone,
                'imagingResult' => $request->imagingResult,
                'imagingOtherFindings' => $request->imagingOtherFindings,
    
                'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
                'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
                'testedPositiveLab' => $request->testedPositiveLab,
                'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
    
                'testDateCollected1' => $request->testDateCollected1,
                'oniTimeCollected1' => $oniTimeFinal,
                'testDateReleased1' => $request->testDateReleased1,
                'testLaboratory1' => $request->testLaboratory1,
                'testType1' => $request->testType1,
                'testTypeAntigenRemarks1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'antigenKit1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'testResult1' => $request->testResult1,
                'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
    
                'testDateCollected2' => $request->testDateCollected2,
                'oniTimeCollected2' => $oniTimeFinal2,
                'testDateReleased2' => $request->testDateReleased2,
                'testLaboratory2' => $request->testLaboratory2,
                'testType2' => (!is_null($request->testType2)) ? $request->testType2 : NULL,
                'testTypeAntigenRemarks2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                'antigenKit2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->antigenKit2) : NULL,
                'testTypeOtherRemarks2' => ($request->testType2 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                'testResult2' => (!is_null($request->testType2)) ? $request->testResult2 : NULL,
                'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
    
                'outcomeCondition' => $request->outcomeCondition,
                'outcomeRecovDate' => $request->outcomeRecovDate,
                'outcomeDeathDate' => $request->outcomeDeathDate,
                'deathImmeCause' => $request->deathImmeCause,
                'deathAnteCause' => $request->deathAnteCause,
                'deathUndeCause' => $request->deathUndeCause,
                'contriCondi' => $request->contriCondi,
    
                'expoitem1' => $request->expoitem1,
                'expoDateLastCont' => $request->expoDateLastCont,
    
                'expoitem2' => $request->expoitem2,
                'intCountry' => $request->intCountry,
                'intDateFrom' => $request->intDateFrom,
                'intDateTo' => $request->intDateTo,
                'intWithOngoingCovid' => ($request->expoitem2 == 2) ? $request->intWithOngoingCovid : 'N/A',
                'intVessel' => $request->intVessel,
                'intVesselNo' => $request->intVesselNo,
                'intDateDepart' => $request->intDateDepart,
                'intDateArrive' => $request->intDateArrive,
    
                'placevisited' => (!is_null($request->placevisited)) ? implode(",", $request->placevisited) : NULL,
    
                'locName1' => $request->locName1,
                'locAddress1' => $request->locAddress1,
                'locDateFrom1' => $request->locDateFrom1,
                'locDateTo1' => $request->locDateTo1,
                'locWithOngoingCovid1' => (!is_null($request->placevisited) && in_array('Health Facility', $request->placevisited)) ? $request->locWithOngoingCovid1 : 'N/A', 
    
                'locName2' => $request->locName2,
                'locAddress2' => $request->locAddress2,
                'locDateFrom2' => $request->locDateFrom2,
                'locDateTo2' => $request->locDateTo2,
                'locWithOngoingCovid2' => (!is_null($request->placevisited) && in_array('Closed Settings', $request->placevisited)) ? $request->locWithOngoingCovid2 : 'N/A',
                
                'locName3' => $request->locName3,
                'locAddress3' => $request->locAddress3,
                'locDateFrom3' => $request->locDateFrom3,
                'locDateTo3' => $request->locDateTo3,
                'locWithOngoingCovid3' => (!is_null($request->placevisited) && in_array('School', $request->placevisited)) ? $request->locWithOngoingCovid3 : 'N/A',
                
                'locName4' => $request->locName4,
                'locAddress4' => $request->locAddress4,
                'locDateFrom4' => $request->locDateFrom4,
                'locDateTo4' => $request->locDateTo4,
                'locWithOngoingCovid4' => (!is_null($request->placevisited) && in_array('Workplace', $request->placevisited)) ? $request->locWithOngoingCovid4 : 'N/A',
    
                'locName5' => $request->locName5,
                'locAddress5' => $request->locAddress5,
                'locDateFrom5' => $request->locDateFrom5,
                'locDateTo5' => $request->locDateTo5,
                'locWithOngoingCovid5' => (!is_null($request->placevisited) && in_array('Market', $request->placevisited)) ? $request->locWithOngoingCovid5 : 'N/A',
    
                'locName6' => $request->locName6,
                'locAddress6' => $request->locAddress6,
                'locDateFrom6' => $request->locDateFrom6,
                'locDateTo6' => $request->locDateTo6,
                'locWithOngoingCovid6' => (!is_null($request->placevisited) && in_array('Social Gathering', $request->placevisited)) ? $request->locWithOngoingCovid6 : 'N/A',
    
                'locName7' => $request->locName7,
                'locAddress7' => $request->locAddress7,
                'locDateFrom7' => $request->locDateFrom7,
                'locDateTo7' => $request->locDateTo7,
                'locWithOngoingCovid7' => (!is_null($request->placevisited) && in_array('Others', $request->placevisited)) ? $request->locWithOngoingCovid7 : 'N/A',
    
                'localVessel1' => $request->localVessel1,
                'localVesselNo1' => $request->localVesselNo1,
                'localOrigin1' => $request->localOrigin1,
                'localDateDepart1' => $request->localDateDepart1,
                'localDest1' => $request->localDest1,
                'localDateArrive1' => $request->localDateArrive1,
    
                'localVessel2' => $request->localVessel2,
                'localVesselNo2' => $request->localVesselNo2,
                'localOrigin2' => $request->localOrigin2,
                'localDateDepart2' => $request->localDateDepart2,
                'localDest2' => $request->localDest2,
                'localDateArrive2' => $request->localDateArrive2,
    
                'contact1Name' => ($request->filled('contact1Name')) ? mb_strtoupper($request->contact1Name) : NULL,
                'contact1No' => $request->contact1No,
                'contact2Name' => ($request->filled('contact2Name')) ? mb_strtoupper($request->contact2Name) : NULL,
                'contact2No' => $request->contact2No,
                'contact3Name' => ($request->filled('contact3Name')) ? mb_strtoupper($request->contact3Name) : NULL,
                'contact3No' => $request->contact3No,
                'contact4Name' => ($request->filled('contact4Name')) ? mb_strtoupper($request->contact4Name) : NULL,
                'contact4No' => $request->contact4No,

                'remarks' => ($request->filled('remarks')) ? mb_strtoupper($request->remarks) : NULL,
            ]);
            
            return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF of Patient was created successfully.')->with('statustype', 'success');
        }
    }

    public function upload(Request $request, $id) {
        $request->validate([
            'file_type' => 'required',
            'filepath' => 'required|mimes:jpg,png,jpeg,pdf|max:5048',
            'remarks' => 'nullable',
        ]);

        $newFileName = time() . ' - ' . $request->filepath->getClientOriginalName();

        $upload = $request->filepath->move(public_path('assets/cif_docs'), $newFileName);
    
        $request->user()->cifupload()->create([
            'forms_id' => $id,
            'file_type' => $request->file_type,
            'filepath' => $newFileName,
            'remarks' => ($request->filled('remarks')) ? mb_strtoupper($request->remarks) : NULL,
        ]);

        return redirect()->back()
        ->with('msg', 'Document has been uploaded successfully.')
        ->with('msgType', 'success');
    }

    public function downloadDocs($id) {
        $doc = CifUploads::find($id);

        return response()->download(public_path('assets/cif_docs')."/".$doc->filepath);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
            if(!is_null(auth()->user()->brgy_id)) {
                $records = Forms::with('user')
                ->where('id', $id)
                ->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->first();
            }
            else {
                $records = Forms::with('user')
                ->where('id', $id)
                ->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->first();
            }
        }
        else {
            $records = Forms::findOrFail($id);
        }

        if($records) {
            $interviewers = Interviewers::orderBy('lname', 'asc')->get();

            $docs = CifUploads::where('forms_id', $id)->get();

            $countries = new Countries();
            $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
            $all = $countries->all()->pluck('name.common')->toArray();

            $oldRecords = Forms::where('records_id', $records->records_id)->onlyTrashed()->get();

            //Vaccination Details
            if(!is_null($records->vaccinationDate2) || !is_null($records->vaccinationDate1)) {
                if(!is_null($records->vaccinationDate2)) {
                    $vaccineDose = 2;
                }
                else {
                    $vaccineDose = 1;
                }
            }
            else {
                $vaccineDose = NULL;
            }

            return view('formsedit', [
                'countries' => $all,
                'records' => $records,
                'interviewers' => $interviewers,
                'docs' => $docs,
                'oldRecords' => $oldRecords,
                'vaccineDose' => $vaccineDose,
            ]);
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormValidationRequest $request, $id)
    {
        $rec = Forms::findOrFail($id);
        
        $oldAttendance = $rec->isPresentOnSwabDay;
        $olddate = $rec->testDateCollected1;
        $oldTestType1 = $rec->testType1;
        $oldTestType2 = $rec->testType2;
        $currentPhilhealth = $rec->records->philhealth;

        $rec = Records::findOrFail($rec->records->id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        if($request->testResult1 != "PENDING") {
            if($request->testResult1 == "POSITIVE") {
                $caseClassi = 'Confirmed';
            }
            else if($request->testResult1 == "NEGATIVE") {
                if($request->pType == 'CLOSE CONTACT') {
                    $caseClassi = 'Suspect';
                }
                else {
                    $caseClassi = 'Non-COVID-19 Case';
                }
            }
            else {
                $caseClassi = $request->caseClassification;
            }

            $attended = 1;
        }
        else {
            $caseClassi = $request->caseClassification;

            if($request->testType1 == "OPS" || $request->testType1 == "NPS" || $request->testType1 == "OPS AND NPS") {
                if(!is_null($oldAttendance)) {
                    $attended = $oldAttendance;
                }
                else {
                    $attended = null;
                }
            }
            else {
                $attended = null;
            }
        }

        if($request->filled('testType2')) {
            if($request->testResult2 != "PENDING") {
                if($request->testResult2 == "POSITIVE") {
                    $caseClassi = 'Confirmed';
                }
                else if($request->testResult2 == "NEGATIVE") {
                    if($request->pType == 'CLOSE CONTACT') {
                        $caseClassi = 'Suspect';
                    }
                    else {
                        $caseClassi = 'Non-COVID-19 Case';
                    }
                }
                else {
                    //Equivocal and others will be placed here
                    $caseClassi = $request->caseClassification;
                }
                $attended = 1;
            }
            else {
                $caseClassi = $request->caseClassification;

                if($request->testType2 == "OPS" || $request->testType2 == "NPS" || $request->testType2 == "OPS AND NPS") {
                    if(!is_null($oldAttendance)) {
                        $attended = $oldAttendance;
                    }
                    else {
                        $attended = null;
                    }
                }
                else {
                    $attended = null;
                }
            }
        }

        $request->validated();

        if($olddate == $request->testDateCollected1) {
            $proceed = 1;
        }
        else {
            if(Forms::where([
                ['records_id', $rec->id],
                ['testDateCollected1', $request->testDateCollected1]
            ])->exists()) {
                $proceed = 0;
            }
            else {
                $proceed = 1;
            }
        }

        if($request->testType1 == "OPS" || $request->testType1 == "NPS" || $request->testType1 == "OPS AND NPS") {
            if(!is_null($currentPhilhealth)) {
                if($request->filled('oniTimeCollected1')) {
                    $oniTimeFinal = $request->oniTimeCollected1;
                }
                else {
                    $trigger = 0;
                    $addMinutes = 0;

                    while ($trigger != 1) {
                        $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                        $query = Forms::with('records')
                        ->where('testDateCollected1', $request->testDateCollected1)
                        ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                        ->whereHas('records', function ($q) {
                            $q->whereNotNull('philhealth');
                        })
                        ->where('oniTimeCollected1', $oniStartTime)->get();

                        if($query->count()) {
                            if($query->count() < 5) {
                                $oniTimeFinal = $oniStartTime;
                                $trigger = 1;
                            }
                            else {
                                $addMinutes = $addMinutes + 5;
                            }
                        }
                        else {
                            $oniTimeFinal = $oniStartTime;
                            $trigger = 1;
                        }
                    }
                }
            }
            else {
                $oniTimeFinal = $request->oniTimeCollected1;
            }
        }
        else {
            $oniTimeFinal = $request->oniTimeCollected1;
        }
        
        if($request->testType2 == "OPS" || $request->testType2 == "NPS" || $request->testType2 == "OPS AND NPS") {
            if(!is_null($currentPhilhealth)) {
                if($request->filled('oniTimeCollected2')) {
                    $oniTimeFinal2 = $request->oniTimeCollected2;
                }
                else {
                    $trigger = 0;
                    $addMinutes = 0;

                    while ($trigger != 1) {
                        $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                        $query = Forms::with('records')
                        ->where('testDateCollected2', $request->testDateCollected2)
                        ->whereIn('testType2', ['OPS', 'NPS', 'OPS AND NPS'])
                        ->whereHas('records', function ($q) {
                            $q->whereNotNull('philhealth');
                        })
                        ->where('oniTimeCollected2', $oniStartTime)->get();

                        if($query->count()) {
                            if($query->count() < 5) {
                                $oniTimeFinal2 = $oniStartTime;
                                $trigger = 1;
                            }
                            else {
                                $addMinutes = $addMinutes + 5;
                            }
                        }
                        else {
                            $oniTimeFinal2 = $oniStartTime;
                            $trigger = 1;
                        }
                    }
                }
            }
            else {
                $oniTimeFinal2 = $request->oniTimeCollected2;
            }
        }
        else {
            $oniTimeFinal2 = $request->oniTimeCollected2;
        }

        if($proceed == 1) {
            $form = Forms::where('id', $id)->update([
                'status' => 'approved',
                'isExported' => '0',
                'exportedDate' => null,
                'updated_by' => auth()->user()->id,
                'isPresentOnSwabDay' => $attended,
                'drunit' => mb_strtoupper($request->drunit),
                'drregion' => mb_strtoupper($request->drregion),
                'drprovince' => mb_strtoupper($request->drprovince),
                'interviewerName' => $request->interviewerName,
                'interviewerMobile' => $request->interviewerMobile,
                'interviewDate' => $request->interviewDate,
                'informantName' => $request->informantName,
                'informantRelationship' => $request->informantRelationship,
                'informantMobile' => $request->informantMobile,
                'existingCaseList' => implode(",", $request->existingCaseList),
                'ecOthersRemarks' => $request->ecOthersRemarks,
                'pType' => $request->pType,
                'isForHospitalization' => $request->isForHospitalization,
                'testingCat' => implode(",",$request->testingCat),
                'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                'dateOfFirstConsult' => $request->dateOfFirstConsult,
                'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,

                'vaccinationDate1' => (!is_null($request->howManyDoseVaccine)) ? $request->vaccinationDate1 : NULL,
                'haveAdverseEvents1' => (!is_null($request->howManyDoseVaccine)) ? $request->haveAdverseEvents1 : NULL,
                'vaccinationName1' => (!is_null($request->howManyDoseVaccine)) ? $request->vaccineName : NULL,
                'vaccinationNoOfDose1' => (!is_null($request->howManyDoseVaccine)) ? 1 : NULL,
                'vaccinationFacility1' => (!is_null($request->howManyDoseVaccine)) ? mb_strtoupper($request->vaccinationFacility1) : NULL,
                'vaccinationRegion1' => (!is_null($request->howManyDoseVaccine)) ? mb_strtoupper($request->vaccinationRegion1) : NULL,

                'vaccinationDate2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->vaccinationDate2 : NULL,
                'haveAdverseEvents2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents2 : NULL,
                'vaccinationName2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
                'vaccinationNoOfDose2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? 2 : NULL,
                'vaccinationFacility2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility2) : NULL,
                'vaccinationRegion2' => (!is_null($request->howManyDoseVaccine) && $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion2) : NULL,
                
                'dispoType' => $request->dispositionType,
                'dispoName' => $request->dispositionName,
                'dispoDate' => $request->dispositionDate,
                'healthStatus' => $request->healthStatus,
                'caseClassification' => $caseClassi,
                'isHealthCareWorker' => $request->isHealthCareWorker,
                'healthCareCompanyName' => $request->healthCareCompanyName,
                'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
                'isOFW' => $request->isOFW,
                'OFWCountyOfOrigin' => ($request->isOFW == 1) ? $request->OFWCountyOfOrigin : NULL,
                'OFWPassportNo' => ($request->isOFW == 1) ? $request->OFWPassportNo : NULL,
                'ofwType' => ($request->isOFW == 1) ? $request->ofwType : NULL,
                'isFNT' => $request->isFNT,
                'FNTCountryOfOrigin' => ($request->isFNT == 1) ? $request->FNTCountryOfOrigin : NULL,
                'FNTPassportNo' => ($request->isFNT == 1) ? $request->FNTPassportNo : NULL,
                'lsiType' => $request->lsiType,
                'isLSI' => $request->isLSI,
                'LSICity' => $request->LSICity,
                'LSIProvince' => $request->LSIProvince,
                'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
                'institutionType' => $request->institutionType,
                'institutionName' => $request->institutionName,
                'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
                'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                'SASFeverDeg' => $request->SASFeverDeg,
                'SASOtherRemarks' => $request->SASOtherRemarks,
                'COMO' => implode(",", $request->comCheck),
                'COMOOtherRemarks' => $request->COMOOtherRemarks,
                'PregnantLMP' => $request->PregnantLMP,
                'PregnantHighRisk' => $hrp,
                'diagWithSARI' => $request->diagWithSARI,
                'imagingDoneDate' => $request->imagingDoneDate,
                'imagingDone' => $request->imagingDone,
                'imagingResult' => $request->imagingResult,
                'imagingOtherFindings' => $request->imagingResult,
    
                'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
                'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
                'testedPositiveLab' => $request->testedPositiveLab,
                'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
    
                'testDateCollected1' => $request->testDateCollected1,
                'oniTimeCollected1' => $oniTimeFinal,
                'testDateReleased1' => $request->testDateReleased1,
                'testLaboratory1' => $request->testLaboratory1,
                'testType1' => $request->testType1,
                'testTypeAntigenRemarks1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'antigenKit1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'testResult1' => $request->testResult1,
                'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
    
                'testDateCollected2' => $request->testDateCollected2,
                'oniTimeCollected2' => $oniTimeFinal2,
                'testDateReleased2' => $request->testDateReleased2,
                'testLaboratory2' => $request->testLaboratory2,
                'testType2' => ($request->testType2 != "N/A") ? $request->testType2 : NULL,
                'testTypeAntigenRemarks2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                'antigenKit2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->antigenKit2) : NULL,
                'testTypeOtherRemarks2' => ($request->testType2 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                'testResult2' => ($request->testType2 != "N/A") ? $request->testResult2 : NULL,
                'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
    
                'outcomeCondition' => $request->outcomeCondition,
                'outcomeRecovDate' => $request->outcomeRecovDate,
                'outcomeDeathDate' => $request->outcomeDeathDate,
                'deathImmeCause' => $request->deathImmeCause,
                'deathAnteCause' => $request->deathAnteCause,
                'deathUndeCause' => $request->deathUndeCause,
                'contriCondi' => $request->contriCondi,
    
                'expoitem1' => $request->expoitem1,
                'expoDateLastCont' => $request->expoDateLastCont,
    
                'expoitem2' => $request->expoitem2,
                'intCountry' => $request->intCountry,
                'intDateFrom' => $request->intDateFrom,
                'intDateTo' => $request->intDateTo,
                'intWithOngoingCovid' => ($request->expoitem2 == 2) ? $request->intWithOngoingCovid : 'N/A',
                'intVessel' => $request->intVessel,
                'intVesselNo' => $request->intVesselNo,
                'intDateDepart' => $request->intDateDepart,
                'intDateArrive' => $request->intDateArrive,
    
                'placevisited' => (!is_null($request->placevisited)) ? implode(",", $request->placevisited) : NULL,
    
                'locName1' => $request->locName1,
                'locAddress1' => $request->locAddress1,
                'locDateFrom1' => $request->locDateFrom1,
                'locDateTo1' => $request->locDateTo1,
                'locWithOngoingCovid1' => (!is_null($request->placevisited) && in_array('Health Facility', $request->placevisited)) ? $request->locWithOngoingCovid1 : 'N/A', 
    
                'locName2' => $request->locName2,
                'locAddress2' => $request->locAddress2,
                'locDateFrom2' => $request->locDateFrom2,
                'locDateTo2' => $request->locDateTo2,
                'locWithOngoingCovid2' => (!is_null($request->placevisited) && in_array('Closed Settings', $request->placevisited)) ? $request->locWithOngoingCovid2 : 'N/A',
                
                'locName3' => $request->locName3,
                'locAddress3' => $request->locAddress3,
                'locDateFrom3' => $request->locDateFrom3,
                'locDateTo3' => $request->locDateTo3,
                'locWithOngoingCovid3' => (!is_null($request->placevisited) && in_array('School', $request->placevisited)) ? $request->locWithOngoingCovid3 : 'N/A',
                
                'locName4' => $request->locName4,
                'locAddress4' => $request->locAddress4,
                'locDateFrom4' => $request->locDateFrom4,
                'locDateTo4' => $request->locDateTo4,
                'locWithOngoingCovid4' => (!is_null($request->placevisited) && in_array('Workplace', $request->placevisited)) ? $request->locWithOngoingCovid4 : 'N/A',
    
                'locName5' => $request->locName5,
                'locAddress5' => $request->locAddress5,
                'locDateFrom5' => $request->locDateFrom5,
                'locDateTo5' => $request->locDateTo5,
                'locWithOngoingCovid5' => (!is_null($request->placevisited) && in_array('Market', $request->placevisited)) ? $request->locWithOngoingCovid5 : 'N/A',
    
                'locName6' => $request->locName6,
                'locAddress6' => $request->locAddress6,
                'locDateFrom6' => $request->locDateFrom6,
                'locDateTo6' => $request->locDateTo6,
                'locWithOngoingCovid6' => (!is_null($request->placevisited) && in_array('Social Gathering', $request->placevisited)) ? $request->locWithOngoingCovid6 : 'N/A',
    
                'locName7' => $request->locName7,
                'locAddress7' => $request->locAddress7,
                'locDateFrom7' => $request->locDateFrom7,
                'locDateTo7' => $request->locDateTo7,
                'locWithOngoingCovid7' => (!is_null($request->placevisited) && in_array('Others', $request->placevisited)) ? $request->locWithOngoingCovid7 : 'N/A',
    
                'localVessel1' => $request->localVessel1,
                'localVesselNo1' => $request->localVesselNo1,
                'localOrigin1' => $request->localOrigin1,
                'localDateDepart1' => $request->localDateDepart1,
                'localDest1' => $request->localDest1,
                'localDateArrive1' => $request->localDateArrive1,
    
                'localVessel2' => $request->localVessel2,
                'localVesselNo2' => $request->localVesselNo2,
                'localOrigin2' => $request->localOrigin2,
                'localDateDepart2' => $request->localDateDepart2,
                'localDest2' => $request->localDest2,
                'localDateArrive2' => $request->localDateArrive2,
    
                'contact1Name' => ($request->filled('contact1Name')) ? mb_strtoupper($request->contact1Name) : NULL,
                'contact1No' => $request->contact1No,
                'contact2Name' => ($request->filled('contact2Name')) ? mb_strtoupper($request->contact2Name) : NULL,
                'contact2No' => $request->contact2No,
                'contact3Name' => ($request->filled('contact3Name')) ? mb_strtoupper($request->contact3Name) : NULL,
                'contact3No' => $request->contact3No,
                'contact4Name' => ($request->filled('contact4Name')) ? mb_strtoupper($request->contact4Name) : NULL,
                'contact4No' => $request->contact4No,

                'remarks' => ($request->filled('remarks')) ? mb_strtoupper($request->remarks) : NULL,
                ]);

                if(request()->input('fromView') && request()->input('sdate') && request()->input('edate')) {
                    return redirect(route('forms.index')."?view=".request()->input('fromView')."&sdate=".request()->input('sdate')."&edate=".request()->input('edate')."")->with('status', 'CIF for '.$rec->lname.", ".$rec->fname." ".$rec->mname." has been updated successfully.")->with('statustype', 'success');
                }
                else {
                    return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF for '.$rec->lname.", ".$rec->fname." ".$rec->mname." has been updated successfully.")->with('statustype', 'success');
                }
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'Double Entry Detected! Edit Error: CIF Record for '.$rec->lname.", ".$rec->fname." ".$rec->mname." already exists at ".date('m/d/Y', strtotime($request->testDateCollected1)))->with('statustype', 'danger');
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forms $form)
    {
        if(auth()->user()->isAdmin == 1) {
            $form->delete();

            return redirect()->action([FormsController::class, 'index'])->with('status', "CIF for ".$form->records->getname()." has been updated successfully.")->with('statustype', 'success');
        }
        else {
            return back()
			->withInput()
			->with('msg', 'You are not allowed to do that.');
        }
    }
}
