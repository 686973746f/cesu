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
        foreach($request->bu as $item) {
            $form = Forms::findOrFail($item['forms_id']);

            if(!is_null($form->testDateCollected2)) {
                $form->testResult2 = (!is_null($item['testResult'])) ? $item['testResult'] : $form->testResult2;
                $form->testDateReleased2 = (!is_null($item['dateReleased'])) ? $item['dateReleased'] : $form->testDateReleased2;
                $form->oniTimeCollected2 = (!is_null($item['timeReleased'])) ? $item['timeReleased'] : $form->oniTimeCollected2;
            }
            else {
                $form->testResult1 = (!is_null($item['testResult'])) ? $item['testResult'] : $form->testResult1;
                $form->testDateReleased1 = (!is_null($item['dateReleased'])) ? $item['dateReleased'] : $form->testDateReleased1;
                $form->oniTimeCollected1 = (!is_null($item['timeReleased'])) ? $item['timeReleased'] : $form->oniTimeCollected1;
            }

            $form->dispoType = (!is_null($item['dispositionType'])) ? $item['dispositionType'] : $form->dispoType;
            if(!is_null($item['dispositionType'])) {
                $form->dispoName = $item['dispositionName'];
                $form->dispoDate = $item['dispositionDate'];
            }
            else {
                $form->dispoName = $form->dispoName;
                $form->dispoDate = $form->dispoDate;
            }
            
            $form->outcomeCondition = (!is_null($item['outcomeCondition'])) ? $item['outcomeCondition'] : $form->outcomeCondition;
            $form->outcomeRecovDate = (!is_null($item['dateRecovered']) && $item['outcomeCondition'] == 'Recovered') ? $item['dateRecovered'] : $form->outcomeRecovDate;
            $form->outcomeDeathDate = (!is_null($item['outcomeDeathDate']) && $item['outcomeCondition'] == 'Died') ? $item['outcomeDeathDate'] : $form->outcomeDeathDate;
            $form->deathImmeCause = (!is_null($item['deathImmeCause']) && $item['outcomeCondition'] == 'Died') ? $item['deathImmeCause'] : $form->deathImmeCause;
            $form->deathAnteCause = (!is_null($item['deathAnteCause']) && $item['outcomeCondition'] == 'Died') ? $item['deathAnteCause'] : $form->deathAnteCause;
            $form->deathUndeCause = (!is_null($item['deathUndeCause']) && $item['outcomeCondition'] == 'Died') ? $item['deathUndeCause'] : $form->deathUndeCause;
            $form->contriCondi = (!is_null($item['contriCondi']) && $item['outcomeCondition'] == 'Died') ? $item['contriCondi'] : $form->contriCondi;

            if($form->isDirty()) {
                $form->save();
            }
        }

        return redirect()->action([BulkUpdateController::class, 'viewBulkUpdate'])->with('msg', 'Bulk Update Processed Successfully.')->with('msgtype', 'success');
    }
}
