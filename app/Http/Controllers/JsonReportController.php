<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Forms;
use Illuminate\Http\Request;

class JsonReportController extends Controller
{
    public function totalCases() {

    }

    public function dailyNewCases() {
        
    }

    public function casesDistribution() {

    }

    public function brgyCases() {
        $arr = [];

        $list = Brgy::where('city_id', 1)
        ->where('displayInList', '1')
        ->orderBy('brgyName', 'ASC')->get();

        foreach($list as $item) {
            /*
            $confirmedCases = Forms::with('records')
            ->whereHas('records', function($q) use ($item) {
                $q->where('address_brgy', $item->brgyName);
            })->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')->count();
            */

            $activeCases = Forms::with('records')
            ->whereHas('records', function($q) use ($item) {
                $q->where('address_brgy', $item->brgyName);
            })->where('status', 'approved')
            ->where('outcomeCondition', 'Active')
            ->where('caseClassification', 'Confirmed')->count();

            $deaths = Forms::with('records')
            ->whereHas('records', function($q) use ($item) {
                $q->where('address_brgy', $item->brgyName);
            })->where('status', 'approved')
            ->where('outcomeCondition', 'Died')->count();

            $recovered = Forms::with('records')
            ->whereHas('records', function($q) use ($item) {
                $q->where('address_brgy', $item->brgyName);
            })->where('status', 'approved')
            ->where('outcomeCondition', 'Recovered')->count();

            $confirmedCases = ($activeCases + $deaths + $recovered);

            array_push($arr, [
                'brgyName' => $item->brgyName,
                'numOfConfirmedCases' => $confirmedCases,
                'numOfActiveCases' => $activeCases,
                'numOfDeaths' => $deaths,
                'numOfRecoveries' => $recovered,
            ]);
        }
        
        return response()->json($arr);
    }

    public function genderBreakdown() {
        $arr = [];

        $male = Forms::with('records')
        ->whereHas('records', function($q) use ($item) {
            $q->where('gender', 'MALE');
        })->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->count();

        $female = Forms::with('records')
        ->whereHas('records', function($q) use ($item) {
            $q->where('gender', 'FEMALE');
        })->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->count();

        array_push($arr, [
            'male' => $male,
            'female' => $female,
            'total' => ($male + $female),
        ]);

        return response()->json($arr);
    }

    public function conditionBreakdown() {

    }
}
