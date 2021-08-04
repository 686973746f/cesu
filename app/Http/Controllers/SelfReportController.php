<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use App\Http\Requests\SelfReportValidationRequest;

class SelfReportController extends Controller
{
    public function index() {
        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        return view('selfreport_index', ['countries' => $all]);
    }

    public function store(SelfReportValidationRequest $request) {
        
    }
}
