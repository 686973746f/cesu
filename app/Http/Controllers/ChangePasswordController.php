<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index() {
        return view('changepw');
    }

    public function initChangePw(Request $request) {

        $request->validate([
            'oldpw' => 'required|min:8',
            'newpw1' => 'required|min:8',
            'newpw2' => 'required|min:8',
        ]);

        if(Hash::check($request->oldpw, User::find(auth()->user()->id)->password)) {
            if($request->newpw1 == $request->newpw2) {
                $usr = User::findOrFail(auth()->user()->id);

                $usr->password = Hash::make($request->newpw1);

                $usr->save();

                return redirect()->route('changepw.index')
                ->with('msg', 'Your password has been changed successfully.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->route('changepw.index')
                ->with('msg', 'New password does not match. Please try again.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return redirect()->route('changepw.index')
            ->with('msg', 'Your current password is incorrect. Please try again.')
            ->with('msgtype', 'warning');
        }
    }
}
