<?php

namespace App\Http\Controllers;

use App\Models\BrgyCodes;
use Illuminate\Http\Request;

class RegisterCodeController extends Controller
{
    public function index() {
        return view('auth.register_code');
    }

    public function refCodeCheck(Request $request) {

        $request->validate([
            'refCode' => 'required'
        ]);

        $list = BrgyCodes::where([
            ['bCode', strtoupper($request->refCode)],
            ['enabled', 1]
        ]);

        if($list->exists()) {
            //return redirect()->route('register', ['list' => $list]);

            $list = $list->get();

            return view('auth.register', ['list' => $list]);
        }
        else {
            return back()
			->withInput()
			->with('msg', 'Referral Code is invalid. Pleae try again.');
        }
    }
}
