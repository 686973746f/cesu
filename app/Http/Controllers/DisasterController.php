<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Disaster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EvacuationCenter;
use App\Models\EvacuationCenterFamiliesInside;
use App\Models\EvacuationCenterFamilyHead;
use App\Models\EvacuationCenterFamilyMember;
use App\Models\EvacuationCenterFamilyMembersInside;
use Illuminate\Support\Facades\Auth;
use App\Models\EvacuationCenterPatient;
use App\Models\EvacuationCenterPatientMembers;

class DisasterController extends Controller
{
    public function index() {
        $list = Disaster::orderBy('created_at', 'DESC')->paginate(10);

        return view('disaster.index', [
            'list' => $list,
        ]);
    }

    public function storeDisaster(Request $r) {
        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = Disaster::where('hash', $hashStr)->first();
            if(!$s) {
                $foundunique = true;
            }
        }

        $name = mb_strtoupper($r->name);

        $s = Disaster::where('name', $name)->first();

        if(!$s) {
            $c = Disaster::create([
                'name' => $name,
                'city_id' => 388, //Default for GenTri
                'date_start' => date('Y-m-d'),
                'status' => 'ACTIVE',
                'hash' => $hashStr,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('gtsecure_disaster_view', $c->id)
            ->with('msg', 'Disaster data was successfully added. Continue by adding Evacuation Centers and Patients inside.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Disaster Name already exists.')
            ->with('msgtype', 'warning');
        }
    }

    public function viewDisaster($disaster_id) {
        $d = Disaster::findOrFail($disaster_id);

        $list_evac = EvacuationCenter::where('enabled', 'Y')
        ->where('disaster_id', $d->id)
        ->orderBy('name', 'ASC')
        ->get();

        return view('disaster.disaster_view', [
            'd' => $d,
            'list_evac' => $list_evac,
        ]);
    }

    public function updateDisaster($id, Request $r) {
        $d = Disaster::findOrFail($id);

        $u = $d->update([
            'name',
            'event_type',
            'description',
            'city_id',
            'date_start',
            'date_end',
            'status',
        ]);

        //
    }

    public function viewFamilies() {
        $list = EvacuationCenterFamilyHead::orderBy('lname', 'ASC')->get();

        return view('disaster.family_list', [
            'list' => $list,
        ]);
    }

    public function storeFamilyHead(Request $r) {
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
        $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;

        $bdate = $r->bdate;

        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = EvacuationCenterFamilyHead::where('hash', $hashStr)->first();
            if(!$s) {
                $foundunique = true;
            }
        }

        //$birthdate = Carbon::parse($bdate);
        //$currentDate = Carbon::now();

        //$get_ageyears = $birthdate->diffInYears($currentDate);
        //$get_agemonths = $birthdate->diffInMonths($currentDate);
        //$get_agedays = $birthdate->diffInDays($currentDate);

        $check = EvacuationCenterFamilyHead::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Family Head '.$check->getName().' already exists. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = EvacuationCenterFamilyHead::create([
            //'evacuation_center_id' => $d->id,
            //'date_registered' => $r->date_registered,
            'dswd_serialno' => ($r->dswd_serialno) ? mb_strtoupper($r->dswd_serialno) : NULL,
            'cswd_serialno' => ($r->cswd_serialno) ? mb_strtoupper($r->cswd_serialno) : NULL,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            //'nickname' => ($r->nickname) ? mb_strtoupper($r->nickname) : NULL,
            'bdate' => $r->bdate,
            'birthplace' => (!is_null($r->birthplace)) ? mb_strtoupper($r->birthplace) : NULL,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'cs' => $r->cs,
            
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'contact_number2' => $r->contact_number2,
            //'philhealth_number' => $r->philhealth_number,
            'religion' => ($r->religion) ? mb_strtoupper($r->religion) : NULL,
            'occupation' => ($r->occupation) ? mb_strtoupper($r->occupation) : NULL,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            //'is_headoffamily' => $r->is_headoffamily,
            'id_presented' => mb_strtoupper($r->id_presented),
            'id_number' => mb_strtoupper($r->id_number),
            'house_ownership' => $r->house_ownership,
            //'shelterdamage_classification' => $r->shelterdamage_classification,
            //'is_injured' => $r->is_injured,
            'is_pwd' => $r->is_pwd,
            'is_4ps' => $r->is_4ps,
            'is_indg' => $r->is_indg,
            //'outcome' => $r->outcome,
            //'family_status' => $r->family_status,
            //'focal_name' => $r->focal_name,

            'remarks' => $r->remarks,
            'hash' => $hashStr,
            //'age_years' => $get_ageyears,
            //'age_months' => $get_agemonths,
            //'age_days' => $get_agedays,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('disaster_viewfamilies')
        ->with('msg', 'Family Head was successfully added.')
        ->with('msgtype', 'success');
    }

    public function viewFamilyHead($id) {
        $d = EvacuationCenterFamilyHead::findOrFail($id);

        $member_list = EvacuationCenterFamilyMember::where('familyhead_id', $d->id)->get();

        return view('disaster.view_familyhead', [
            'd' => $d,
            'member_list' => $member_list,
        ]);
    }

    public function updateFamilyHead($id, Request $r) {

    }

    public function storeFamilyMember($id, Request $r) {
        $d = EvacuationCenterFamilyHead::findOrFail($id);

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
        $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;

        $bdate = $r->bdate;

        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = EvacuationCenterFamilyHead::where('hash', $hashStr)->first();
            if(!$s) {
                $foundunique = true;
            }
        }

        $check = EvacuationCenterFamilyMember::where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Family Member '.$check->getName().' already exists. Kindly double check and try again.')
            ->with('msgtype', 'warning');
        }

        $c = EvacuationCenterFamilyMember::create([
            'familyhead_id' => $d->id,
            'relationship_tohead' => $r->relationship_tohead,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            //'nickname',
            'bdate' => $bdate,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'highest_education' => $r->highest_education,
            'occupation', ($r->occupation) ? mb_strtoupper($r->occupation) : NULL,
            //'outcome',
            //'date_missing',
            //'date_returned',
            //'date_died',
            //'is_injured',
            'is_pwd' => $r->is_pwd,
            'is_4ps' => $r->is_4ps,
            'is_indg' => $r->is_indg,
            //'cswd_serialno',
            //'dswd_serialno',
            //'remarks',
            'created_by' => Auth::id(),
            'hash' => $hashStr,
        ]);

        return redirect()->route('disaster_viewfamilyhead', $d->id)
        ->with('msg', 'Family Member was added successfully. (ID: '.$c->id.')')
        ->with('msgtype', 'success');
    }

    public function updateFamilyMember($id, Request $r) {

    }

    public function storeEvacuationCenter($disaster_id, Request $r) {
        $d = Disaster::findOrFail($disaster_id);

        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = EvacuationCenter::where('hash', $hashStr)->first();
            if(!$s) {
                $foundunique = true;
            }
        }

        $name = mb_strtoupper($r->name);
        $s = EvacuationCenter::where('name', $name)
        ->where('address_brgy_code', $r->address_brgy_code)
        ->first();

        if(!$s) {
            $c = EvacuationCenter::create([
                'disaster_id' => $d->id,
                'name' => $name,
                'address_brgy_code' => $r->address_brgy_code,
                'date_start' => $r->date_start,
    
                'hash' => $hashStr,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('gtsecure_evacuationcenter_view', $c->id)
            ->with('msg', 'Evacuation Center was successfully added. You may continue by Encoding Patients.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Evacuation Center data already exists.')
            ->with('msgtype', 'warning');
        }
    }
    
    public function viewEvacuationCenter($id) {
        $d = EvacuationCenter::findOrFail($id);
        
        $assignedFamilyHeadIds = EvacuationCenterFamiliesInside::whereHas('evacuationCenter', function ($q) use ($d) {
            $q->where('disaster_id', $d->disaster_id);
        })
        ->pluck('familyhead_id');

        $available_list = EvacuationCenterFamilyHead::whereNotIn('id', $assignedFamilyHeadIds)->get();
        
        $head_list = EvacuationCenterFamiliesInside::where('evacuation_center_id', $d->id)->get();

        return view('disaster.evacuationcenter_index', [
            'd' => $d,
            'head_list' => $head_list,
            'available_list' => $available_list,
        ]);
    }

    public function linkFamilyToEvac($id, Request $r) {
        $e = EvacuationCenter::findOrFail($id);

        $check = EvacuationCenterFamiliesInside::where('evacuation_center_id', $e->id)
        ->where('familyhead_id', $r->familyhead_id)
        ->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'ERROR: Family ID already exists inside the Evacuation Center.')
            ->with('msgtype', 'warning');
        }

        $check2 = EvacuationCenterFamiliesInside::where('familyhead_id', $r->familyhead_id)
        ->whereHas('evacuationCenter', function ($q) use ($e) {
            $q->where('disaster_id', $e->disaster_id)
            ->where('id', '!=', $e->id); // exclude the current center
        })
        ->first();

        if($check2) {
            return redirect()->back()
            ->with('msg', 'ERROR: Family Head was already in another Evacuation Center ('.$check2->evacuationCenter->name.')')
            ->with('msgtype', 'warning');
        }

        $c = EvacuationCenterFamiliesInside::create([
            'evacuation_center_id' => $e->id,
            'familyhead_id' => $r->familyhead_id,
            'date_registered' => $r->date_registered,
            'family_status' => 'ACTIVE',
            'outcome' => 'ALIVE',
            'is_injured' => $r->is_injured,
            'shelterdamage_classification' => $r->shelterdamage_classification,
            'evac_type' => $r->evac_type,
            'remarks' => $r->remarks,
            'focal_name' => ($r->focal_name) ? mb_strtoupper($r->focal_name) : NULL,
            'supervisor_name' => ($r->supervisor_name) ? mb_strtoupper($r->supervisor_name) : NULL,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('gtsecure_evacuationcenter_view', $e->id)
        ->with('msg', 'Family ID #'.$r->familyhead_id.' was successfully added to the evacuation center.')
        ->with('msgtype', 'success');
    }

    public function viewEvacFamily($evac_id, $headinside_id, Request $r) {
        $d = EvacuationCenterFamiliesInside::findOrFail($headinside_id);

        $the_array = EvacuationCenterFamilyMembersInside::where('familyinside_id', $d->id)
        ->pluck('member_id') // only get the column familyhead_id
        ->toArray();

        $available_list = EvacuationCenterFamilyMember::whereNotIn('id', $the_array)
        ->where('familyhead_id', $d->familyhead_id)
        ->get();
        
        $list = EvacuationCenterFamilyMembersInside::where('familyinside_id', $d->id)->get();

        return view('disaster.evac_viewfamily', [
            'd' => $d,
            'available_list' => $available_list,
            'list' => $list,
        ]);
    }

    public function linkMemberToEvac($evac_id, $headinside_id, Request $r) {
        $d = EvacuationCenterFamiliesInside::findOrFail($headinside_id);

        $check = EvacuationCenterFamilyMembersInside::where([
            'familyinside_id' => $d->id,
            'member_id' => $r->member_id,
        ])->first();

        if($check) {
            return redirect()->back()
            ->with('msg', 'ERROR: Family member was already added.')
            ->with('msgtype', 'warning');
        }

        $c = EvacuationCenterFamilyMembersInside::create([
            'date_registered' => $r->date_registered,
            'familyinside_id' => $d->id,
            'member_id' => $r->member_id,

            'is_injured' => $r->is_injured,
            'is_admitted' => $r->is_admitted,
            'date_admitted' => ($r->is_admitted == 'Y') ? $r->date_admitted : NULL,
            'date_discharged' => ($r->is_admitted == 'Y') ? $r->date_discharged : NULL,
            'outcome' => $r->outcome,
            'date_missing' => ($r->outcome == 'MISSING' || $r->outcome == 'MISSING THEN RETURNED') ? $r->date_missing : NULL,
            'date_returned' => ($r->outcome == 'MISSING THEN RETURNED') ? $r->date_returned : NULL,
            'date_died' => ($r->outcome == 'DIED') ? $r->date_died : NULL,

            'remarks' => $r->remarks,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('disaster_viewfamilyevac', [$d->evacuation_center_id, $d->id])
        ->with('msg', 'Family Member was successfully added.')
        ->with('msgtype', 'success');
    }

    public function viewHearsReport($disaster_id) {

    }

    public function storeHearsReport($disaster_id, Request $r) {
        
    }

    public function viewReportDashbooard($disaster_id) {

    }

    /*
    public function viewPatient($id) {
        $p = EvacuationCenterPatient::findOrFail($id);

        $d = EvacuationCenter::findOrFail($p->evacuation_center_id);

        return $this->editPatient($p)
        ->with('d', $d);
    }

    public function updatePatient($id, Request $r) {
        $d = EvacuationCenterPatient::findOrFail($id);

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
        $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;

        $bdate = $r->bdate;

        $birthdate = Carbon::parse($bdate);
        $currentDate = Carbon::now();

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $update_params = [
            'date_registered' => $r->date_registered,
            'cswd_serialno' => ($r->cswd_serialno) ? mb_strtoupper($r->cswd_serialno) : NULL,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            //'nickname' => ($r->nickname) ? mb_strtoupper($r->nickname) : NULL,
            'bdate' => $r->bdate,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'cs' => $r->cs,
            
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'contact_number2' => $r->contact_number2,
            //'philhealth_number' => $r->philhealth_number,
            'religion' => ($r->religion) ? mb_strtoupper($r->religion) : NULL,
            'occupation' => ($r->occupation) ? mb_strtoupper($r->occupation) : NULL,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            //'is_headoffamily' => $r->is_headoffamily,
            'id_presented' => mb_strtoupper($r->id_presented),
            'id_number' => mb_strtoupper($r->id_number),
            'house_ownership' => $r->house_ownership,
            'shelterdamage_classification' => $r->shelterdamage_classification,
            'is_pwd' => $r->is_pwd,
            'is_injured' => $r->is_injured,
            'is_4ps' => $r->is_4ps,
            'is_indg' => $r->is_indg,
            'outcome' => $r->outcome,
            'family_status' => $r->family_status,
            'focal_name' => $r->focal_name,

            'remarks' => $r->remarks,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        $u = EvacuationCenterPatient::where('id', $id)
        ->update($update_params);

        return redirect()->route('gtsecure_evacuationcenter_view', $d->evacuation_center_id)
        ->with('msg', 'Details of '.$d->getName().' was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function newMember($id) {
        $d = EvacuationCenterPatient::findOrFail($id);
        
        return $this->viewMember(new EvacuationCenterPatientMembers())
        ->with('d', $d);
    }

    public function storeMember($id, Request $r) {
        $d = EvacuationCenterPatient::findOrFail($id);

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
        $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;

        $bdate = $r->bdate;

        $birthdate = Carbon::parse($bdate);
        $currentDate = Carbon::now();

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = EvacuationCenterPatient::where('hash', $hashStr)->first();
            if(!$s) {
                $s1 = EvacuationCenterPatientMembers::where('hash', $hashStr)->first();

                if(!$s1) {
                    $foundunique = true;
                }
            }
        }

        $c = EvacuationCenterPatientMembers::create([
            'familyhead_id' => $d->id,
            'relationship_tohead' => $r->relationship_tohead,
            'date_registered' => $r->date_registered,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            //'nickname',
            'bdate' => $r->bdate,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'highest_education' => $r->highest_education,
            'occupation' => $r->occupation,
            'outcome' => $r->outcome,
            'date_missing' => ($r->outcome == 'MISSING') ? $r->date_missing : NULL,
            'date_died' => ($r->outcome == 'DIED') ? $r->date_died : NULL,
            'is_injured' => $r->is_injured,
            'is_pwd' => $r->is_pwd,
            'is_4ps' => $r->is_4ps,
            'is_indg' => $r->is_indg,
            'indg_specify' => ($r->is_indg == 'Y') ? mb_strtoupper($r->indg_specify) : NULL,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
            'cswd_serialno' => ($r->cswd_serialno) ? mb_strtoupper($r->cswd_serialno) : NULL,
            //'dswd_serialno',
            'remarks' => $r->remarks,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'hash' => $hashStr,
        ]);

        return redirect()->route('gtsecure_evacuationcenter_view', $d->evacuation_center_id)
        ->with('msg', 'Family Member was successfully added.')
        ->with('msgtype', 'success');
    }

    public function viewMember(EvacuationCenterPatientMembers $pt) {
        return view('disaster.create_edit_patient_member', ['p' => $pt]);
    }

    public function updateMember($member_id) {

    }

    public function evacPostUpdate($evac_id, Request $r) {
        
    }

    public function disasterGenerateReport($disaster_id) {

    }
    */
}
