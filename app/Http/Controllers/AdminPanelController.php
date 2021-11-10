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
        $lists = Brgy::orderBy('brgyName', 'asc')->paginate(10);
        $users = User::whereNotNull('brgy_id');
        return view('admin_brgy_home', ['lists' => $lists, 'users' => $users]);
    }

    public function accountIndex() {

        $lists = User::whereIn('isAdmin', [1,2])->orderBy('isAdmin', 'asc')->get();

        return view('admin_accounts_home', ['lists' => $lists]);
    }

    public function adminCodeStore(Request $request) {
        $request->validate([
            'adminType' => 'required|in:1,2,3,4',
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

    public function accountOptions($id, Request $request) {
        $user = User::findOrFail($id);
        
        if(auth()->user()->isAdmin == 1) {
            if($request->submit == 'accountInit') {
                if($user->isAdmin != 1) {
                    if($user->enabled == 1) {
                        $update = User::where('id', $id)
                        ->update([
                            'enabled' => 0,
                        ]);
                    }
                    else {
                        $update = User::where('id', $id)
                        ->update([
                            'enabled' => 1,
                        ]);
                    }

                    return redirect()->action([AdminPanelController::class, 'accountIndex'])
                    ->with('msg', 'Account status of '.$user->name.' ('.$user->email.') has been updated successfully.')
                    ->with('msgtype', 'success');
                }
                else {
                    return redirect()->action([AdminPanelController::class, 'accountIndex'])
                    ->with('msg', 'You are not allowed to do that.')
                    ->with('msgtype', 'warning');
                }
            }
            else if($request->submit == 'validatorInit') {
                if($user->isValidator == 1) {
                    $update = User::where('id', $id)
                    ->update([
                        'isValidator' => 0,
                    ]);
                }
                else {
                    $update = User::where('id', $id)
                    ->update([
                        'isValidator' => 1,
                    ]);
                }
                
                return redirect()->action([AdminPanelController::class, 'accountIndex'])
                ->with('msg', 'Validation Privilege of '.$user->name.' ('.$user->email.') has been updated successfully.')
                ->with('msgtype', 'success');
            }
            else if($request->submit == 'bypassValidationInit') {
                if($user->canByPassValidation == 1) {
                    $update = User::where('id', $id)
                    ->update([
                        'canByPassValidation' => 0,
                    ]);
                }
                else {
                    $update = User::where('id', $id)
                    ->update([
                        'canByPassValidation' => 1,
                    ]);
                }

                return redirect()->action([AdminPanelController::class, 'accountIndex'])
                ->with('msg', 'Bypass Validation Privilege of '.$user->name.' ('.$user->email.') has been updated successfully.')
                ->with('msgtype', 'success');
            }
        }
        else {
            return redirect()->action([AdminPanelController::class, 'accountIndex'])
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
    }

    public function brgyStore(Request $request) {

        $request->validate([
            'brgyName' => 'required|unique:brgy'
        ]);

        $request->user()->brgy()->create([
            'brgyName' => strtoupper($request->brgyName),
            'city_id' => 1,
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
