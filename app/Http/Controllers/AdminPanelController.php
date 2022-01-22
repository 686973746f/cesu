<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\City;
use App\Models\User;
use App\Models\BrgyCodes;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use IlluminateAgnostic\Collection\Support\Str;

class AdminPanelController extends Controller
{
    public function index() {
        return view('admin_home');
    }

    public function brgyIndex() {
        if(request()->input('q')) {
            $search = request()->input('q');

            $lists = Brgy::where('brgyName', 'LIKE', '%'.$search.'%')->orWhere('id', $search)->paginate(10);
        }
        else {
            $lists = Brgy::orderBy('brgyName', 'asc')->paginate(10);
        }

        $allBrgy = Brgy::orderBy('brgyName', 'asc')->get();
        $users = User::whereNotNull('brgy_id');

        return view('admin_brgy_home', ['lists' => $lists, 'users' => $users, 'allBrgy' => $allBrgy]);
    }

    public function brgyView($id) {
        $data = Brgy::findOrFail($id);

        $city_list = City::all();
        $account_list = User::where('brgy_id', $data->id)->get();

        return view('admin_brgy_view_single', ['data' => $data, 'city_list' => $city_list, 'account_list' => $account_list]);
    }

    public function referralCodeView() {
        $data = BrgyCodes::orderBy('created_at', 'DESC')->paginate(10);

        return view('admin_brgy_view_code', ['data' => $data]);
    }

    public function brgyViewUser($brgy_id, $user_id) {
        $brgy = Brgy::findOrFail($brgy_id);
        $user = User::findOrFail($user_id);

        $interviewers = Interviewers::all();

        if($user->brgy_id == $brgy->id) {
            return view('admin_brgy_view_user', ['brgy' => $brgy, 'user' => $user, 'interviewers' => $interviewers]);
        }
        else {
            return abort(401);
        }
    }

    public function brgyUpdateUser($brgy_id, $user_id, Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'enabled' => 'required|in:0,1',
            'interviewer_id' => 'nullable',
            'canAccessLinelist' => 'required|in:0,1',
            'canByPassValidation' => 'required|in:0,1',
            'isValidator' => 'required|in:0,1',
            'isPositiveEncoder' => 'required|in:0,1',
        ]);

        $brgy = Brgy::findOrFail($brgy_id);
        $user = User::findOrFail($user_id);

        if($user->brgy_id == $brgy->id) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->enabled = $request->enabled;
            $user->interviewer_id = $request->interviewer_id;
            $user->canAccessLinelist = $request->canAccessLinelist;
            $user->canByPassValidation = $request->canByPassValidation;
            $user->isValidator = $request->isValidator;
            $user->isPositiveEncoder = $request->isPositiveEncoder;

            if($user->isDirty()) {
                $user->save();
            }

            return redirect()->route('adminpanel.brgy.view.user', ['brgy_id' => $brgy->id, 'user_id' => $user->id])
            ->with('msg', 'User information has been updated successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function brgyUpdate($id, Request $request) {
        $data = Brgy::findOrFail($id);

        $request->validate([
            'brgyName' => 'required',
            'displayInList' => 'required',
            'city_id' => 'required',
            'estimatedPopulation' => 'nullable|numeric',
            'dilgCustCode' => 'nullable',
        ]);

        $data->brgyName = mb_strtoupper($request->brgyName);
        $data->displayInList = $request->displayInList;
        $data->city_id = $request->city_id;
        $data->estimatedPopulation = $request->estimatedPopulation;
        $data->dilgCustCode = $request->dilgCustCode;

        if($data->isDirty()) {
            $data->save();   
        }

        return redirect()->route('adminpanel.brgy.index')
        ->with('msg', 'Barangay Data has been updated successfully.')
        ->with('msgtype', 'success');
    }

    public function accountIndex() {
        $lists = User::whereIn('isAdmin', [1,2])->orderBy('isAdmin', 'asc')->paginate(10);

        return view('admin_accounts_home', ['lists' => $lists]);
    }

    public function accountView($id) {
        $data = User::findOrFail($id);

        return view('admin_accounts_view', ['data' => $data]);
    }

    public function accountUpdate($id) {
        
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

    public function brgyCodeStore($brgy_id, Request $request) {
        $data = Brgy::findOrFail($brgy_id);

        $request->validate([
            'pw' => 'required',
        ]);

        $hashedPassword = User::find(auth()->user()->id)->password;

        if (Hash::check($request->pw, $hashedPassword)) {
            $bCode = strtoupper(Str::random(6));

            $request->user()->brgyCode()->create([
                'brgy_id' => $data->id,
                'bCode' => $bCode,
            ]);
            
            return redirect()->route('adminpanel.brgy.view', ['id' => $data->id])
            ->with('process', 'createCode')
            ->with('bCode', $bCode);
        }
        else {
            return redirect()->route('adminpanel.brgy.view', ['id' => $data->id])
            ->with('msg', 'Password is incorrect. Please try again.')
            ->with('msgtype', 'danger');
        }
    }

    public function encoderStatsIndex() {
        return view('admin_encoder_stats_index');
    }
}
