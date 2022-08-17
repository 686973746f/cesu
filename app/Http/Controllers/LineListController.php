<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Forms;
use App\Models\Records;
use App\Models\LinelistSubs;
use Illuminate\Http\Request;
use App\Models\LinelistMasters;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        if($request->isOverride == 1) {
            session(['set_override' => 1]);
        }
        else {
            session(['set_override' => 0]);
        }

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

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = $request->q;
            
            if($request->isOverride == 1) {
                if(auth()->user()->isCesuAccount()) {
                    $query = Forms::whereHas('records', function ($q) use ($search) {
                        $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere('id', $search);
                    })
                    ->whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                    ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo])
                    ->get();
                }
                else {
                    if(auth()->user()->isBrgyAccount()) {
                        $query = Forms::with('user')
                        ->whereHas('records', function ($q) use ($search) {
                            $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere('id', $search);
                        })
                        ->where(function ($q) use($request) {
                            $q->whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                            ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo]);
                        })
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
                        ->get();
                    }
                    else if(auth()->user()->isCompanyAccount()) {
                        $query = Forms::with('user')
                        ->whereHas('records', function ($q) use ($search) {
                            $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere('id', $search);
                        })
                        ->where(function ($q) use($request) {
                            $q->whereBetween('testDateCollected1', [$request->sFrom, $request->sTo])
                            ->orWhereBetween('testDateCollected2', [$request->sFrom, $request->sTo]);
                        })
                        ->where(function ($sq) {
                            $sq->whereHas('user', function ($query) {
                                $query->where('company_id', auth()->user()->company_id);
                            })
                            ->orWhereHas('records', function ($query) {
                                $query->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                            });
                        })
                        ->get();
                    }
                }
            }
            else {
                if(auth()->user()->isCesuAccount()) {
                    $query = Forms::whereHas('records', function ($q) use ($search) {
                        $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                        ->orWhere('id', $search);
                    })
                    ->where(function ($query) {
                        $query->where('testDateCollected1', date('Y-m-d'))
                        ->orWhere('testDateCollected2', date('Y-m-d'));
                    })
                    ->get();
                }
                else {
                    if(auth()->user()->isBrgyAccount()) {
                        $query = Forms::with('user')
                        ->whereHas('records', function ($q) use ($search) {
                            $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere('id', $search);
                        })
                        ->where(function ($query) {
                            $query->where('testDateCollected1', date('Y-m-d'))
                            ->orWhere('testDateCollected2', date('Y-m-d'));
                        })
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
                        ->get();
                    }
                    else if(auth()->user()->isCompanyAccount()) {
                        $query = Forms::with('user')
                        ->whereHas('records', function ($q) use ($search) {
                            $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                            ->orWhere('id', $search);
                        })
                        ->where(function ($query) {
                            $query->where('testDateCollected1', date('Y-m-d'))
                            ->orWhere('testDateCollected2', date('Y-m-d'));
                        })
                        ->where(function ($sq) {
                            $sq->whereHas('user', function ($query) {
                                $query->where('company_id', auth()->user()->company_id);
                            })
                            ->orWhereHas('records', function ($query) {
                                $query->where('sharedOnId', 'LIKE', '%'.auth()->user()->id);
                            });
                        })
                        ->get();
                    }
                }
            }

            foreach($query as $item) {
                array_push($list, [
                    'id' => $item->records->id,
                    'text' => '#'.$item->records->id.' - '.$item->records->getName().' | '.$item->records->getAge().'/'.substr($item->records->gender,0,1).' | '.date('m/d/Y', strtotime($item->records->bdate)),
                ]);
            }
        }

        return response()->json($list);
    }
    
    public function print($link, $id) {
        ini_set('max_execution_time', 600);

        $setPaper = request()->input('s');
        $details = LineListMasters::findOrFail($id);
        $list = LineListSubs::where('linelist_masters_id', $details->id)->orderBy('specNo', 'asc')->get();

        if($link == 'lasalle') {
            $pdf = PDF::loadView('lasalle_pdf', ['details' => $details, 'list' => $list, 'size' => $setPaper])->setPaper($setPaper, 'landscape');
            return $pdf->download('LINELIST_LASALLE_'.date('m_d_Y', strtotime($details->created_at)).'.pdf');
        }
        else if($link == 'oni') {
            //return view('oni_pdf', ['details' => $details, 'list' => $list, 'size' => $setPaper]);

            //New ONI Linelist Submission
            $spreadsheet = IOFactory::load(storage_path('ONI_LINELIST.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();

            $num = 3;

            foreach ($list as $l) {
                $sheet->setCellValue('A'.$num, $l->records->lname);
                $sheet->setCellValue('B'.$num, $l->records->fname);
                $sheet->setCellValue('C'.$num, (!is_null($l->records->mname)) ? $l->records->mname : 'N/A');
                $sheet->setCellValue('D'.$num, date('m/d/Y', strtotime($l->records->bdate)));
                $sheet->setCellValue('E'.$num, $l->records->gender);
                $sheet->setCellValue('F'.$num, $l->records->address_province);
                $sheet->setCellValue('G'.$num, $l->records->address_city);
                $sheet->setCellValue('H'.$num, $l->records->address_brgy);
                $sheet->setCellValue('I'.$num, 'CHO GENERAL TRIAS');
                $sheet->setCellValue('J'.$num, (!is_null($l->forms()->dateOnsetOfIllness)) ? date('m/d/Y', strtotime($l->forms()->dateOnsetOfIllness)) : 'N/A');
                $sheet->setCellValue('K'.$num, $l->records->address_brgy);
                $sheet->setCellValue('L'.$num, date('m/d/Y', strtotime($l->forms()->testDateCollected1)));
                $sheet->setCellValue('M'.$num, $l->forms()->testType1);
                $sheet->setCellValue('N'.$num, '1ST');
                $sheet->setCellValue('O'.$num, $l->records->mobile);
                $sheet->setCellValue('P'.$num, $l->forms()->healthStatus);
                $sheet->setCellValue('Q'.$num, ($l->forms()->isOFW == 1) ? 'Y' : 'N');
                $sheet->setCellValue('R'.$num, 'RT-PCR');

                $num++;
            }

            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode('ONI_LINELIST_'.date('m_d_Y').'.xlsx').'"');
            $writer->save('php://output');
        }
    }

    public function oniStore(Request $request) {

        $master = $request->user()->linelistmaster()->create([
            'type' => 1, //ONI = 1, LaSalle = 2
            'dru' => $request->dru,
            'contactPerson' => $request->contactPerson,
            'contactMobile' => $request->contactMobile,
            'is_override' => session('set_override'),
        ]);

        for($i=0;$i<count($request->user);$i++) {
            if(!is_null($request->timeCollected[$i])) {
                $oniTime = $request->timeCollected[$i];
            }
            else {
                $search = Forms::where('records_id', $request->user[$i])->orderBy('created_at', 'DESC')->first();

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
        })->update([
            'caseClassification' => 'Probable',
            'isPresentOnSwabDay' => 1
        ]);

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
            'laSallePreparedByDate' => date('Y-m-d H:i:s', strtotime($request->laSallePreparedByDate." ".$request->laSallePreparedByTime)),
            'is_override' => session('set_override'),
        ]);

        for($i=0;$i<count($request->user);$i++) {
            //Count number of Swab based on the number of linelist done to the record id
            $rctr = LinelistSubs::whereHas('linelistmaster', function ($q) {
                $q->where('type', 2)
                ->where('is_override', 0);
            })
            ->where('records_id', $request->user[$i])
            ->count();
            
            if($rctr <= 0) {
                $fcount = '1ST'; 
            }
            else if($rctr == 1) {
                $fcount = '2ND'; 
            }
            else if($rctr >= 2) {
                $fcount = '3RD'; 
            }

            $query = LinelistSubs::create([
                'linelist_masters_id' => $master->id,
                'specNo' => $i+1,
                'records_id' => $request->user[$i],
                'dateAndTimeCollected' => $request->dateCollected[$i]." ".$request->timeCollected[$i],
                'remarks' => $fcount,
            ]);
        }

        $update = Forms::whereIn('records_id', $request->user)
        ->whereIn('testDateCollected1', array_unique($request->dateCollected))
        ->where(function ($query) use ($request) {
            $query->whereNull('testDateCollected2')
            ->orWhereIn('testDateCollected2', array_unique($request->dateCollected));
        })->update([
            'caseClassification' => 'Probable',
            'isPresentOnSwabDay' => 1
        ]);

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
}
