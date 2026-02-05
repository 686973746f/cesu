<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\City;
use App\Models\User;
use App\Models\Forms;
use App\Models\WorkTask;
use App\Models\BrgyCodes;
use App\Models\LiveBirth;
use App\Models\DohFacility;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Models\PharmacyBranch;
use App\Models\VaxcertConcern;
use App\Models\SyndromicDoctor;
use App\Models\DeathCertificate;
use App\Models\SyndromicRecords;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\EvacuationCenterFamilyHead;
use App\Models\EvacuationCenterFamilyMember;
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
        $lists = User::paginate(10);

        $facility_list = DohFacility::where('enabled', 'Y')
        ->where('address_muncity', config('custom.default_city_name'))
        ->orderBy('facility_name', 'ASC')
        ->get();

        $doctors_list = SyndromicDoctor::where('active_in_service', 'Y')
        ->orderBy('doctor_name', 'ASC')
        ->get();

        $abtc_list = AbtcVaccinationSite::where('enabled', 1)
        ->orderBy('site_name', 'ASC')
        ->get();

        $pharmacy_branches = PharmacyBranch::where('enabled', 1)
        ->orderBy('name', 'ASC')
        ->get();

        $perm_list = User::getPermissionList();

        return view('admin_accounts_home', [
            'lists' => $lists,
            'facility_list' => $facility_list,
            'doctors_list' => $doctors_list,
            'abtc_list' => $abtc_list,
            'pharmacy_branches' => $pharmacy_branches,
            'perm_list' => $perm_list,
        ]);
    }

    public function adminAccountCreate(Request $r) {
        $name = mb_strtoupper($r->name);
        $email = $r->email;

        $check = User::where('name', $r->name)
        ->orWhere('email', $email)
        ->first();

        if($check) {
            return redirect()
            ->back()
            ->with('msg', 'ERROR: User or email already exists.')
            ->with('msgtype', 'warning');
        }

        $c = User::create([
            'isAdmin' => 2,

            'name' => $name,
            'email' => $email,
            'password' => Hash::make('12345678'),

            'itr_facility_id' => $r->itr_facility_id,
            'itr_doctor_id' => $r->itr_doctor_id,
            'pharmacy_branch_id' => $r->pharmacy_branch_id,
            'abtc_default_vaccinationsite_id' => $r->abtc_default_vaccinationsite_id,
            'etcl_bhs_id' => $r->etcl_bhs_id,
            'switch_bhs_list' => $r->switch_bhs_list,

            'permission_list' => implode(",", $r->permission_list),
        ]);

        $c->email_verified_at = now();
        $c->save();

        return redirect()
        ->back()
        ->with('msg', 'User '.$c->name.' was successfully created.')
        ->with('msgtype', 'success');
    }

    public function accountView($id) {
        $d = User::findOrFail($id);

        //Load Pharmacy Branches
        $pharma_branches = PharmacyBranch::where('enabled', 1)
        ->orderBy('name', 'ASC')
        ->get();

        //Load DOH Facilities for OPD System ID
        $opd_branches = DohFacility::where('address_muncity', 'CITY OF GENERAL TRIAS')
        ->where('id', '!=', 10887)
        ->where('ownership_type', 'Government')
        ->orderBy('facility_name', 'ASC')
        ->get();

        $abtc_branches = AbtcVaccinationSite::where('enabled', 1)->get();

        if(!is_null($d->itr_facility_id)) {
            $opd_doctors = SyndromicDoctor::where('facility_id', $d->itr_facility_id)
            ->where('active_in_service', 'Y')
            ->orderBy('doctor_name', 'ASC')
            ->get();
        }
        else {
            $opd_doctors = NULL;
        }

        $perm_list = User::getPermissionList();

        return view('admin_accounts_view', [
            'd' => $d,
            'pharma_branches' => $pharma_branches,
            'opd_branches' => $opd_branches,
            'abtc_branches' => $abtc_branches,
            'opd_doctors' => $opd_doctors,
            'perm_list' => $perm_list,
        ]);
    }

    public function accountUpdate($id, Request $r) {
        $d = User::findOrFail($id);

        $d->name = $r->name;
        $d->enabled = $r->enabled;
        $d->encoder_stats_visible = $r->encoder_stats_visible;
        $d->itr_facility_id = ($r->itr_facility_id != 'NONE') ? $r->itr_facility_id : NULL;
        $d->pharmacy_branch_id = ($r->pharmacy_branch_id != 'NONE') ? $r->pharmacy_branch_id : NULL;
        $d->itr_doctor_id = ($r->itr_doctor_id != 'NONE') ? $r->itr_doctor_id : NULL;
        $d->abtc_default_vaccinationsite_id = ($r->abtc_default_vaccinationsite_id != 'NONE') ? $r->abtc_default_vaccinationsite_id : NULL;
        $d->permission_list = implode(",", $r->permission_list);
        $d->etcl_bhs_id = $r->etcl_bhs_id;
        $d->switch_bhs_list = $r->switch_bhs_list;

        if($d->isDirty()) {
            $d->save();
        }

        return redirect()
        ->route('admin_account_index')
        ->with('msg', 'User Account '.$r->name.' was updated successfully.')
        ->with('msgtype', 'success');
    }

    /*
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
    */

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
        $list = User::where('enabled', 1);
        
        if(auth()->user()->isGlobalAdmin()) {
            $list = $list->where('encoder_stats_visible', 1)
            ->orderBy('name', 'ASC')
            ->get();
        }
        else {
            $list = $list->where('id', Auth::id())
            ->get();
        }

        $arr = [];

        if(request()->input('date')) {
            $date = request()->input('date');
        }
        else {
            $date = date('Y-m-d');
        }

        foreach($list as $item) {
            /*
            $suspected_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) {
                $q->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->count();
            */

            /*
            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where(function ($r) use ($item) {
                    $r->where('user_id', $item->id)
                    ->where('updated_by', '!=', $item->id);
                })
                ->orWhere(function ($s) use ($item) {
                    $s->where('user_id', '!=', $item->id)
                    ->where('updated_by', $item->id);
                })
                ->orWhere(function ($t) use ($item) {
                    $t->where('user_id', $item->id)
                    ->where('updated_by', $item->id);
                });
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->count();
            */

            /*
            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) {
                $q->whereDate('created_at', date('Y-m-d'))
                ->orWhereDate('updated_at', date('Y-m-d'));
            })
            ->whereDate('morbidityMonth', date('Y-m-d'))
            ->where('caseClassification', 'Confirmed')
            ->count();
            */

            //If changing values here, please also change the values in Encoding Count and Monthly Accomplishment

            $suspected_count = Forms::where('user_id', $item->id)
            ->where(function($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->whereIn('caseClassification', ['Suspect', 'Probable'])
            ->count();

            $confirmed_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->whereDate('morbidityMonth', $date)
            ->where('caseClassification', 'Confirmed')
            ->count();

            $recovered_count = Forms::where(function ($q) use ($item) {
                $q->where(function ($r) use ($item) {
                    $r->where('user_id', $item->id)
                    ->where('updated_by', '!=', $item->id);
                })
                ->orWhere(function ($s) use ($item) {
                    $s->where('user_id', '!=', $item->id)
                    ->where('updated_by', $item->id);
                })
                ->orWhere(function ($t) use ($item) {
                    $t->where('user_id', $item->id)
                    ->where('updated_by', $item->id);
                });
            })
            ->whereDate('morbidityMonth', $date)
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Recovered')
            ->count();
            
            $negative_count = Forms::where(function ($q) use ($item) {
                $q->where('user_id', $item->id)
                ->orWhere('updated_by', $item->id);
            })
            ->where(function ($q) use ($date) {
                $q->whereDate('created_at', $date)
                ->orWhereDate('updated_at', $date);
            })
            ->where('caseClassification', 'Non-COVID-19 Case')
            ->count();

            $covid_count_final = $suspected_count + $confirmed_count + $recovered_count + $negative_count;

            $abtc_count = AbtcBakunaRecords::where('d0_done_by', $item->id)
            ->whereDate('d0_done_date', $date)
            ->count();

            $abtc_count_ff1 = AbtcBakunaRecords::where('d3_done_by', $item->id)
            ->whereDate('d3_done_date', $date)
            ->count();

            $abtc_count_ff2 = AbtcBakunaRecords::where('d7_done_by', $item->id)
            ->whereDate('d7_done_date', $date)
            ->count();

            $abtc_count_ff3 = AbtcBakunaRecords::where('d14_done_by', $item->id)
            ->whereDate('d14_done_date', $date)
            ->count();

            $abtc_count_ff4 = AbtcBakunaRecords::where('d28_done_by', $item->id)
            ->whereDate('d28_done_date', $date)
            ->count();

            $abtc_ffup_gtotal = $abtc_count_ff1 + $abtc_count_ff2 + $abtc_count_ff3 + $abtc_count_ff4;

            $vaxcert_count = VaxcertConcern::where('processed_by', $item->id)
            ->whereDate('updated_at', $date)
            ->count();

            $opd_count = SyndromicRecords::where('created_by', $item->id)
            ->whereDate('created_at', $date)
            ->count();

            $lcr_livebirth = LiveBirth::whereDate('created_at', $date)
            ->where('created_by', $item->id)
            ->count();
            
            $disease_list = PIDSRController::listDiseasesTables();

            //Add Laboratory data table for counting
            $disease_list = $disease_list + [
                'EdcsLaboratoryData',
            ];

            $edcs_count = 0;

            foreach($disease_list as $d) {
                $modelClass = "App\\Models\\$d";

                $model_count = $modelClass::where('created_by', $item->id)
                ->whereDate('created_at', $date)
                ->count();

                $edcs_count += $model_count;
            }

            /*
            $death_count = WorkTask::where('name', 'DAILY ENCODE OF DEATH CERTIFICATES TO FHSIS')
            ->where('finished_by', $item->id)
            ->whereDate('finished_date', $date)
            ->first();
            
            if($death_count) {
                $death_count = $death_count->encodedcount ?: 0;
            }
            else {
                $death_count = 0;
            }
            */

            $death_count = DeathCertificate::whereDate('created_at', $date)
            ->where('created_by', $item->id)
            ->count();

            $opdtoics_count = SyndromicRecords::where('ics_finishedby', $item->id)
            ->whereDate('ics_finished_date', $date)
            ->count();

            $abtctoics_count = AbtcBakunaRecords::where('ics_finishedby', $item->id)
            ->whereDate('ics_finished_date', $date)
            ->count();

            $evac_count1 = EvacuationCenterFamilyHead::where('created_by', $item->id)
            ->whereDate('created_at', $date)
            ->count();

            $evac_count2 = EvacuationCenterFamilyMember::where('created_by', $item->id)
            ->whereDate('created_at', $date)
            ->count();

            array_push($arr, [
                'id' => $item->id,
                'name' => $item->name,
                'covid_count_final' => $covid_count_final,
                'abtc_count' => $abtc_count,
                'abtc_ffup_gtotal' => $abtc_ffup_gtotal,
                'vaxcert_count' => $vaxcert_count,
                'opd_count' => $opd_count,
                'lcr_livebirth' => $lcr_livebirth,
                'edcs_count' => $edcs_count,
                'death_count' => $death_count,
                'opdtoics_count' => $opdtoics_count,
                'abtctoics_count' => $abtctoics_count,
                'evac_count' => $evac_count1 + $evac_count2,
            ]);
        }

        return view('admin_encoder_stats_index', [
            'arr' => $arr,
            'date' => $date,
        ]);
    }
}
