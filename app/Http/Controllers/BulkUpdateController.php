<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkUpdateController extends Controller
{
    public function viewBulkUpdate() {
        if(time() >= strtotime('16:00:00')) {
            return redirect()->route('home')
            ->with('status', 'Feature was disabled from 4PM to 12AM Onwards Daily.')
            ->with('statustype', 'warning');
        }
        else {
            return view('bulkupdate_index');
        }
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

                if($item->dispoType == 1) {
                    $adm = 'ADMITTED AT HOSPITAL';
                }
                else if($item->dispoType == 2) {
                    $adm = 'ADMITTED AT ISOLATION FACILITY';
                }
                else if($item->dispoType == 3) {
                    $adm = 'HOME QUARANTINE';
                }
                else if($item->dispoType == 4) {
                    $adm = 'DISCHARGED TO HOME';
                }
                else {
                    $adm = 'OTHERS';
                }
    
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->records->id.' - '.$item->records->getName().' ('.$item->getType().' - MM: '.date('m/d/Y', strtotime($item->morbidityMonth)).' - DR: '.date('m/d/Y', strtotime($item->dateReported)).') | '.$item->records->getAge().'/'.substr($item->records->gender,0,1).' | '.date('m/d/Y', strtotime($item->records->bdate)).' | '.$ph.' - Recent Test: '.$testType.' @ '.$testDate.' ('.$testResult.') | Classification: '.$item->caseClassification.' | Outcome: '.$item->outcomeCondition.' | Quarantine Status: '.$adm,
                ]);
            }
        }

        return response()->json($list);
    }

    public function store(Request $request) {
        foreach($request->bu as $item) {
            $form = Forms::findOrFail($item['forms_id']);

            //Update Case Classification if Positive or Negative
            if(!is_null($item['testResult'])) {
                if($item['testResult'] == 'POSITIVE') {
                    $form->caseClassification = 'Confirmed';
                }
                else if($item['testResult'] == 'NEGATIVE') {
                    $form->caseClassification = 'Non-COVID-19 Case';
                }

                if(!is_null($form->testDateCollected2)) {
                    $form->testResult2 = $item['testResult'];
                    $form->testDateReleased2 = $item['dateReleased'];
                    $form->oniTimeCollected2 = (!is_null($item['timeReleased'])) ? $item['timeReleased'] : $form->oniTimeCollected2;
                }
                else {
                    $form->testResult1 = $item['testResult'];
                    $form->testDateReleased1 = $item['dateReleased'];
                    $form->oniTimeCollected1 = (!is_null($item['timeReleased'])) ? $item['timeReleased'] : $form->oniTimeCollected1;
                }
            }

            //Morbidity Month
            if(!is_null($item['morbidityMonth'])) {
                $form->morbidityMonth = $item['morbidityMonth'];
            }

            //Date Reported
            if(!is_null($item['dateReported'])) {
                $form->dateReported = date('Y-m-d 00:00:00', strtotime($item['dateReported']));
            }

            if(!is_null($item['dispositionType'])) {
                $form->dispoType = $item['dispositionType'];
                $form->dispoName = $item['dispositionName'];
                $form->dispoDate = $item['dispositionDate'];
            }

            //Outcome
            if(!is_null($item['outcomeCondition'])) {
                $form->outcomeCondition = $item['outcomeCondition'];
                $form->caseClassification = 'Confirmed'; //Auto update sa Confirmed either Recovered or Died

                if($item['outcomeCondition'] == 'Recovered') {
                    $form->outcomeRecovDate = $item['dateRecovered'];
                }
                else if($item['outcomeCondition'] == 'Died') {
                    $form->outcomeDeathDate = $item['outcomeDeathDate'];
                    $form->deathImmeCause = $item['deathImmeCause'];
                    $form->deathAnteCause = (!is_null($item['deathAnteCause']) && $item['outcomeCondition'] == 'Died') ? $item['deathAnteCause'] : $form->deathAnteCause;
                    $form->deathUndeCause = (!is_null($item['deathUndeCause']) && $item['outcomeCondition'] == 'Died') ? $item['deathUndeCause'] : $form->deathUndeCause;
                    $form->contriCondi = (!is_null($item['contriCondi']) && $item['outcomeCondition'] == 'Died') ? $item['contriCondi'] : $form->contriCondi;
                }
            }

            if($form->isDirty()) {
                $form->save();
            }
        }

        return redirect()->action([BulkUpdateController::class, 'viewBulkUpdate'])
        ->with('msg', 'Bulk Update Processed Successfully.')
        ->with('msgtype', 'success');
    }
}
