<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcceptanceLetter;

class AcceptanceLetterController extends Controller
{
    public function index() {
        $list = AcceptanceLetter::orderBy('created_at', 'desc')->paginate(10);
        
        return view('acceptanceletter', [
            'list' => $list,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'lname' => 'required',
        ]);

        return redirect()->route('acceptance.index')
        ->with('msg', 'Acceptance Letter was successfully created.')
        ->with('msgType', 'success');
    }

    public function savetodocx(Request $request) {

    }
}
