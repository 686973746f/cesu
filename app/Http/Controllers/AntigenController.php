<?php

namespace App\Http\Controllers;

use App\Models\Antigen;
use Illuminate\Http\Request;

class AntigenController extends Controller
{
    public function index() {
        $list = Antigen::orderBy('antigenKitName', 'ASC')->get();

        return view('antigen_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        return view('antigen_create');
    }

    public function store(Request $request) {
        $request->validate([
            'antigenKitName' => 'required',
            'antigenKitShortName' => 'required',
            'lotNo' => 'required',
            'isDOHAccredited' => 'required|in:Yes,No',
        ]);

        $request->user()->antigen()->create([
            'antigenKitName' => mb_strtoupper($request->antigenKitName),
            'antigenKitShortName' => mb_strtoupper($request->antigenKitShortName),
            'lotNo' => mb_strtoupper($request->lotNo),
            'isDOHAccredited' => ($request->isDOHAccredited == 'Yes') ? 1 : 0,
        ]);

        return redirect()->route('antigen_index')
        ->with('msg', 'Antigen Data has been added successfully.')
        ->with('msgtype', 'success');
    }

    public function edit($id) {
        $data = Antigen::findOrFail($id);

        return view('antigen_edit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id) {
        $data = Antigen::findOrFail($id);

        $request->validate([
            'antigenKitName' => 'required',
            'antigenKitShortName' => 'required',
            'lotNo' => 'required',
            'isDOHAccredited' => 'required|in:Yes,No',
        ]);

        $data->antigenKitName = mb_strtoupper($request->antigenKitName);
        $data->antigenKitShortName = mb_strtoupper($request->antigenKitShortName);
        $data->lotNo = mb_strtoupper($request->lotNo);
        $data->isDOHAccredited = ($request->isDOHAccredited == 'Yes') ? 1 : 0;

        if($data->isDirty()) {
            $data->save();
        }

        return redirect()->route('antigen_index')
        ->with('msg', 'Antigen Data has been updated successfully.')
        ->with('msgtype', 'success');
    }
}
