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
            
        ]);

        return redirect()->route('antigen_index')
        ->with('msg', 'Antigen Data has been added successfully.')
        ->with('msgtype', 'success');
    }
}
