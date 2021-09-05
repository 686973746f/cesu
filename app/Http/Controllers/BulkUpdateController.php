<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkUpdateController extends Controller
{
    public function viewBulkUpdate() {
        return view('bulkupdate_index');
    }

    public function ajaxController(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) != 0) {
            $search = $request->q;

            $data = Forms::with('records')
            ->whereHas('records', function ($q) use ($search) {
                $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%");
            })->get();

            foreach($data as $item) {
                if(!is_null($item->testDateCollected2)) {
                    $testType = $item->testType2;
                    $testResult = $item->testResult2;
                    $testDate = date('m/d/Y', strtotime($item->testDateCollected2));
                }
                else {
                    $testType = $item->testType1;
                    $testResult = $item->testResult1;
                    $testDate = date('m/d/Y', strtotime($item->testDateCollected1));
                }

                if(!is_null($item->records->philhealth)) {
                    $ph = 'P.Health: YES';
                }
                else {
                    $ph = 'P.Health: N/A';
                }
    
                array_push($list, [
                    'id' => $item->id,
                    'text' => $item->records->getName().' ('.$item->getType().') | '.$item->records->getAge().'/'.substr($item->records->gender,0,1).' | '.date('m/d/Y', strtotime($item->records->bdate)).' | '.$ph.' - Recent Test: '.$testType.' @ '.$testDate.' ('.$testResult.')',
                ]);
            }
        }

        return response()->json($list);
    }

    public function store(Request $request) {
        dd($request);
    }
}
