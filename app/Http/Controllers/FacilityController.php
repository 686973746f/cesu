<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index() {
        //Facility Account
        $currentWeek = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('W');
        
        $list = Forms::where('dispoType', 6)
        ->where('status', 'approved')
        ->where('caseClassification', 'Confirmed')
        ->where('outcomeCondition', 'Active')
        ->get();
        
        $list = $list->sortBy('records.lname');

        return view('home_facility', ['currentWeek' => $currentWeek, 'list' => $list]);
    }
}
