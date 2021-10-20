<?php

namespace App\Http\Controllers;

use App\Models\BrgyCodes;
use Illuminate\Http\Request;
use App\Models\ReferralCodes;

class RegisterCodeController extends Controller
{
    public function index() {
        return view('auth.register_code');
    }

    public function refCodeCheck(Request $request) {

        $request->validate([
            'refCode' => 'required'
        ]);

        $list = BrgyCodes::where('bCode', strtoupper($request->refCode))
        ->where('enabled', 1)
        ->first();
        
        if($list) {
            return view('auth.register', ['item' => $list, 'aType' => 'cesu']);
        }
        else {

            $list1 = ReferralCodes::where('refCode', strtoupper($request->refCode))
            ->where('enabled', 1)
            ->first();

            if($list1) {
                return view('auth.register', ['item' => $list1, 'aType' => 'company']);
            }
            else {
                return back()
                ->withInput()
                ->with('msg', 'Referral Code is invalid. Pleae try again.');
            }
        }
    }
}
