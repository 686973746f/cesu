<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $list1 = Forms::where('caseClassification', 'PROBABLE')->get();
        
        $list2 = Forms::where('caseClassification', 'SUSPECT')->get();

        $list3 = Forms::where('caseClassification', 'CONFIRMED')->get(); 

        $list4 = Forms::where('caseClassification', 'NON-COVID-19 CASE')->get();

        return view('reports_home', ['list1' => $list1, 'list2' => $list2, 'list3' => $list3, 'list4' => $list4]);
    }
}
