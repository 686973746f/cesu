<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Forms;
use App\Models\Antigen;
use App\Models\Records;
use App\Imports\CifImport;
use App\Models\CifUploads;
use App\Exports\FormsExport;
use App\Imports\ExcelImport;
use App\Models\Interviewers;
use App\Models\LinelistSubs;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use App\Models\ExposureHistory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MonitoringSheetMaster;
use PragmaRX\Countries\Package\Countries;
use App\Http\Requests\FormValidationRequest;
use IlluminateAgnostic\Collection\Support\Str;

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
                        ->where(function ($sq) {
                            $sq->whereHas('user', function ($q) {
                                $q->where('brgy_id', auth()->user()->brgy_id);
                            })
                            ->orWhereHas('records', function ($q) {
                                $q->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                            })
                            ->orWhereHas('records', function ($q) {
                                $q->where('address_province', auth()->user()->brgy->city->province->provinceName)
                                ->where('address_city', auth()->user()->brgy->city->cityName)
                                ->where('address_brgy', auth()->user()->brgy->brgyName);
                            });
                        })
                        ->where(function ($query) {
                            $query->whereDate('testDateCollected1', request()->input('getDate'))
                            ->orWhereDate('testDateCollected2', request()->input('getDate'));
                        })
                        ->whereIn('caseClassification', ['Suspect', 'Probable'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                    }
                    else {
                        $forms = Forms::with('user')
                        ->where(function ($sq) {
                            $sq->whereHas('user', function ($query) {
                                $query->where('company_id', auth()->user()->company_id);
                            })
                            ->orWhereHas('records', function ($query) {
                                $query->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                            });
                        })
                        ->where(function ($query) {
                            $query->whereDate('testDateCollected1', request()->input('getDate'))
                            ->orWhereDate('testDateCollected2', request()->input('getDate'));
                        })
                        ->whereIn('caseClassification', ['Suspect', 'Probable'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                    }
                }
                else {
                    $forms = Forms::where(function ($query) {
                        $query->whereDate('testDateCollected1', request()->input('getDate'))
                        ->orWhereDate('testDateCollected2', request()->input('getDate'));
                    })
                    ->whereIn('caseClassification', ['Suspect', 'Probable'])
                    ->orderBy('created_at', 'desc')->get();
                }
            }
        }
        else {
            if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
                if(!is_null(auth()->user()->brgy_id)) {
                    $forms = Forms::with('user')
                    ->where(function ($sq) {
                        $sq->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })
                        ->orWhereHas('records', function ($query) {
                            $query->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                        })
                        ->orWhereHas('records', function ($q) {
                            $q->where('address_province', auth()->user()->brgy->city->province->provinceName)
                            ->where('address_city', auth()->user()->brgy->city->cityName)
                            ->where('address_brgy', auth()->user()->brgy->brgyName);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })
                    ->whereIn('caseClassification', ['Suspect', 'Probable'])
                    ->orderBy('created_at', 'desc')->get();
                }
                else {
                    $forms = Forms::with('user')
                    ->where(function ($sq) {
                        $sq->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })
                        ->orWhereHas('records', function ($query) {
                            $query->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })
                    ->whereIn('caseClassification', ['Suspect', 'Probable'])
                    ->orderBy('created_at', 'desc')->get();
                }
            }
            else {
                $forms = Forms::where(function ($q) {
                    $q->where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'));
                })
                ->whereIn('caseClassification', ['Suspect', 'Probable'])
                ->orderBy('created_at', 'desc')->get();
            }
        }

        //Forms Counter
        $formsctr = $forms;

        if(request()->input('view')) {
            $searchOnDate = request()->input('getDate');
        }
        else {
            $searchOnDate = date('Y-m-d');
        }

        $count_ops = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'OPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'OPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        });

        $count_nps = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'NPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'NPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        });

        $count_opsandnps = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'OPS AND NPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'OPS AND NPS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        });
        
        $count_antigen = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'ANTIGEN')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'ANTIGEN')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->count();

        $count_antibody = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'ANTIBODY')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'ANTIBODY')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->count();

        $count_others = Forms::where(function ($q) use ($searchOnDate) {
            $q->whereNull('testDateCollected2')
            ->whereDate('testDateCollected1', $searchOnDate)
            ->where('testType1', 'OTHERS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->orWhere(function ($q) use ($searchOnDate) {
            $q->whereNotNull('testDateCollected2')
            ->whereDate('testDateCollected2', $searchOnDate)
            ->where('testType2', 'OTHERS')
            ->whereIn('caseClassification', ['Suspect', 'Probable']);
        })
        ->count();

        $paswabctr = PaSwabDetails::where('status', 'pending')->count();

        return view('forms', [
            'forms' => $forms,
            'formsctr' => $formsctr,
            'paswabctr' => $paswabctr,
            'count_ops' => $count_ops,
            'count_nps' => $count_nps,
            'count_opsandnps' => $count_opsandnps,
            'count_antigen' => $count_antigen,
            'count_antibody' => $count_antibody,
            'count_others' => $count_others,
        ]);
    }

    public function ajaxList(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);

            if(auth()->user()->isCesuAccount()) {
                $data = Records::where(function ($query) use ($search) {
                    $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                    ->orWhere('id', $search);
                })->get();

                $paswab = PaSwabDetails::where(function ($query) use ($search) {
                    $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',$search)."%")
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',$search)."%");
                })->where('status', 'pending')->get();

                foreach($paswab as $item) {
                    array_push($list, [
                        'id' => $item->id,
                        'text' => '(PASWAB) - '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                        'class' => 'paswab',
                    ]);
                }
            }
            else {
                if(auth()->user()->isBrgyAccount()) {
                    $data = Records::with('user')
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
                    })
                    ->where(function($sq) {
						$sq->whereHas('user', function($q) {
							$q->where('brgy_id', auth()->user()->brgy_id)
							->orWhere('sharedOnId', 'LIKE', '%'.auth()->user()->id);
						})
						->orWhere(function ($q) {
							$q->where('address_province', auth()->user()->brgy->city->province->provinceName)
							->where('address_city', auth()->user()->brgy->city->cityName)
							->where('address_brgy', auth()->user()->brgy->brgyName);
						});
					})
                    ->get();
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

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->id.' - '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                    'class' => 'cif',
                ]);
            }
        }
        
        return response()->json($list);
    }

    public function ajaxcclist(Request $request) {
        $list = [];
        $self_id = request()->input('self_id');

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);

            $data = Forms::whereHas('records', function ($q) use ($search) {
                $q->where(DB::raw('CONCAT(records.lname," ",records.fname," ", records.mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(records.lname," ",records.fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere('records.id', $search);
            })
            ->whereHas('records', function ($q) use ($self_id) {
                $q->where('records.id', '!=', $self_id);
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->where('outcomeCondition', 'Active')
            //->whereBetween('created_at', [date('Y-m-d', strtotime('-14 Days')), date('Y-m-d')])
            ->orderBy('created_at', 'DESC')
            ->get();

            /*
            $data = Records::where(function ($q) use ($search) {
                $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere('id', $search);
            })
            ->where('id', '!=', $self_id)
            ->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->id.' - '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                ]);
            }
            */

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->records->id.' - '.$item->records->getName().' | '.$item->records->getAge().'/'.substr($item->records->gender,0,1).' | '.date('m/d/Y', strtotime($item->records->bdate)),
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
        return Excel::download(new FormsExport([$id], 'export_alphabetic'), 'CIF_'.date("m_d_Y").'.csv');
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
            if($testType == 1) {
                $aname = Antigen::where('id', $details->antigen_id1)->value('antigenKitName');
                
                if(!is_null($details->antigenLotNo1)) {
                    $alot = $details->antigenLotNo1;
                }
                else {
                    $alot = Antigen::where('id', $details->antigen_id1)->value('lotNo');
                }
            }
            else if($testType == 2) {
                $aname = Antigen::where('id', $details->antigen_id2)->value('antigenKitName');

                if(!is_null($details->antigenLotNo2)) {
                    $alot = $details->antigenLotNo2;
                }
                else {
                    $alot = Antigen::where('id', $details->antigen_id2)->value('lotNo');
                }
            }
            else {
                return abort(401);
            }

            if($details->testType1 == "ANTIGEN" || $details->testType2 == "ANTIGEN") {
                $pdf = PDF::loadView('pdf_antigen', [
                    'details' => $details,
                    'testType' => $testType,
                    'aname' => $aname,
                    'alot' => $alot,
                ])->setPaper('a4', 'portrait');
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

        if($request->submit == 'export' || $request->submit == 'export_alphabetic' || $request->submit == 'export_alphabetic_withp' || $request->submit == 'export_alphabetic_withp2') {
            //Export by Laboratory (ONI first, LaSalle second)
            $request->validate([
                'listToPrint' => 'required',
            ]);
            
            $models = Forms::whereIn('id', $list)
            ->update([
                'isExported' => '1',
                'exportedDate' => NOW(),
            ]);

            return Excel::download(new FormsExport($list, $request->submit), 'CIF_'.date("m_d_Y").'.csv');
        }
        else if($request->submit == 'export_type1') {
            
        }
        else if($request->submit == 'printsticker' || $request->submit == 'printsticker_alllasalle') {
            $models = Forms::with('records')->whereIn('id', $list)->get();

            $models = $models->sortBy('records.lname');

            echo '<br>';

            foreach($models as $item) {
                if(!is_null($item->testDateCollected2)) {
                    $swabtype = $item->testType2;
                    $swabdate = Carbon::parse($item->testDateCollected2)->format('m/d/Y');
                    $swabtime = Carbon::parse($item->oniTimeCollected2)->format('h:i A');
                }
                else {
                    $swabtype = $item->testType1;
                    $swabdate = Carbon::parse($item->testDateCollected1)->format('m/d/Y');
                    $swabtime = Carbon::parse($item->oniTimeCollected1)->format('h:i A');
                }
                
                if($request->submit == 'printsticker') {
                    if(!is_null($item->records->philhealth)) {
                        echo $item->records->getName().'<br>'.
                        $item->records->getAge().'/'.substr($item->records->gender,0,1).' '.date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                        $swabtype.' '.$swabdate.' '.$swabtime.'<br><br>'.
                        $item->records->getName().'<br>'.
                        $item->records->getAge().'/'.substr($item->records->gender,0,1).' '.date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                        $swabtype.' '.$swabdate.' '.$swabtime.'<br><br>';
                    }
                    else {
                        echo $item->records->lname.',<br>'.
                        $item->records->fname.' '.$item->records->mname.'<br>'.
                        date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                        $item->records->getAge().'/'.substr($item->records->gender,0,1).'<br>'.
                        $swabtype.'<br><br>'.
                        $item->records->lname.',<br>'.
                        $item->records->fname.' '.$item->records->mname.'<br>'.
                        date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                        $item->records->getAge().'/'.substr($item->records->gender,0,1).'<br>'.
                        $swabtype.'<br><br>';
                    }
                }
                else {
                    echo $item->records->lname.',<br>'.
                    $item->records->fname.' '.$item->records->mname.'<br>'.
                    date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                    $item->records->getAge().'/'.substr($item->records->gender,0,1).'<br>'.
                    $swabtype.'<br><br>'.
                    $item->records->lname.',<br>'.
                    $item->records->fname.' '.$item->records->mname.'<br>'.
                    date('m/d/Y', strtotime($item->records->bdate)).'<br>'.
                    $item->records->getAge().'/'.substr($item->records->gender,0,1).'<br>'.
                    $swabtype.'<br><br>';
                }
            }
        }
        else if($request->submit == 'resched') {
            $request->validate([
                'listToPrint' => 'required',
                'reschedDate' => 'required|date',
            ]);

            $models = Forms::whereIn('id', $list)->get();
            foreach($models as $item) {
                $f = Forms::find($item->id);
                
                if(!is_null($item->testDateCollected2)) {
                    $f->testDateCollected2 = $request->reschedDate;
                }
                else {
                    $f->testDateCollected1 = $request->reschedDate;
                }

                if($f->isDirty('testDateCollected2') || $f->isDirty('testDateCollected1')) {
                    $f->isExported = 0;
                    $f->updated_by = auth()->user()->id;

                    $f->save();
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
                        'updated_by' => auth()->user()->id,
                        'testType2' => $request->changeType,
                        'isExported' => '0',
                        'testTypeAntigenRemarks2' => $antigenReason,
                        'antigenKit2' => $antigenKit,
                        'testTypeOtherRemarks2' => $otherReason
                    ]);
                }
                else {
                    $query = Forms::where('id', $item->id)->update([
                        'updated_by' => auth()->user()->id,
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
        else if ($request->submit == 'cancelsched') {
            $models = Forms::whereIn('id', $list)->get();
            foreach ($models as $item) {
                if(!is_null($item->testDateCollected2)) {
                    $query = Forms::where('id', $item->id)
                    ->update([
                        'testDateCollected2' => NULL,
                        'oniTimeCollected2' => NULL,
                        'testDateReleased2' => NULL,
                        'testLaboratory2' => NULL,
                        'testType2' => NULL,
                        'testTypeAntigenRemarks2' => NULL,
                        'antigenKit2' => NULL,
                        'testTypeOtherRemarks2' => NULL,
                        'testResult2' => NULL,
                        'testResultOtherRemarks2' => NULL,
                    ]);
                }
                else {
                    $query = Forms::where('id', $item->id)
                    ->update([
                        'testDateCollected1' => NULL,
                        'testDateReleased1' => NULL,
                        'oniTimeCollected1' => NULL,
                        'testLaboratory1' => NULL,
                        'testType1' => NULL,
                        'testTypeAntigenRemarks1' => NULL,
                        'antigenKit1' => NULL,
                        'testTypeOtherRemarks1' => NULL,
                        'testResult1' => NULL,
                        'testResultOtherRemarks1' => NULL,
                    ]);
                }
            }

            return redirect()->action([FormsController::class, 'index'])->with('status', 'All Selected CIF Schedule Data has been cancelled.')->with('statustype', 'success');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function new($id) {
        if(Records::eligibleToUpdate($id)) {
            $check = Records::findOrFail($id);

            //is_confidential
            if(!($check->ifAllowedToViewConfidential())) {
                return view('confidential_index', ['record' => $check]);
            }

            $check2 = Forms::whereHas('records', function ($q) use ($check) {
                $q->where('records.id', $check->id);
            })->count();

            if($check2 >= 1) {
                //existing na
                $ex_id = Forms::where('records_id', $check->id)->orderBy('created_at', 'DESC')->first();

                $l = LinelistSubs::where('records_id', $check->id)->orderBy('created_at', 'DESC')->first();

                return view('forms_existing', ['form' => $ex_id, 'l' => $l]);
                /*
                Old Redirect Method
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
                */
            }
            else {
                $interviewers = Interviewers::where('enabled', 1)
                ->orderBy('lname', 'asc')
                ->get();

                //Positive Encoding Cutoff indicator
                if(time() >= strtotime('16:00:00')) {
                    $is_cutoff = true;
                }
                else {
                    $is_cutoff = false;
                }

                //Get Antigen List
                $antigen_list = Antigen::orderBy('antigenKitShortName', 'ASC')->get();

                //Adjust Max Date of Swab
                if(date('m') == 01) {
                    $mindate = date('Y-12-01', strtotime('-1 Year'));
                    $enddate = date('Y-12-31');
                }
                else if(date('m') == 12) {
                    $mindate = date('Y-01-01');
                    $enddate = date('Y-01-31', strtotime('+1 Year'));
                }
                else {
                    $mindate = date('Y-01-01');
                    $enddate = date('Y-12-31');
                }
                
                $countries = new Countries();
                $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
                $all = $countries->all()->pluck('name.common')->toArray();
                return view('formscreate', [
                    'countries' => $all,
                    'records' => $check,
                    'interviewers' => $interviewers,
                    'id' => $id,
                    'is_cutoff' => $is_cutoff,
                    'antigen_list' => $antigen_list,
                    'mindate' => $mindate,
                    'enddate' => $enddate,
                ]);
            }
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
        }
        
        /*
        Old Checking Method

        if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
            if(!is_null(auth()->user()->brgy_id)) {
                $check = Records::with('user')
                ->where('id', $id)
                ->where(function ($sq) {
                    $sq->whereHas('user', function ($q) {
                        $q->where('brgy_id', auth()->user()->brgy_id);
                    })
                    ->orWhereHas('records', function ($q) {
                        $q->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                    })
                    ->orWhereHas('records', function ($q) {
                        $q->where('address_province', auth()->user()->brgy->city->province->provinceName)
                        ->where('address_city', auth()->user()->brgy->city->cityName)
                        ->where('address_brgy', auth()->user()->brgy->brgyName);
                    });
                })
                ->first();
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
            
        }
        else {
            
        }
        */ 
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

        $checkform = Forms::where('records_id', $rec->id)
        ->where(function ($q) {
            $q->whereDate('morbidityMonth', date('Y-m-d'))
            ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->first();

        /*
        $checkform = Forms::where('records_id', $rec->id)
        ->where(function ($q) {
            $q->where(function ($r) {
                $r->whereDate('testDateCollected1', date('Y-m-d'))
                ->orWhereDate('testDateCollected2', date('Y-m-d'));
            })
            ->orWhereDate('created_at', date('Y-m-d'));
        })
        ->first();
        */

        if($checkform) {
            return redirect()->route('forms.index')->with('status', 'Error: '.$rec->getName()." CIF Data was already existed and your request was blocked to prevent double entry.")->with('statustype', 'danger');
        }
        else {
            if($rec->gender == 'MALE') {
                $hrp = 0;
            }
            else {
                if($rec->isPregnant == 0) {
                    $hrp = 0;
                }
                else {
                    $hrp = $request->highRiskPregnancy;
                }
            }

            $request->validated();
    
            if(!is_null($request->testDateCollected2) || !is_null($request->testDateCollected1)) {
                if($request->testDateCollected1 == date('Y-m-d') || $request->testDateCollected2 == date('Y-m-d')) {
                    $sameDateEncode = true;
                }
                else {
                    $sameDateEncode = false;
                }
            }
            else {
                $sameDateEncode = false;
            }
    
            if(auth()->user()->isBrgyAccount() && auth()->user()->canByPassCutoff == 0 && time() >= strtotime('08:30') && $sameDateEncode) {
                return back()
                ->withInput()
                ->with('msg', 'Warning: Cannot Encode the CIF Scheduled for Swab Today. Cut-off time reached (08:30 AM Onwards) Daily.')
                ->with('msgType', 'warning');
            }
    
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
    
            //Auto Change Classification kung Recovered or Patay na ang pasyente
            if($request->outcomeCondition == 'Recovered' || $request->outcomeCondition == 'Died') {
                $caseClassi = 'Confirmed';
            }
    
            //Auto Change Testing Category/Subgroup Base on the patient data
            $testCat = $request->testingCat;
            if(!in_array("A", $testCat) && in_array($request->healthStatus, ['Severe','Critical'])) {
                array_push($testCat, "A");
            }
            /*
            if(!in_array("D.1", $testCat) && $request->healthStatus == 'Asymptomatic') {
                array_push($testCat, "D.1");
            }
            */
            if(!in_array("C", $testCat) && !is_null($request->sasCheck) && !in_array("Asymptomatic", $request->sasCheck)) {
                array_push($testCat, "C");
            }
            if(!in_array("B", $testCat) && $rec->getAgeInt() >= 60) {
                array_push($testCat, "B");
            }
            if(!in_array("D.1", $testCat) && $request->pType == 'CLOSE CONTACT') {
                array_push($testCat, "D.1");
            }
            if(!in_array("F.1", $testCat) && $rec->isPregnant == 1) {
                array_push($testCat, "F.1");
            }
            if(!in_array("F.3", $testCat) && $request->isForHospitalization == 1 && $rec->isPregnant == 0) {
                array_push($testCat, "F.3");
            }
            if(!in_array("F.2", $testCat) && in_array('Dialysis', $request->comCheck)) {
                array_push($testCat, "F.2");
            }
            if(!in_array("F.4", $testCat) && in_array('Cancer', $request->comCheck)) {
                array_push($testCat, "F.4");
            }
            if(!in_array("F.5", $testCat) && in_array('Operation', $request->comCheck)) {
                array_push($testCat, "F.5");
            }
            if(!in_array("F.6", $testCat) && in_array('Transplant', $request->comCheck)) {
                array_push($testCat, "F.6");
            }
            /*
            if(!in_array("F", $testCat) && $request->isForHospitalization == 1) {
                array_push($testCat, "F");
            }
            */
    
            if(!in_array('D.2', $testCat) && $rec->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                array_push($testCat, "D.2");
            }
            if(!in_array('I', $testCat) && $rec->natureOfWork == 'MANUFACTURING') {
                array_push($testCat, "I");
            }
            if(!in_array('E2.3', $testCat) && $rec->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                array_push($testCat, "E2.3");
            }

            if(!in_array('J1.1', $testCat) && $rec->natureOfWork == 'TRANSPORTATION') {
                array_push($testCat, "J1.1");
            }
            if(!in_array('J1.1', $testCat) && $rec->natureOfWork == 'MANNING/SHIPPING AGENCY') {
                array_push($testCat, "J1.1");
            }
            if(!in_array('J1.1', $testCat) && $rec->natureOfWork == 'STORAGE') {
                array_push($testCat, "J1.1");
            }
            if(!in_array('J1.3', $testCat) && $rec->natureOfWork == 'EDUCATION') {
                array_push($testCat, "J1.3");
            }
            if(!in_array('J1.8', $testCat) && $rec->natureOfWork == 'CONSTRUCTION') {
                array_push($testCat, "J1.8");
            }
            if(!in_array('J1.8', $testCat) && $rec->natureOfWork == 'ELECTRICITY') {
                array_push($testCat, "J1.8");
            }
            if(!in_array('J1.2', $testCat) && $rec->natureOfWork == 'HOTEL AND RESTAURANT') {
                array_push($testCat, "J1.2");
            }
            if(!in_array('J1.2', $testCat) && $rec->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                array_push($testCat, "J1.2");
            }
            if(!in_array('J1.4', $testCat) && $rec->natureOfWork == 'FINANCIAL') {
                array_push($testCat, "J1.4");
            }
            if(!in_array('J1.6', $testCat) && $rec->natureOfWork == 'SERVICES') {
                array_push($testCat, "J1.6");
            }
            if(!in_array('J1.11', $testCat) && $rec->natureOfWork == 'MASS MEDIA') {
                array_push($testCat, "J1.11");
            }
    
            //Auto Change Case Classification based on symptoms
            if($caseClassi != 'Confirmed' && $caseClassi != 'Non-COVID-19 Case' && !is_null($request->sasCheck)) {
                if(in_array('Anosmia (Loss of Smell)', $request->sasCheck) || in_array('Ageusia (Loss of Taste)', $request->sasCheck)) {
                    $caseClassi = 'Probable';
                }
            }
    
            //Auto Re-infect if Positive + Positive ang Old CIF
            if($caseClassi == 'Confirmed') {
                $oldcifcheck = Forms::where('id', '!=', $id)
                ->where('records_id', $rec->id)
                ->where('caseClassification', 'Confirmed')
                ->first();
                if($oldcifcheck) {
                    $autoreinfect = 1;
                    
                    /*
                    if($request->dispositionType == 6) {
                        $autoreinfect = 0;
                    }
                    else {
                        $autoreinfect = 1;
                    }
                    */
                }
                else {
                    $autoreinfect = 0;
                }
            }
            else {
                $autoreinfect = 0;
            }

            //Auto MM if reached cutoff
            if($caseClassi != 'Confirmed' && $caseClassi != 'Non-COVID-19 Case') {
                if(time() >= strtotime('13:00:00')) {
                    $set_mm = date('Y-m-d', strtotime('+1 Day'));
                    $set_mt = date('Y-m-d 08:00:00', strtotime('+1 Day'));
                }
                else {
                    $set_mm = $request->morbidityMonth;
                    $set_mt = $request->morbidityMonth.' '.date('H:i:s');
                }
            }
            else {
                $set_mm = $request->morbidityMonth;
                $set_mt = $request->morbidityMonth.' '.date('H:i:s');
            }

            //Auto Change Date Reported Based on Date Released
            if($caseClassi == 'Confirmed') {
                if(!is_null($request->testDateCollected2)) {
                    $set_dr = $request->testDateReleased2;
                }
                else {
                    $set_dr = $request->testDateReleased1;
                }
            }
            else {
                $set_dr = $request->dateReported;
            }

            //Block Re-infection if di pa lagpas ng 90 Days
            if($caseClassi == 'Confirmed' && $request->outcomeCondition != 'Died') {
                $confirmed_search = Forms::where('status', 'approved')
                ->where('records_id', $rec->id)
                ->where('caseClassification', 'Confirmed')
                ->orderBy('created_at', 'DESC')
                ->first();
                if($confirmed_search) {
                    $sDate = Carbon::parse($confirmed_search->dateReported);
                    $now = Carbon::parse(date('Y-m-d'));

                    $diffInDays = $sDate->diffInDays($now);

                    if($diffInDays <= 90) {
                        return back()
                        ->withInput()
                        ->with('msg', 'Warning: The patient (#'.$rec->id.' - '.$rec->getName().') has existing Confirmed Case that is still not 90 days old. Therefore, your submission is blocked.')
                        ->with('msgType', 'warning');
                    }
                }
            }

            //Auto Recovered if Lagpas na sa Quarantine Period
            if($caseClassi == 'Confirmed' && $request->outcomeCondition != 'Died') {
                $dateToday = Carbon::parse(date('Y-m-d'));
                
                if($request->dispositionType != 6 && $request->dispositionType != 7) {
                    if(!is_null($request->testType2)) {
                        $swabDateCollected = $request->testDateCollected2;
                    }
                    else {
                        $swabDateCollected = $request->testDateCollected1;
                    }

                    if($request->dispositionType == 1 || $request->healthStatus == 'Severe' || $request->healthStatus == 'Critical') {
                        $daysToRecover = 21;
                    }
                    else {
                        if(!is_null($rec->vaccinationDate2)) {
                            $date1 = Carbon::parse($rec->vaccinationDate2);
                            $days_diff = $date1->diffInDays($dateToday);
    
                            if($days_diff >= 14) {
                                $daysToRecover = 7;
                            }
                            else {
                                $daysToRecover = 10;
                            }
                        }
                        else {
                            if($rec->vaccinationName1 == 'JANSSEN') {
                                $date1 = Carbon::parse($rec->vaccinationDate1);
                                $days_diff = $date1->diffInDays($dateToday);

                                if($days_diff >= 14) {
                                    $daysToRecover = 7;
                                }
                                else {
                                    $daysToRecover = 10;
                                }
                            }
                            else {
                                $daysToRecover = 10;
                            }
                        }
                    }

                    $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                    $diff = $startDate->diffInDays($dateToday);
                    if($diff >= $daysToRecover) {
                        $auto_outcome = 'Recovered';
                        $auto_outcome_recovered_date = Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('Y-m-d');
                        //$auto_outcome_recovered_date = date('Y-m-d');

                        $add_note = 'Note: The patient CIF was automatically moved to Recovered Cases because the Quarantine Period is already over.';
                    }
                    else {
                        $auto_outcome = $request->outcomeCondition;
                        $auto_outcome_recovered_date = $request->outcomeRecovDate;
                    }
                }
                else {
                    $auto_outcome = $request->outcomeCondition;
                    $auto_outcome_recovered_date = $request->outcomeRecovDate;
                }
            }
            else {
                $auto_outcome = $request->outcomeCondition;
                $auto_outcome_recovered_date = $request->outcomeRecovDate;
            }

            //Get Number of Previous Swab and if Nag-positive
            $previousswab_count = Forms::where('records_id', $rec->id)
            ->where('isPresentOnSwabDay', 1)
            ->where(function ($q) {
                $q->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                ->orWhereIn('testType2', ['OPS', 'NPS', 'OPS AND NPS']);
            })
            ->count();

            $get_previousswab_positive = Forms::where('records_id', $rec->id)
            ->where('caseClassification', 'Confirmed')
            ->orderBy('created_at', 'DESC')
            ->first();

            if($get_previousswab_positive) {
                if(!is_null($get_previousswab_positive->testDateCollected2)) {
                    $get_testedPositiveSpecCollectedDate = $get_previousswab_positive->testDateCollected2;
                    $get_testedPositiveLab = $get_previousswab_positive->testLaboratory2;
                }
                else {
                    $get_testedPositiveSpecCollectedDate = $get_previousswab_positive->testDateCollected1;
                    $get_testedPositiveLab = $get_previousswab_positive->testLaboratory1;
                }
            }
            else {
                $get_testedPositiveLab = NULL;
                $get_testedPositiveSpecCollectedDate = NULL;
            }
    
            if($set_mm == date('Y-m-d') && $caseClassi == 'Confirmed' && time() >= strtotime('16:00:00')) {
                return back()
                ->withInput()
                ->with('msg', 'Warning: Encoding and Editing Active Cases only allowed until 4PM - 12AM Daily. You can change the Morbidity Month to Tomorrow ('.date('m/d/Y', strtotime('+1 Day')).') so that it could be encoded for tomorrow.')
                ->with('msgType', 'warning');
            }
            else {
                $createform = $request->user()->form()->create([
                    'reinfected' => ($request->reinfected || $autoreinfect == 1) ? 1 : 0,
                    'morbidityMonth' => $set_mm,
                    'morbidityTime' => $set_mt,
                    'dateReported' => $set_dr,
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
                    'ccType' => ($request->pType == 'CLOSE CONTACT') ? $request->ccType : NULL,
                    'ccid_list' => (!is_null($request->ccid_list)) ? implode(",", $request->ccid_list) : NULL,
                    'isForHospitalization' => $request->isForHospitalization,
                    'testingCat' => implode(",", $testCat),
                    'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                    'dateOfFirstConsult' => $request->dateOfFirstConsult,
                    'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
                    
                    'dispoType' => $request->dispositionType,
                    'dispoName' => $request->dispositionName,
                    'dispoDate' => $request->dispositionDate,
                    'healthStatus' => $request->healthStatus,
                    'caseClassification' => $caseClassi,
                    'date_of_positive' => ($caseClassi == 'Confirmed') ? $set_dr : NULL,
                    'confirmedVariantName' => $request->confirmedVariantName,
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
        
                    'testedPositiveUsingRTPCRBefore' => ($get_previousswab_positive) ? 1 : 0,
                    'testedPositiveNumOfSwab' => $previousswab_count, // Previous RT-PCR Swab Done
                    'testedPositiveLab' => $get_testedPositiveLab,
                    'testedPositiveSpecCollectedDate' => $get_testedPositiveSpecCollectedDate,
        
                    'testDateCollected1' => (!is_null($request->testType1)) ? $request->testDateCollected1 : NULL,
                    'oniTimeCollected1' => $oniTimeFinal,
                    'testDateReleased1' => $request->testDateReleased1,
                    'testLaboratory1' => $request->testLaboratory1,
                    'testType1' => $request->testType1,
                    'testTypeAntigenRemarks1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                    //'antigenKit1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                    'antigen_id1' => ($request->testType1 == "ANTIGEN") ? $request->antigen_id1 : NULL,
                    'antigenLotNo1' => ($request->testType1 == "ANTIGEN" && !is_null($request->antigenLotNo1)) ? mb_strtoupper($request->antigenLotNo1) : NULL,
                    'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                    'testResult1' => (!is_null($request->testType1)) ? $request->testResult1 : NULL,
                    'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
        
                    'testDateCollected2' => (!is_null($request->testType2)) ? $request->testDateCollected2 : NULL,
                    'oniTimeCollected2' => $oniTimeFinal2,
                    'testDateReleased2' => $request->testDateReleased2,
                    'testLaboratory2' => $request->testLaboratory2,
                    'testType2' => $request->testType2,
                    'testTypeAntigenRemarks2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                    //'antigenKit2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->antigenKit2) : NULL,
                    'antigen_id2' => ($request->testType2 == "ANTIGEN") ? $request->antigen_id2 : NULL,
                    'antigenLotNo2' => ($request->testType2 == "ANTIGEN" && !is_null($request->antigenLotNo2)) ? mb_strtoupper($request->antigenLotNo2) : NULL,
                    'testTypeOtherRemarks2' => ($request->testType2 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                    'testResult2' => (!is_null($request->testType2)) ? $request->testResult2 : NULL,
                    'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
        
                    'outcomeCondition' => $auto_outcome,
                    'outcomeRecovDate' => $auto_outcome_recovered_date,
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
    
                //Create Monitoring Sheet
                $msheet_search = MonitoringSheetMaster::where('forms_id', $createform->id)->first();
                if(!$msheet_search) {
                    $foundunique = false;
                    while(!$foundunique) {
                        $majik = Str::random(30);
                        
                        $search = MonitoringSheetMaster::where('magicURL', $majik);
                        if($search->count() == 0) {
                            $foundunique = true;
                        }
                    }
    
                    $newmsheet = new MonitoringSheetMaster;
                    
                    $newmsheet->forms_id = $createform->id;
                    $newmsheet->region = '4A';
                    $newmsheet->date_lastexposure = (!is_null($createform->expoDateLastCont)) ? $createform->expoDateLastCont : $createform->interviewDate;
                    $newmsheet->date_startquarantine = $createform->interviewDate;
                    $newmsheet->date_endquarantine = Carbon::parse($createform->interviewDate)->addDays(13)->format('Y-m-d');
                    $newmsheet->magicURL = $majik;
    
                    $newmsheet->save();
                }
                
                return redirect()->action([FormsController::class, 'index'])
                ->with('status', 'CIF of Patient ('.$rec->getName().' #'.$rec->id.') was created successfully.')
                ->with('statustype', 'success')
                ->with('add_note', (isset($add_note)) ? $add_note : NULL);
            }
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
        $records = Forms::findOrFail($id);
        
        //is_confidential
        if(!($records->records->ifAllowedToViewConfidential())) {
            return view('confidential_index', ['record' => $records->records]);
        }

        if(Records::eligibleToUpdate($records->records_id)) {
            $interviewers = Interviewers::where('enabled', 1)
            ->orderBy('lname', 'asc')
            ->get();

            $docs = CifUploads::where('forms_id', $id)->get();

            $countries = new Countries();
            $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
            $all = $countries->all()->pluck('name.common')->toArray();

            $oldRecords = Forms::where('records_id', $records->records_id)->onlyTrashed()->get();
            $oldCif = Forms::where('id', '!=', $records->id)
            ->where('records_id', $records->records_id)
            ->get();

            $msheet = MonitoringSheetMaster::where('forms_id', $records->id)->first();

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

            //Positive Encoding Cutoff indicator
            if(time() >= strtotime('16:00:00')) {
                $is_cutoff = true;
            }
            else {
                $is_cutoff = false;
            }

            //Get Current CC List
            $cc_list_array = explode(',', $records->ccid_list);
            $get_current_ccid_data = Forms::whereIn('id', $cc_list_array)->get();

            //Get ExposureHistory List
            $get_ctdata = ExposureHistory::where('form_id', $records->id)->get();

            //Get Antigen List
            $antigen_list = Antigen::orderBy('antigenKitShortName', 'ASC')->get();

            //Adjust Max Date of Swab
            if(date('m') == 01) {
                $mindate = date('Y-12-01', strtotime('-1 Year'));
                $enddate = date('Y-12-31');
            }
            else if(date('m') == 12) {
                $mindate = date('Y-01-01');
                $enddate = date('Y-01-31', strtotime('+1 Year'));
            }
            else {
                if(date('Y', strtotime($records->testDateCollected1)) != date('Y')) {
                    $mindate = date('Y-01-01', strtotime($records->testDateCollected1));
                    $enddate = date('Y-12-31');
                }
                else {
                    $mindate = date('Y-01-01');
                    $enddate = date('Y-12-31');
                }
            }

            return view('formsedit', [
                'countries' => $all,
                'records' => $records,
                'interviewers' => $interviewers,
                'docs' => $docs,
                'oldRecords' => $oldRecords,
                'vaccineDose' => $vaccineDose,
                'oldCif' => $oldCif,
                'msheet' => $msheet,
                'is_cutoff' => $is_cutoff,
                'current_ccid_data' => $get_current_ccid_data,
                'get_ctdata' => $get_ctdata,
                'antigen_list' => $antigen_list,
                'mindate' => $mindate,
                'enddate' => $enddate,
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

        if(Records::eligibleToUpdate($rec->records_id)) {
            //Checking Parameters Before Updating
            if($rec->ifOldCif()) {
                if(auth()->user()->ifTopAdmin()) {
                    $proceed2 = 1;
                }
                else {
                    $proceed2 = 0;
                }
            }
            else {
                if($rec->outcomeCondition == 'Active') {
                    if(auth()->user()->isCesuAccount()) {
                        $proceed2 = 1;
                    }
                    else {
                        if($rec->caseClassification == 'Confirmed') {
                            $proceed2 = 0;
                        }
                        else {
                            $proceed2 = 1;
                        }
                    }
                }
                else {
                    if($rec->outcomeCondition == 'Recovered') {
                        if(auth()->user()->isCesuAccount()) {
                            $proceed2 = 1;
                        }
                        else {
                            $proceed2 = 0;
                        }
                    }
                    else {
                        //Died, only admin can edit
                        if(auth()->user()->ifTopAdmin()) {
                            $proceed2 = 1;
                        }
                        else {
                            $proceed2 = 0;
                        }
                    }
                }
            }

            if($proceed2 == 1) {
                $oldAttendance = $rec->isPresentOnSwabDay;
                $olddate = $rec->testDateCollected1;
                $oldTestType1 = $rec->testType1;
                $oldTestType2 = $rec->testType2;
                $currentPhilhealth = $rec->records->philhealth;
                $currentOutcome = $rec->outcomeCondition;
                $currentClassi = $rec->caseClassification;

                //$rec = Records::findOrFail($rec->records->id);

                if($rec->records->gender == 'MALE') {
                    $hrp = 0;
                }
                else {
                    if($rec->records->isPregnant == 0) {
                        $hrp = 0;
                    }
                    else {
                        $hrp = $request->highRiskPregnancy;
                    }
                }

                if($request->testResult1 != "PENDING") {
                    if($request->testResult1 == "POSITIVE") {
                        $caseClassi = 'Confirmed';
                    }
                    else if($request->testResult1 == "NEGATIVE") {
                        /*
                        if($request->pType == 'CLOSE CONTACT') {
                            $caseClassi = 'Suspect';
                        }
                        else {
                            $caseClassi = 'Non-COVID-19 Case';
                        }
                        */

                        $caseClassi = 'Non-COVID-19 Case';
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
                            /*
                            if($request->pType == 'CLOSE CONTACT') {
                                $caseClassi = 'Suspect';
                            }
                            else {
                                $caseClassi = 'Non-COVID-19 Case';
                            }
                            */
                            
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
                        ['records_id', $rec->records_id],
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
        
                //Auto Change Classification kung Recovered or Patay na ang pasyente
                if($request->outcomeCondition == 'Recovered' || $request->outcomeCondition == 'Died') {
                    $caseClassi = 'Confirmed';
                }
        
                //Auto Change MM pag namatay
                if($currentOutcome != 'Died' && $request->outcomeCondition == 'Died') {
                    $request->morbidityMonth = date('Y-m-d');
                }
        
                //Auto Change Testing Category/Subgroup Base on the patient data
                $testCat = $request->testingCat;
                if(!in_array("A", $testCat) && in_array($request->healthStatus, ['Severe','Critical'])) {
                    array_push($testCat, "A");
                }
                /*
                if(!in_array("D.1", $testCat) && $request->healthStatus == 'Asymptomatic') {
                    array_push($testCat, "D.1");
                }
                */
                if(!in_array("C", $testCat) && !is_null($request->sasCheck) && !in_array("Asymptomatic", $request->sasCheck)) {
                    array_push($testCat, "C");
                }
                if(!in_array("B", $testCat) && $rec->records->getAgeInt() >= 60) {
                    array_push($testCat, "B");
                }
                if(!in_array("D.1", $testCat) && $request->pType == 'CLOSE CONTACT') {
                    array_push($testCat, "D.1");
                }
                if(!in_array("F.1", $testCat) && $rec->records->isPregnant == 1) {
                    array_push($testCat, "F.1");
                }
                if(!in_array("F.2", $testCat) && in_array('Dialysis', $request->comCheck)) {
                    array_push($testCat, "F.2");
                }
                if(!in_array("F.3", $testCat) && $request->isForHospitalization == 1 && $rec->records->isPregnant == 0) {
                    array_push($testCat, "F.3");
                }
                if(!in_array("F.4", $testCat) && in_array('Cancer', $request->comCheck)) {
                    array_push($testCat, "F.4");
                }
                if(!in_array("F.5", $testCat) && in_array('Operation', $request->comCheck)) {
                    array_push($testCat, "F.5");
                }
                if(!in_array("F.6", $testCat) && in_array('Transplant', $request->comCheck)) {
                    array_push($testCat, "F.6");
                }

                if(!in_array('D.2', $testCat) && $rec->records->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                    array_push($testCat, "D.2");
                }
                if(!in_array('I', $testCat) && $rec->records->natureOfWork == 'MANUFACTURING') {
                    array_push($testCat, "I");
                }
                if(!in_array('E2.3', $testCat) && $rec->records->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                    array_push($testCat, "E2.3");
                }

                if(!in_array('J1.1', $testCat) && $rec->records->natureOfWork == 'TRANSPORTATION') {
                    array_push($testCat, "J1.1");
                }
                if(!in_array('J1.1', $testCat) && $rec->records->natureOfWork == 'MANNING/SHIPPING AGENCY') {
                    array_push($testCat, "J1.1");
                }
                if(!in_array('J1.1', $testCat) && $rec->records->natureOfWork == 'STORAGE') {
                    array_push($testCat, "J1.1");
                }
                if(!in_array('J1.3', $testCat) && $rec->records->natureOfWork == 'EDUCATION') {
                    array_push($testCat, "J1.3");
                }
                if(!in_array('J1.8', $testCat) && $rec->records->natureOfWork == 'CONSTRUCTION') {
                    array_push($testCat, "J1.8");
                }
                if(!in_array('J1.8', $testCat) && $rec->records->natureOfWork == 'ELECTRICITY') {
                    array_push($testCat, "J1.8");
                }
                if(!in_array('J1.2', $testCat) && $rec->records->natureOfWork == 'HOTEL AND RESTAURANT') {
                    array_push($testCat, "J1.2");
                }
                if(!in_array('J1.2', $testCat) && $rec->records->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                    array_push($testCat, "J1.2");
                }
                if(!in_array('J1.4', $testCat) && $rec->records->natureOfWork == 'FINANCIAL') {
                    array_push($testCat, "J1.4");
                }
                if(!in_array('J1.6', $testCat) && $rec->records->natureOfWork == 'SERVICES') {
                    array_push($testCat, "J1.6");
                }
                if(!in_array('J1.11', $testCat) && $rec->records->natureOfWork == 'MASS MEDIA') {
                    array_push($testCat, "J1.11");
                }
        
                //Auto Change Case Classification based on symptoms
                if($caseClassi != 'Confirmed' && $caseClassi != 'Non-COVID-19 Case' && !is_null($request->sasCheck)) {
                    if(in_array('Anosmia (Loss of Smell)', $request->sasCheck) || in_array('Ageusia (Loss of Taste)', $request->sasCheck)) {
                        $caseClassi = 'Probable';
                    }
                }

                //Auto Re-infect if Positive + Positive ang Old CIF
                if($caseClassi == 'Confirmed') {
                    $oldcifcheck = Forms::where('id', '!=', $id)
                    ->where('records_id', $rec->records->id)
                    ->where('caseClassification', 'Confirmed')
                    ->first();
                    if($oldcifcheck) {
                        $autoreinfect = 1;

                        /*
                        if($rec->dispoType != 6 && $request->dispositionType == 6) {
                            $autoreinfect = 0;
                        }
                        else {
                            $autoreinfect = 1;
                        }
                        */
                    }
                    else {
                        $autoreinfect = 0;
                    }
                }
                else {
                    $autoreinfect = 0;
                }

                //Auto MM if reached cutoff
                if($rec->morbidityMonth != $request->morbidityMonth) {
                    if($caseClassi != 'Confirmed' && $caseClassi != 'Non-COVID-19 Case') {
                        if(time() >= strtotime('13:00:00')) {
                            if(date('Y-m-d') == $request->morbidityMonth) {
                                $set_mm = date('Y-m-d', strtotime('+1 Day'));
                            }
                            else {
                                $set_mm = $request->morbidityMonth;
                            }
                        }
                        else {
                            $set_mm = $request->morbidityMonth;
                        }
                    }
                    else {
                        $set_mm = $request->morbidityMonth;
                    }
                }
                else {
                    $set_mm = $request->morbidityMonth;
                }

                if($rec->morbidityMonth != $set_mm) {
                    if(time() >= strtotime('13:00:00')) {
                        $set_mt = date('Y-m-d 08:00:00', strtotime('+1 Day'));
                    }
                    else {
                        $set_mt = $set_mm.' '.date('H:i:s');
                    }
                }
                else {
                    $set_mt = $rec->morbidityTime;
                }

                //Auto Change Date Reported Based on Date Released
                if($currentClassi != 'Confirmed' && $caseClassi == 'Confirmed') {
                    if(!is_null($request->testDateCollected2)) {
                        if(!is_null($request->testDateReleased2)) {
                            $set_dr = $request->testDateReleased2;
                        }
                        else {
                            $set_dr = $request->dateReported;
                        }
                    }
                    else {
                        if(!is_null($request->testDateReleased1)) {
                            $set_dr = $request->testDateReleased1;
                        }
                        else {
                            $set_dr = $request->dateReported;
                        }   
                    }
                }
                else {
                    $set_dr = $request->dateReported;
                }

                //Block Re-infection if di pa lagpas ng 90 Days
                if($currentClassi != 'Confirmed' && $caseClassi == 'Confirmed' && $request->outcomeCondition != 'Died') {
                    $confirmed_search = Forms::where('status', 'approved')
                    ->where('id', '!=', $id)
                    ->where('records_id', $rec->records->id)
                    ->where('caseClassification', 'Confirmed')
                    ->orderBy('created_at', 'DESC')
                    ->first();
                    if($confirmed_search) {
                        $sDate = Carbon::parse($confirmed_search->dateReported);
                        $now = Carbon::parse(date('Y-m-d'));

                        $diffInDays = $sDate->diffInDays($now);

                        if($diffInDays <= 90) {
                            return back()
                            ->withInput()
                            ->with('msg', 'Warning: The patient (#'.$rec->records->id.' - '.$rec->records->getName().') has existing Confirmed Case that is still not 90 days old. Therefore, your submission is blocked.')
                            ->with('msgType', 'warning');
                        }
                    }
                }

                //Auto Recovered if Lagpas na sa Quarantine Period
                if($currentClassi != 'Confirmed' && $caseClassi == 'Confirmed' && $request->outcomeCondition != 'Died') {
                    $dateToday = Carbon::parse(date('Y-m-d'));
                    
                    if($request->dispositionType != 6 && $request->dispositionType != 7) {
                        if(!is_null($request->testType2)) {
                            $swabDateCollected = $request->testDateCollected2;
                        }
                        else {
                            $swabDateCollected = $request->testDateCollected1;
                        }

                        if($request->dispositionType == 1 || $request->healthStatus == 'Severe' || $request->healthStatus == 'Critical') {
                            $daysToRecover = 21;
                        }
                        else {
                            if(!is_null($rec->records->vaccinationDate2)) {
                                $date1 = Carbon::parse($rec->records->vaccinationDate2);
                                $days_diff = $date1->diffInDays($dateToday);
        
                                if($days_diff >= 14) {
                                    $daysToRecover = 7;
                                }
                                else {
                                    $daysToRecover = 10;
                                }
                            }
                            else {
                                if($rec->records->vaccinationName1 == 'JANSSEN') {
                                    $date1 = Carbon::parse($rec->records->vaccinationDate1);
                                    $days_diff = $date1->diffInDays($dateToday);

                                    if($days_diff >= 14) {
                                        $daysToRecover = 7;
                                    }
                                    else {
                                        $daysToRecover = 10;
                                    }
                                }
                                else {
                                    $daysToRecover = 10;
                                }
                            }
                        }

                        $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                        $diff = $startDate->diffInDays($dateToday);
                        if($diff >= $daysToRecover) {
                            $auto_outcome = 'Recovered';
                            $auto_outcome_recovered_date = Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('Y-m-d');
                            //$auto_outcome_recovered_date = date('Y-m-d');

                            $add_note = 'Note: The patient CIF was automatically moved to Recovered Cases because the Quarantine Period is already over.';
                        }
                        else {
                            $auto_outcome = $request->outcomeCondition;
                            $auto_outcome_recovered_date = $request->outcomeRecovDate;
                        }
                    }
                    else {
                        $auto_outcome = $request->outcomeCondition;
                        $auto_outcome_recovered_date = $request->outcomeRecovDate;
                    }
                }
                else {
                    $auto_outcome = $request->outcomeCondition;
                    $auto_outcome_recovered_date = $request->outcomeRecovDate;
                }

                //Disobedient Admin Checker
                if($rec->is_disobedient == 1) {
                    if(auth()->user()->ifTopAdmin()) {
                        $form_is_disobedient = ($request->is_disobedient) ? 1 : 0;
                        $form_disobedient_remarks = ($request->is_disobedient) ? $request->disobedient_remarks : NULL;
                    }
                    else {
                        $form_is_disobedient = $rec->is_disobedient;
                        $form_disobedient_remarks = $rec->disobedient_remarks;
                    }
                }
                else {
                    $form_is_disobedient = ($request->is_disobedient) ? 1 : 0;
                    $form_disobedient_remarks = ($request->is_disobedient) ? $request->disobedient_remarks : NULL;
                }
                
                if($proceed == 1) {
                    if($set_mm == date('Y-m-d') && $caseClassi == 'Confirmed' && time() >= strtotime('16:00:00')) {
                        return back()
                        ->withInput()
                        ->with('msg', 'Warning: Encoding and Editing Active Cases only allowed until 4PM - 12AM Daily. You can change the Morbidity Month to Tomorrow ('.date('m/d/Y', strtotime('+1 Day')).') so that it could be encoded for tomorrow.')
                        ->with('msgType', 'warning');
                    }
                    else {
                        $form = Forms::where('id', $id)->update([
                            'reinfected' => ($request->reinfected || $autoreinfect == 1) ? 1 : 0,
                            'morbidityMonth' => $set_mm,
                            'morbidityTime' => $set_mt,
                            'dateReported' => $set_dr,
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
                            'ccType' => ($request->pType == 'CLOSE CONTACT') ? $request->ccType : NULL,
                            'ccid_list' => (!is_null($request->ccid_list)) ? implode(",", $request->ccid_list) : NULL,
                            'isForHospitalization' => $request->isForHospitalization,
                            'testingCat' => implode(",", $testCat),
                            'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                            'dateOfFirstConsult' => $request->dateOfFirstConsult,
                            'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
                                
                            'dispoType' => $request->dispositionType,
                            'dispoName' => $request->dispositionName,
                            'dispoDate' => $request->dispositionDate,
                            'healthStatus' => $request->healthStatus,
                            'caseClassification' => $caseClassi,
                            'date_of_positive' => ($caseClassi == 'Confirmed') ? $set_dr : NULL,
                            'confirmedVariantName' => $request->confirmedVariantName,
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
                            //'antigenKit1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                            'antigen_id1' => ($request->testType1 == "ANTIGEN") ? $request->antigen_id1 : NULL,
                            'antigenLotNo1' => ($request->testType1 == "ANTIGEN" && !is_null($request->antigenLotNo1)) ? mb_strtoupper($request->antigenLotNo1) : NULL,
                            'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                            'testResult1' => $request->testResult1,
                            'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
                
                            'testDateCollected2' => $request->testDateCollected2,
                            'oniTimeCollected2' => $oniTimeFinal2,
                            'testDateReleased2' => $request->testDateReleased2,
                            'testLaboratory2' => $request->testLaboratory2,
                            'testType2' => ($request->testType2 != "N/A") ? $request->testType2 : NULL,
                            'testTypeAntigenRemarks2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                            //'antigenKit2' => ($request->testType2 == "ANTIGEN") ? mb_strtoupper($request->antigenKit2) : NULL,
                            'antigen_id2' => ($request->testType2 == "ANTIGEN") ? $request->antigen_id2 : NULL,
                            'antigenLotNo2' => ($request->testType2 == "ANTIGEN" && !is_null($request->antigenLotNo2)) ? mb_strtoupper($request->antigenLotNo2) : NULL,
                            'testTypeOtherRemarks2' => ($request->testType2 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks2) : NULL,
                            'testResult2' => ($request->testType2 != "N/A") ? $request->testResult2 : NULL,
                            'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
                
                            'outcomeCondition' => $auto_outcome,
                            'outcomeRecovDate' => $auto_outcome_recovered_date,
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

                            'is_disobedient' => $form_is_disobedient,
                            'disobedient_remarks' => $form_disobedient_remarks,
                        ]);
            
                        if(request()->input('fromView') && request()->input('sdate') && request()->input('edate')) {
                            return redirect(route('forms.index')."?view=".request()->input('fromView')."&sdate=".request()->input('sdate')."&edate=".request()->input('edate')."")
                            ->with('status', "CIF for ".$rec->records->getName()." (#".$rec->records->id.") has been updated successfully.")
                            ->with('statustype', 'success')
                            ->with('add_note', (isset($add_note)) ? $add_note : NULL);
                        }
                        else {
                            return redirect()->action([FormsController::class, 'index'])
                            ->with('status', "CIF for ".$rec->records->getName()." (#".$rec->records->id.") has been updated successfully.")
                            ->with('statustype', 'success')
                            ->with('add_note', (isset($add_note)) ? $add_note : NULL);
                        }
                    }
                }
                else {
                    return redirect()->action([FormsController::class, 'index'])
                    ->with('status', 'Double Entry Detected! Edit Error: CIF Record for '.$rec->records->getName()." already exists at ".date('m/d/Y', strtotime($request->testDateCollected1)))
                    ->with('statustype', 'danger');
                }
            }
            else {
                return back()
                ->withInput()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgType', 'warning');
            }
        }
        else {
            return back()
            ->withInput()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgType', 'warning');
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
        if(auth()->user()->ifTopAdmin()) {
            $form->delete();

            return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF of Patient ['.$form->records->getName().' #'.$form->records->id.'] has been deleted successfully.')->with('statustype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function reswab($id) {
        $record = Records::findOrFail($id);
        $recentcif = Forms::where('records_id', $record->id)->orderBy('created_at', 'DESC')->first();
        
        if(!$recentcif) {
            return redirect()->route('forms.index')
            ->with('status', 'You are not allowed to do that.')
            ->with('statustype', 'warning');
        }

        if($recentcif->outcomeCondition == 'Recovered' || $recentcif->caseClassification == 'Non-COVID-19 Case') {
            $interviewers = Interviewers::where('enabled', 1)->orderBy('lname', 'asc')->get();
                
            $countries = new Countries();
            $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
            $all = $countries->all()->pluck('name.common')->toArray();

            //Positive Encoding Cutoff indicator
            if(time() >= strtotime('16:00:00')) {
                $is_cutoff = true;
            }
            else {
                $is_cutoff = false;
            }

            //Get Antigen List
            $antigen_list = Antigen::orderBy('antigenKitShortName', 'ASC')->get();

            //Adjust Max Date of Swab
            if(date('m') == 01) {
                $mindate = date('Y-12-01', strtotime('-1 Year'));
                $enddate = date('Y-12-31');
            }
            else if(date('m') == 12) {
                $mindate = date('Y-01-01');
                $enddate = date('Y-01-31', strtotime('+1 Year'));
            }
            else {
                $mindate = date('Y-01-01');
                $enddate = date('Y-12-31');
            }

            return view('formscreate', [
                'countries' => $all,
                'records' => $record,
                'interviewers' => $interviewers,
                'id' => $id,
                'is_cutoff' => $is_cutoff,
                'antigen_list' => $antigen_list,
                'mindate' => $mindate,
                'enddate' => $enddate,
            ]);
        }
        else {
            return redirect()->route('forms.index')
            ->with('status', 'You are not allowed to do that.')
            ->with('statustype', 'warning');
        }
    }

    public function viewExistingForm($id) {
        $form = Forms::findOrFail($id);

        $l = LinelistSubs::where('records_id', $form->records->id)->orderBy('created_at', 'DESC')->first();

        return view('forms_existing', ['form' => $form, 'l' => $l]);
    }

    public function generateMedCert(Request $request, $form_id) {
        $data = Forms::findOrFail($form_id);

        $number = date('j');

        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if (($number %100) >= 11 && ($number%100) <= 13) {
            $abbreviation = $number. 'th';
        }
        else {
            $abbreviation = $number. $ends[$number % 10];
        }

        if($data->pType == 'CLOSE CONTACT') {
            $pui = true;
        }
        else {
            $pui = false;
        }

        if(!is_null($data->SAS)) {
            $pum = true;
        }
        else {
            $pum = false;
        }

        if($request->submit == 'medcert1') {
            return view('medcert', [
                'data' => $data,
                'req' => $request,
                'cardinal' => $abbreviation,
                'pui' => $pui,
                'pum' => $pum,
            ]);
        }
        else {
            if($data->dispoType == 1) {
                $admitted = 'Hospital';
            }
            else if($data->dispoType == 2) {
                $admitted = 'Isolation Facility';
            }
            else if($data->dispoType == 3 || $data->dispoType == 4) {
                $admitted = 'Home Quarantine';
            }
            else if($data->dispoType == 5) {
                $admitted = 'Others';
            }
            else if($data->dispoType == 6) {
                $admitted = 'Gen. Trias Isolation Facility (Brgy. Santiago)';
            }
            else if($data->dispoType == 7) {
                $admitted = 'Gen. Trias Isolation Facility (Brgy. Javalera)';
            }

            return view('medcert2', [
                'data' => $data,
                'req' => $request,
                'cardinal' => $abbreviation,
                'pui' => $pui,
                'pum' => $pum,
                'whonote' => ($request->whonote == 1) ? $data->interviewerName : mb_strtoupper($request->whonote_other),
                'admitted' => $admitted,
            ]);
        }
    }
}
