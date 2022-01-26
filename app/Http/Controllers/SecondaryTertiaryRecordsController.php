<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecondaryTertiaryRecords;
use App\Http\Requests\SecondaryTertiaryRecordsValidationRequest;

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

    public function store(SecondaryTertiaryRecordsValidationRequest $request) {
        $request->validated();

        $create = $request->user()->secondaryTertiaryRecords()->create([
            'morbidityMonth' => $request->morbidityMonth,
            'dateReported' => $request->dateReported,
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
            'gender' => $request->gender,
            'bdate' => $request->bdate,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address_houseno' => $request->address_houseno,
            'address_street' => $request->address_street,
            'address_brgy' => $request->address_brgy,
            'address_city' => $request->address_city,
            'address_cityjson' => $request->address_cityjson,
            'address_province' => $request->address_province,
            'address_provincejson' => $request->address_provincejson,
            'temperature' => $request->temperature,
        ]);

        return redirect()->route('sc_index')->with('msg', 'The record has been successfully added.')->with('msgtype', 'success');
    }

    public function edit($id) {

    }

    public function update(Request $request) {
        
    }

    public function delete($id) {

    }
}
