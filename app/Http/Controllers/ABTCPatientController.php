<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AbtcPatient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use App\Models\AbtcVaccineLogs;
use Illuminate\Support\Facades\Session;

class ABTCPatientController extends Controller
{
    public function home() {

        Session::put('default_menu', 'ABTC');
        Session::put('default_home_url', route('abtc_home'));

        $vslist = AbtcVaccinationSite::where('enabled', 1)->orderBy('id', 'ASC')->get();

        //get uninitialized vaccine stocks
        $init_list = AbtcVaccineStocks::where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)->pluck('vaccine_id');
        if($init_list->count() == 0) {
            $get_initVaccineList = AbtcVaccineBrand::where('enabled', 1)->get();
        }
        else {
            $get_initVaccineList = AbtcVaccineBrand::whereNotIn('id', $init_list)->where('enabled', 1)->get();
        }

        $wastage_submit_check = AbtcVaccineLogs::whereDate('created_at', date('Y-m-d'))->first();
        
        return view('abtc.home', [
            'vslist' => $vslist,
            'get_initVaccineList' => $get_initVaccineList,
            'wastage_submit_check' => $wastage_submit_check,
        ]);
    }

    public function index() {
        if(request()->input('q')) {
            $list = AbtcPatient::where(function ($q) {
                $q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere('id', request()->input('q'));
            })
            ->orderByRaw('lname ASC, fname ASC, mname ASC')
            ->paginate(10);
        }
        else {
            $list = AbtcPatient::orderBy('lname', 'ASC')->paginate(10);
        }
        
        return view('abtc.patientlist_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        return view('abtc.patientlist_create');
    }

    public function store(Request $request) {
        $request->validate([
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
        ]);
        
        if(AbtcPatient::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->suffix, $request->bdate)) {
            return back()
            ->withInput()
            ->with('msg', 'Unable to register new patient. Patient details already exists on the server.')
            ->with('msgtype', 'danger');
        }
        else {
            $foundunique = false;

            while(!$foundunique) {
                $for_qr = Str::random(20);
                
                $search = AbtcPatient::where('qr', $for_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            if($request->has_bday == 'Yes') {
                $get_age = Carbon::parse($request->bdate)->age;
            }
            else {
                $get_age = $request->age;
            }

            //BLOCK 09999999999
            if($request->contact_number == '09999999999') {
                return back()
                ->withInput()
                ->with('msg', 'Error: Invalid Contact Number. Please fill-out the contact number correctly and try again.')
                ->with('msgtype', 'danger');
            }

            $create = $request->user()->abtcpatient()->create([
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                'suffix' => ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL,
                'bdate' => $request->bdate,
                'philhealth' => $request->philhealth,
                'age' => $get_age,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,
                'address_street' => ($request->filled('address_street')) ? mb_strtoupper($request->address_street) : NULL,
                'address_houseno' => ($request->filled('address_houseno')) ? mb_strtoupper($request->address_houseno) : NULL,
    
                'qr' => $for_qr,
                'remarks' => ($request->filled('remarks')) ? $request->remarks : NULL,
                'ip' => request()->ip(),
            ]);
            
            return redirect()->route('abtc_encode_create_new', ['id' => $create->id])
            ->with('msg', 'Patient '.$create->getName().' was successfully registered. You may continue filling up anti-rabies vaccination details of the patient.')
            ->with('msgtype', 'success');

            /*
            return redirect()->route('abtc_patient_index')
            ->with('msg', 'Patient was added successfully.')
            ->with('pid', $create->id)
            ->with('msgtype', 'success');
            */
        }
    }

    public function edit($id) {
        $data = AbtcPatient::findOrFail($id);

        $bcheck = AbtcBakunaRecords::where('patient_id', $data->id)->first();

        return view('abtc.patientlist_edit', [
            'd' => $data,
            'bcheck' => $bcheck,
        ]);
    }

    public function patient_viewbakunarecords($id) {
        $p = AbtcPatient::findOrFail($id);

        $list = AbtcBakunaRecords::where('patient_id', $p->id)->orderBy('created_at', 'DESC')->paginate(10);

        return view('abtc.patientlist_bakunarecords', [
            'p' => $p,
            'list' => $list,
        ]);
    }
    
    public function update($id, Request $request) {
        $request->validate([

        ]);

        $p = AbtcPatient::findOrFail($id);

        if(AbtcPatient::detectChangeName($request->lname, $request->fname, $request->mname, $request->suffix, $request->bdate, $p->id)) {
            return redirect()->back()
            ->with('msg', 'Unable to update. Patient already exists.')
            ->with('msgtype', 'warning');
        }
        else {
            if($request->has_bday == 'Yes') {
                $get_age = Carbon::parse($request->bdate)->age;
            }
            else {
                $get_age = $request->age;
            }

            $p->lname = mb_strtoupper($request->lname);
            $p->fname = mb_strtoupper($request->fname);
            $p->mname = ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL;
            $p->suffix = ($request->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL;
            $p->bdate = $request->bdate;
            $p->age = $get_age;
            $p->gender = $request->gender;
            $p->contact_number = $request->contact_number;
            $p->philhealth = $request->philhealth;
            $p->address_region_code = $request->address_region_code;
            $p->address_region_text = $request->address_region_text;
            $p->address_province_code = $request->address_province_code;
            $p->address_province_text = $request->address_province_text;
            $p->address_muncity_code = $request->address_muncity_code;
            $p->address_muncity_text = $request->address_muncity_text;
            $p->address_brgy_code = $request->address_brgy_text;
            $p->address_brgy_text = $request->address_brgy_text;
            $p->address_street = ($request->filled('address_street')) ? mb_strtoupper($request->address_street) : NULL;
            $p->address_houseno = ($request->filled('address_houseno')) ? mb_strtoupper($request->address_houseno) : NULL;

            $p->remarks = ($request->filled('remarks')) ? $request->remarks : NULL;

            if($p->isDirty()) {
                $p->updated_by = auth()->user()->id;
                
                $p->save();
            }

            return redirect()->route('abtc_patient_index')
            ->with('msg', 'Patient ['.$p->getName().' - #'.$p->id.'] was updated successfully.')
            ->with('msgtype', 'success');
        }
    }

    public function ajaxList(Request $request) {
        $list = [];
        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);

            $data = AbtcPatient::where(function ($query) use ($search) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','', $search)."%")
                ->orWhere('id', $search);
            })->get();

            foreach($data as $item) {
                if(!is_null($item->bdate)) {
                    $fd = '#'.$item->id.' '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate));
                }
                else {
                    $fd = '#'.$item->id.' '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1);
                }

                array_push($list, [
                    'id' => $item->id,
                    'text' => $fd,
                ]);
            }
        }

        return response()->json($list);
    }

    public function destroy($pid) {
        if(auth()->user()->isAdmin == 1) {
            $p = AbtcPatient::findOrFail($pid);
            $p->delete();

            return redirect()
            ->route('abtc_patient_index')
            ->with('msg', 'Patient record was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function initVaccineBrand(Request $r) {
        $update = User::findOrFail(auth()->user()->id);
        
        $update->abtc_default_vaccinebrand_id = $r->selected_vaccine;
        $update->abtc_default_vaccinebrand_date = date('Y-m-d');

        if($update->isDirty()) {
            $update->save();
        }

        return redirect()->route('abtc_home')
        ->with('msg', 'Vaccine to be used today was initialized successfully. You may now proceed encoding.')
        ->with('msgtype', 'success');
    }

    public function initVaccineStocks(Request $r) {
        
    }

    public function initDailyWastage(Request $r) {
        $check = AbtcVaccineLogs::whereDate('created_at', date('Y-m-d'))
        ->where('vaccine_id', auth()->user()->abtc_default_vaccinebrand_id)
        ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->first();

        if(!$check) {
            $r->user()->abtcvaccinelog()->create([
                'vaccine_id' => auth()->user()->abtc_default_vaccinebrand_id,
                'branch_id' => auth()->user()->abtc_default_vaccinationsite_id,
                'wastage_dose_count' => $r->wastage_dose_count,
            ]);

            /*
            $stock = AbtcVaccineStocks::where('vaccine_id', auth()->user()->abtc_default_vaccinebrand_id)
            ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
            ->decrement('current_stock', ceil($r->wastage_dose_count));
            */
        }
        else {
            if($check->wastage_dose_count == 0) {
                $check->wastage_dose_count = $r->wastage_dose_coun;

                $check->save();
            }
        }

        return redirect()->route('abtc_home')
        ->with('msg', 'Daily Wastage was successfully submitted.')
        ->with('msgtype', 'success');
    }
}