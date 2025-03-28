<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Disaster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EvacuationCenter;
use Illuminate\Support\Facades\Auth;
use App\Models\EvacuationCenterPatient;

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

        $patient_list = EvacuationCenterPatient::where('evacuation_center_id', $d->id)
        ->where('enabled', 'Y')
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('disaster.evacuationcenter_index', [
            'd' => $d,
            'patient_list' => $patient_list,
        ]);
    }

    public function newPatient($evac_id) {
        $d = EvacuationCenter::findOrFail($evac_id);

        $heads_list = EvacuationCenterPatient::where('evacuation_center_id', $d->id)
        ->where('is_headoffamily', 'Y')
        ->where('enabled', 'Y')
        ->orderBy('lname', 'ASC')
        ->get();

        return $this->editPatient(new EvacuationCenterPatient())
        ->with('d', $d)
        ->with('heads_list', $heads_list);
    }

    public function editPatient(EvacuationCenterPatient $pt) {
        return view('disaster.create_edit_patient', ['p' => $pt]);
    }
    
    public function storePatient($evac_id, Request $r) {
        $d = EvacuationCenter::findOrFail($evac_id);

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $mname = ($r->mname) ? mb_strtoupper($r->mname) : NULL;
        $suffix = ($r->suffix) ? mb_strtoupper($r->suffix) : NULL;

        $bdate = $r->bdate;

        $foundunique = false;

        while(!$foundunique) {
            $hashStr = Str::random(10);

            $s = EvacuationCenter::where('hash', $hashStr)->first();
            if(!$s) {
                $foundunique = true;
            }
        }

        $birthdate = Carbon::parse($bdate);
        $currentDate = Carbon::now();

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $c = EvacuationCenterPatient::create([
            'evacuation_center_id' => $d->id,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            'nickname' => ($r->nickname) ? mb_strtoupper($r->nickname) : NULL,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'bdate' => $r->bdate,
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'philhealth_number' => $r->philhealth_number,
            'religion' => $r->religion,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            'is_headoffamily' => $r->is_headoffamily,
            'is_pwd' => $r->is_pwd,
            'is_injured' => $r->is_injured,
            'outcome' => $r->outcome,

            'remarks' => $r->remarks,
            'hash' => $hashStr,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('gtsecure_evacuationcenter_view', $d->id)
        ->with('msg', 'Patient was successfully encoded and linked to this Evacuation Center.')
        ->with('msgtype', 'success');
    }

    public function viewPatient($id) {
        $p = EvacuationCenterPatient::findOrFail($id);

        $d = EvacuationCenter::findOrFail($p->evacuation_center_id);

        $heads_list = EvacuationCenterPatient::where('id', '!=', $p->id)
        ->where('evacuation_center_id', $d->id)
        ->where('is_headoffamily', 'Y')
        ->where('enabled', 'Y')
        ->orderBy('lname', 'ASC')
        ->get();

        return $this->editPatient($p)
        ->with('d', $d)
        ->with('heads_list', $heads_list);
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
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'suffix' => $suffix,
            'nickname' => ($r->nickname) ? mb_strtoupper($r->nickname) : NULL,
            'sex' => $r->sex,
            'is_pregnant' => ($r->sex == 'F') ? $r->is_pregnant : 'N',
            'is_lactating' => ($r->sex == 'F') ? $r->is_lactating : 'N',
            'bdate' => $r->bdate,
            'email' => $r->email,
            'contact_number' => $r->contact_number,
            'philhealth_number' => $r->philhealth_number,
            'religion' => $r->religion,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,
            'is_headoffamily' => $r->is_headoffamily,
            'is_pwd' => $r->is_pwd,
            'is_injured' => $r->is_injured,
            'outcome' => $r->outcome,

            'remarks' => $r->remarks,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
            'updated_by' => Auth::id(),
        ];

        $u = EvacuationCenterPatient::where('id', $id)
        ->update($update_params);

        return redirect()->route('gtsecure_evacuationcenter_view', $d->evacuation_center_id)
        ->with('msg', 'Details of '.$d->getName().' was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function evacPostUpdate($evac_id, Request $r) {
        
    }

    public function disasterGenerateReport($disaster_id) {

    }
}
