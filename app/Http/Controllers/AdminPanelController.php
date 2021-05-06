<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function accountIndex() {

        $lists = User::where('isAdmin', 1)->get();

        return view('admin_accounts_home', ['lists' => $lists]);
    }

    public function adminCodeStore(Request $request) {
        $request->validate([
            'adminType' => 'required',
            'pw' => 'required',
        ]);
        
        $hashedPassword = User::find(auth()->user()->id)->password;

        if (Hash::check($request->pw, $hashedPassword)) {
            $code = strtoupper(Str::random(6));

            $request->user()->brgyCode()->create([
                'brgy_id' => null,
                'bCode' => $code,
                'adminType' => $request->adminType
            ]);

            return redirect()->action([AdminPanelController::class, 'accountIndex'])->with('process', 'createAccount')->with('statustype', 'success')->with('bCode', $code);
        }
        else {
            return redirect()->action([AdminPanelController::class, 'accountIndex'])->with('modalstatus', 'Your password is incorrect. Please try again.')->with('statustype', 'danger');
        }
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

        $bCode = strtoupper(Str::random(6));

        $request->user()->brgyCode()->create([
            'brgy_id' => $request->brgyId,
            'bCode' => $bCode
        ]);
        
        return redirect()->action([AdminPanelController::class, 'brgyIndex'])->with('process', 'createCode')->with('bCode', $bCode);
    }
}
