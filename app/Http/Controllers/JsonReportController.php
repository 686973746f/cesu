<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Illuminate\Http\Request;

class JsonReportController extends Controller
{
    public function brgyCases() {
        $arr = [];

        $list = Brgy::where('city_id', 1)
        ->where('displayInList', '1')
        ->orderBy('brgyName', 'ASC')->get();

        foreach($list as $item) {
            $confirmedCases = Forms::with('records')
            ->whereHas('records', function($q) use ($item) {
                $q->where('address_brgy', $item->brgyName);
            })->where('status', 'approved')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')->count();

            array_push($arr, [
                'brgyName' => $item->brgyName,
                'numOfConfirmedCases' => $confirmedCases,
            ]);
        }
        
        return response()->json($arr);
    }
}
