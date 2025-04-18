<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SubdivisionImport;
use App\Models\Subdivision;
use App\Models\SubdivisionV2;
use Maatwebsite\Excel\Facades\Excel;

class SubdivisionController extends Controller
{
    public function index() {
        return view('subdivision.index');
    }

    public function getSubdivisions($brgy_id) {
        $subdivisions = Subdivision::where('brgy_id', $brgy_id)->pluck('subdName', 'id');
        return response()->json($subdivisions);
    }

    public function store(Request $r) {

    }

    public function import(Request $r) {
        Excel::import(new SubdivisionImport(), $r->sfile);

        return redirect()->route('subdivision_index')
        ->with('msg', 'Import Successful.')
        ->with('msgtype', 'success');
    }

    public function getSubdivisionsV2($brgy_id) {
        $subdivisions = SubdivisionV2::where('brgy_id', $brgy_id)->pluck('name', 'id');

        return response()->json($subdivisions);
    }
}
