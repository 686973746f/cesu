<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecondaryTertiaryRecords;

class SecondaryTertiaryRecordsController extends Controller
{
    public function index() {
        $list = SecondaryTertiaryRecords::paginate(10);

        return view('sc_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        return view('sc_create');
    }

    public function store(Request $request) {

    }

    public function edit($id) {

    }

    public function update(Request $request) {
        
    }

    public function delete($id) {

    }
}
