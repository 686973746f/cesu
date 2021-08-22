<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Forms;
use App\Models\Records;
use App\Models\LinelistSubs;
use Illuminate\Http\Request;
use App\Models\LinelistMasters;
use Illuminate\Support\Facades\DB;

class LineListController extends Controller
{
    public function index() {
        if(request()->input('q')) {
            if(auth()->user()->isCesuAccount()) {
                $list = LinelistSubs::with('records')
                ->whereHas('records', function ($query) {
                    $query->where(DB::raw('CONCAT(lname, " ",fname, " ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                    ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%");
                })->orderby('created_at', 'desc')->paginate(10);
            }
            else {
                if(auth()->user()->isBrgyAccount()) {

                }
                else if(auth()->user()->isCompanyAccount()) {

                }
            }
        }
        else {
            if(auth()->user()->isCesuAccount()) {
                $list = LinelistMasters::orderby('created_at', 'desc')->paginate(10);
            }
            else {
                if(auth()->user()->isBrgyAccount()) {
                    $list = LinelistMasters::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('brgy_id', auth()->user()->brgy_id);
                    })->orderby('created_at', 'desc')->paginate(10);
                }
                else if(auth()->user()->isCompanyAccount()) {
                    $list = LinelistMasters::with('user')
                    ->whereHas('user', function ($query) {
                        $query->where('company_id', auth()->user()->company_id);
                    })->orderby('created_at', 'desc')->paginate(10);
                }
            }
        }

        return view('linelist_index', ['list' => $list]);
    }

    public function createLineList(Request $request) {
        if($request->submit == 1) {
            //LaSalle
            return view('linelist_createlasalle', [
                'isOverride' => $request->isOverride,
                'sFrom' => ($request->isOverride == 1) ? $request->sFrom : date('Y-m-d'),
                'sTo' => ($request->isOverride == 1) ? $request->sTo : date('Y-m-d'),
            ]);
        }
        else {
            //ONI
            return view('linelist_createoni', [
                'isOverride' => $request->isOverride,
                'sFrom' => ($request->isOverride == 1) ? $request->sFrom : date('Y-m-d'),
                'sTo' => ($request->isOverride == 1) ? $request->sTo : date('Y-m-d'),
            ]);
        }
    }

    public function ajaxLineList(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) != 0) {
            $search = $request->q;
            
            if($request->isOverride == 1) {
                if(auth()->user()->isCesuAccount()) {
                    $query = Forms::whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                    ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo])
                    ->pluck('records_id')
                    ->toArray();
                }
                else {
                    if(auth()->user()->isBrgyAccount()) {
                        $query = Forms::with('user')
                        ->whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                        ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo])
                        ->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })->pluck('records_id')
                        ->toArray();
                    }
                    else if(auth()->user()->isCompanyAccount()) {
                        $query = Forms::with('user')
                        ->whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                        ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo])
                        ->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })->pluck('records_id')
                        ->toArray();
                    }
                }
    
                //$query = Records::whereIn('id', $query)->orderBy('lname', 'asc')->get();
            }
            else {
                if(auth()->user()->isCesuAccount()) {
                    $query = Forms::where('testDateCollected1', date('Y-m-d'))
                    ->orWhere('testDateCollected2', date('Y-m-d'))
                    ->pluck('records_id')
                    ->toArray();
                }
                else {
                    if(auth()->user()->isBrgyAccount()) {
                        $query = Forms::with('user')
                        ->where(function ($query) {
                            $query->where('testDateCollected1', date('Y-m-d'))
                            ->orWhere('testDateCollected2', date('Y-m-d'));
                        })->whereHas('user', function ($query) {
                            $query->where('brgy_id', auth()->user()->brgy_id);
                        })->pluck('records_id')
                        ->toArray();
                    }
                    else if(auth()->user()->isCompanyAccount()) {
                        $query = Forms::with('user')
                        ->where(function ($query) {
                            $query->where('testDateCollected1', date('Y-m-d'))
                            ->orWhere('testDateCollected2', date('Y-m-d'));
                        })->whereHas('user', function ($query) {
                            $query->where('company_id', auth()->user()->company_id);
                        })->pluck('records_id')
                        ->toArray();
                    }
                }
            }

            $query = Records::whereIn('id', $query)
            ->where(function ($q) use ($search) {
                $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
            })->orderBy('lname', 'asc')->get();

            foreach($query as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => $item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                ]);
            }
        }

        return response()->json($list);
    }

    public function printoni($id) {
        //ini_set('max_execution_time', 600);

        $setPaper = request()->input('s');
        
        $details = LineListMasters::find($id);
        $list = LineListSubs::where('linelist_masters_id', $id)->orderBy('specNo', 'asc')->get();

        //$pdf = PDF::loadView('oni_pdf', ['details' => $details, 'list' => $list, 'size' => $setPaper])->setPaper($setPaper, 'landscape');
        //return $pdf->download('LINELIST_ONI_'.date('m_d_Y', strtotime($details->created_at)).'.pdf');

        return view('oni_pdf', ['details' => $details, 'list' => $list, 'size' => $setPaper]);
    }

    public function printlasalle($id) {
        ini_set('max_execution_time', 600);

        $setPaper = request()->input('s');

        $details = LineListMasters::find($id);
        $list = LineListSubs::where('linelist_masters_id', $id)->orderBy('specNo', 'asc')->get();

        $pdf = PDF::loadView('lasalle_pdf', ['details' => $details, 'list' => $list, 'size' => $setPaper])->setPaper($setPaper, 'landscape');
        return $pdf->download('LINELIST_LASALLE_'.date('m_d_Y', strtotime($details->created_at)).'.pdf');
        
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
            if(!is_null($request->timeCollected[$i])) {
                $oniTime = $request->timeCollected[$i];
            }
            else {
                $search = Forms::where('records_id', $request->user[$i])->first();

                if(!is_null($search->testDateCollected2)) {
                    $oniTime = $search->oniTimeCollected2;
                }
                else {
                    $oniTime = $search->oniTimeCollected1;
                }
            }

            $query = LinelistSubs::create([
                'linelist_masters_id' => $master->id,
                'specNo' => $i+1,
                'dateAndTimeCollected' => $request->dateCollected[$i]." ".$oniTime,
                'accessionNo' => $request->accessionNo[$i],
                'records_id' => $request->user[$i],
                'remarks' => $request->remarks[$i],
                'oniSpecType' => $request->oniSpecType[$i],
                'oniReferringHospital' => $request->oniReferringHospital[$i]
            ]);
        }

        $update = Forms::whereIn('records_id', $request->user)
        ->whereIn('testDateCollected1', array_unique($request->dateCollected))
        ->where(function ($query) use ($request) {
            $query->whereNull('testDateCollected2')
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })->update(['isPresentOnSwabDay' => 1]);

        if(auth()->user()->isCesuAccount()) {
            $update1 = Forms::whereNotIn('records_id', $request->user)
            ->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->where(function ($query) use ($request) {
                $query->whereNull('testDateCollected2')
                ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
            })
            ->where(function ($query) {
                $query->where('isPresentOnSwabDay', '!=', 1)
                ->orWhereNull('isPresentOnSwabDay');
            })
            ->where(function ($query) {
                $query->where('testType1', '!=', 'ANTIGEN')
                ->orWhere('testType2', '!=', 'ANTIGEN');
            })
            ->update(['isPresentOnSwabDay' => 0]);
        }
        else {
            if(auth()->user()->isBrgyAccount()) {
                $update1 = Forms::with('user')
                ->whereNotIn('records_id', $request->user)
                ->whereIn('testDateCollected1', array_unique($request->dateCollected))
                ->where(function ($query) use ($request) {
                    $query->whereNull('testDateCollected2')
                    ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
                })
                ->where(function ($query) {
                    $query->where('isPresentOnSwabDay', '!=', 1)
                    ->orWhereNull('isPresentOnSwabDay');
                })
                ->where(function ($query) {
                    $query->where('testType1', '!=', 'ANTIGEN')
                    ->orWhere('testType2', '!=', 'ANTIGEN');
                })
                ->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->update(['isPresentOnSwabDay' => 0]);
            }
            else if(auth()->user()->isCompanyAccount()) {
                $update1 = Forms::with('user')
                ->whereNotIn('records_id', $request->user)
                ->whereIn('testDateCollected1', array_unique($request->dateCollected))
                ->where(function ($query) use ($request) {
                    $query->whereNull('testDateCollected2')
                    ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
                })
                ->where(function ($query) {
                    $query->where('isPresentOnSwabDay', '!=', 1)
                    ->orWhereNull('isPresentOnSwabDay');
                })
                ->where(function ($query) {
                    $query->where('testType1', '!=', 'ANTIGEN')
                    ->orWhere('testType2', '!=', 'ANTIGEN');
                })
                ->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->update(['isPresentOnSwabDay' => 0]);
            }
        }

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
                'linelist_masters_id' => $master->id,
                'specNo' => $i+1,
                'records_id' => $request->user[$i],
                'dateAndTimeCollected' => $request->dateCollected[$i]." ".$request->timeCollected[$i],
                'remarks' => $request->remarks[$i],
            ]);
        }

        $update = Forms::whereIn('records_id', $request->user)
        ->whereIn('testDateCollected1', array_unique($request->dateCollected))
        ->where(function ($query) use ($request) {
            $query->whereNull('testDateCollected2')
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })->update(['isPresentOnSwabDay' => 1]);

        if(auth()->user()->isCesuAccount()) {
            $update1 = Forms::whereNotIn('records_id', $request->user)
            ->whereIn('testDateCollected1', array_unique($request->dateCollected))
            ->where(function ($query) use ($request) {
                $query->whereNull('testDateCollected2')
                ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
            })
            ->where(function ($query) {
                $query->where('isPresentOnSwabDay', '!=', 1)
                ->orWhereNull('isPresentOnSwabDay');
            })
            ->where(function ($query) {
                $query->where('testType1', '!=', 'ANTIGEN')
                ->orWhere('testType2', '!=', 'ANTIGEN');
            })
            ->update(['isPresentOnSwabDay' => 0]);
        }
        else {
            if(auth()->user()->isBrgyAccount()) {
                $update1 = Forms::with('user')
                ->whereNotIn('records_id', $request->user)
                ->whereIn('testDateCollected1', array_unique($request->dateCollected))
                ->where(function ($query) use ($request) {
                    $query->whereNull('testDateCollected2')
                    ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
                })
                ->where(function ($query) {
                    $query->where('isPresentOnSwabDay', '!=', 1)
                    ->orWhereNull('isPresentOnSwabDay');
                })
                ->where(function ($query) {
                    $query->where('testType1', '!=', 'ANTIGEN')
                    ->orWhere('testType2', '!=', 'ANTIGEN');
                })->whereHas('user', function ($query) {
                    $query->where('brgy_id', auth()->user()->brgy_id);
                })->update(['isPresentOnSwabDay' => 0]);
            }
            else if(auth()->user()->isCompanyAccount()) {
                $update1 = Forms::with('user')
                ->whereNotIn('records_id', $request->user)
                ->whereIn('testDateCollected1', array_unique($request->dateCollected))
                ->where(function ($query) use ($request) {
                    $query->whereNull('testDateCollected2')
                    ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
                })
                ->where(function ($query) {
                    $query->where('isPresentOnSwabDay', '!=', 1)
                    ->orWhereNull('isPresentOnSwabDay');
                })
                ->where(function ($query) {
                    $query->where('testType1', '!=', 'ANTIGEN')
                    ->orWhere('testType2', '!=', 'ANTIGEN');
                })->whereHas('user', function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                })->update(['isPresentOnSwabDay' => 0]);
            }
        }

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
