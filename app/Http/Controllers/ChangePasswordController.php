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

    public function viewFirstTimeChangePw() {
        return view('password_first_change');
    }

    public function initFirstTimeChangePw(Request $r) {
        // This function will be used for first time login password change
        $r->validate([
            'newpw1' => 'required|min:8',
            'newpw2' => 'required|min:8',
        ]);

        if($r->newpw1 == $r->newpw2) {
            $usr = User::findOrFail(auth()->user()->id);

            $usr->password = Hash::make($r->newpw1);
            $usr->is_firsttimelogin = 0;
            $usr->lastpasswordchange_date = now();

            if(Hash::check($r->newpw1, $usr->password)) {
                return redirect()->route('first_changepw_view')
                ->with('msg', 'Error: New password cannot be the same as the current password. Please try again.')
                ->with('msgtype', 'warning');
            }

            $usr->save();

            return redirect()->route('home')
            ->with('msg', 'Your password has been changed successfully. You may now continue to use the system.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->route('first_changepw_view')
            ->with('msg', 'Error: New password does not match. Please try again.')
            ->with('msgtype', 'warning');
        }
    }
}
