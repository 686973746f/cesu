<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $list = Forms::all();

        return view('reports_home', ['list' => $list]);
    }
}
