<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SubdivisionImport;
use Maatwebsite\Excel\Facades\Excel;

class SubdivisionController extends Controller
{
    public function index() {
        return view('subdivision.index');
    }

    public function store(Request $r) {

    }

    public function import(Request $r) {
        Excel::import(new SubdivisionImport(), $r->sfile);

        return redirect()->route('subdivision_index')
        ->with('msg', 'Import Successful.')
        ->with('msgtype', 'success');
    }
}
