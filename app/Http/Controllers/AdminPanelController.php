<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use Illuminate\Http\Request;
use IlluminateAgnostic\Collection\Support\Str;

class AdminPanelController extends Controller
{
    public function index() {
        return view('admin_home');
    }

    public function brgyIndex() {
        $lists = Brgy::all();
        return view('admin_brgy_home', ['lists' => $lists]);
    }

    public function brgyStore(Request $request) {

        $request->validate([
            'brgyName' => 'required|unique:brgy'
        ]);

        $request->user()->brgy()->create([
            'brgyName' => strtoupper($request->brgyName),
        ]);

        return redirect()->action([AdminPanelController::class, 'brgyIndex'])->with('status', 'Barangay Data has been created successfully.')->with('statustype', 'success');
    }

    public function brgyCodeStore(Request $request) {
        $request->user()->brgyCode()->create([
            'brgy_id' => $request->brgyId,
            'bCode' => strtoupper(Str::random(6))
        ]);
        
        return redirect()->action([AdminPanelController::class, 'brgyIndex'])->with('status', 'Barangay Data has been created successfully.')->with('statustype', 'success');
    }
}
