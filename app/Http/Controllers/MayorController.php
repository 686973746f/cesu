<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PharmacyBranch;

class MayorController extends Controller
{
    public function mainMenu() {
        return view('mayor.main_menu');
    }

    public function pharmacyMainMenu() {
        $list_branches = PharmacyBranch::where('enabled', 1)->orderBy('name', 'ASC')->get();

        return view('mayor.pharmacy_home', [
            'list_branches' => $list_branches,
        ]);
    }

    public function pharmacyChangeBranch(Request $r) {
        $d = User::findOrfail(auth()->user()->id);

        $d->pharmacy_branch_id = $r->select_branch;

        if($d->isDirty()) {
            $d->save();
        }
        
        return redirect()->route('mayor_pharmacy_main_menu')
        ->with('msg', 'Successfully changed Pharmacy Branch.')
        ->with('msgtype', 'success');
    }
}
