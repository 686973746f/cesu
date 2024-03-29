<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AbtcPatient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ABTCWalkInRegistrationController extends Controller
{
    public function walkin_part1() {
        //LAGYAN TIME LIMIT
        /*
        if(time() >= strtotime('00:00:00') && time() <= strtotime('06:30:00')) {
            return 'Registration starts at 06:30 AM. Please come back later.';
        }
        else if(time() >= strtotime('16:00:00') && time() <= strtotime('23:59:59')) {
            return 'Registration starts tomorrow ('.date('m/d/Y').') at 06:30 AM. Please come back later.';
        }
        */

        if(request()->input('v')) {
            $c = request()->input('v');

            $vid = AbtcVaccinationSite::where('referral_code', $c)->first();

            Session::put('vrefcode', $c);
            Session::put('vaccination_site_name', $vid->site_name);
            Session::put('vaccination_site_id', $vid->id);
            Session::put('regisphase', 'notyet');
            
            return view('abtc.walkin_part1');
        }
        else {
            return 'Referral Code required in order to proceed. Please go to the nearest ABTC for the link.';
        }
    }

    public function walkin_part2() {
        if(session('regisphase') == 'done') {
            return view('abtc.walkin_part3', [
                'd' => AbtcBakunaRecords::findOrFail(session('regisid')),
                'pila_count' => session('pila_count'),
            ]);
        }

        $v = AbtcVaccinationSite::findOrFail(session('vaccination_site_id'));

        $lname = mb_strtoupper(request()->input('lname'));
        $fname = mb_strtoupper(request()->input('fname'));
        $mname = (request()->filled('mname')) ? mb_strtoupper(request()->input('mname')) : NULL;
        $suffix = (request()->filled('suffix')) ? mb_strtoupper(request()->input('suffix')) : NULL;
        $bdate = request()->input('bdate');

        /*
        $b = AbtcPatient::where('lname', $lname)
        ->where('fname', $fname)
        ->where(function ($q) use ($mname) {
            $q->where('mname', $mname)
            ->orWhereNull('mname');
        })
        ->where(function ($q) use ($suffix) {
            $q->where('suffix', $suffix)
            ->orWhereNull('suffix');
        })
        ->first();
        */

        $b = AbtcPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate);

        if($b) {
            $br = AbtcBakunaRecords::where('patient_id', $b->id)->orderBy('created_at', 'DESC')->first();
            
            if($br) {
                if($br->outcome == 'C') {
                    if(date('Y-m-d') > Carbon::parse($br->d0_date)->addDays(90)) {
                        return view('abtc.walkin_part2');
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Hindi maaaring magpatuloy. Hindi pa lagpas ng 90 na araw ang iyong huling bakuna.')
                        ->with('msgtype', 'danger');
                    }
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Hindi maaaring magpatuloy. Ikaw ay kasalukuyang may tinatapos pa na bakuna. Makipag-usap sa ABTC Coordinator para sa iba pang detalye.')
                    ->with('msgtype', 'danger');
                }
            }
            else {
                return view('abtc.walkin_part2');
            }
        }
        else {
            return view('abtc.walkin_part2');
        }
    }

    public function walkin_part3(Request $request) {
        if(session('regisphase') == 'done') {
            return view('abtc.walkin_part3', [
                'd' => AbtcBakunaRecords::findOrFail(session('regisid')),
                'pila_count' => session('pila_count'),
            ]);
        }

        $v = AbtcVaccinationSite::findOrFail(session('vaccination_site_id'));

        $request->validate([

        ]);

        //Days Calculation (Skip and Wednesdays, Saturdays and Sundays due to Government Office Hours)
        if(time() >= strtotime('11:00')) {
            $base_date = date('Y-m-d', strtotime('+1 Day'));
        }
        else {
            $base_date = date('Y-m-d');
        }

        /*
        $b = AbtcPatient::where('lname', $request->lname)
        ->where('fname', $request->fname)
        ->where(function ($q) use ($request) {
            $q->where('mname', $request->mname)
            ->orWhereNull('mname');
        })
        ->where(function ($q) use ($request) {
            $q->where('suffix', $request->suffix)
            ->orWhereNull('suffix');
        })
        ->whereDate('bdate', $request->bdate)
        ->first();
        */

        $lname = mb_strtoupper($request->lname);
        $fname = mb_strtoupper($request->fname);
        $mname = (request()->filled('mname')) ? mb_strtoupper($request->mname) : NULL;
        $suffix = (request()->filled('suffix')) ? mb_strtoupper($request->suffix) : NULL;
        $bdate = request()->input('bdate');

        $b = AbtcPatient::ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate);

        if($b) {
            $p = $b;
            $data = AbtcBakunaRecords::where('patient_id', $p->id)->orderBy('created_at', 'DESC')->first();

            if($data) {
                if($data->outcome == 'C') {
                    if(date('Y-m-d') > Carbon::parse($data->d0_date)->addDays(90)) {
                        //Proceed
                        $is_booster = 1;
                    }
                    else {
                        return redirect()->back()
                        ->with('msg', 'Hindi maaaring magpatuloy. Hindi pa lagpas ng 90 na araw ang iyong huling bakuna.')
                        ->with('msgtype', 'danger');
                    }
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Hindi maaaring magpatuloy. Ikaw ay kasalukuyang may tinatapos pa na bakuna.')
                    ->with('msgtype', 'danger');
                }
            }
            else {
                if($request->is_booster == 'Y') {
                    $is_booster = 1;
                }
                else {
                    $is_booster = 0;
                }
            }
        }
        else {
            if($request->is_booster == 'Y') {
                $is_booster = 1;
            }
            else {
                $is_booster = 0;
            }

            $foundunique = false;

            while(!$foundunique) {
                $for_qr = Str::random(20);
                
                $search = AbtcPatient::where('qr', $for_qr)->first();
                if(!$search) {
                    $foundunique = true;
                }
            }

            $p = AbtcPatient::create([
                'register_status' => 'PENDING',
                'lname' => $lname,
                'fname' => $fname,
                'mname' => ($request->filled('mname')) ? $mname : NULL,
                'suffix' => ($request->filled('suffix')) ? $suffix : NULL,
                'bdate' => $request->bdate,
                'age' => Carbon::parse($request->bdate)->age,
                'gender' => $request->gender,
                'contact_number' => $request->contact_number,
                'philhealth' => $request->philhealth,
                'address_region_code' => $request->address_region_code,
                'address_region_text' => $request->address_region_text,
                'address_province_code' => $request->address_province_code,
                'address_province_text' => $request->address_province_text,
                'address_muncity_code' => $request->address_muncity_code,
                'address_muncity_text' => $request->address_muncity_text,
                'address_brgy_code' => $request->address_brgy_text,
                'address_brgy_text' => $request->address_brgy_text,
                'address_street' => mb_strtoupper($request->address_street),
                'address_houseno' => mb_strtoupper($request->address_houseno),
    
                'qr' => $for_qr,
                'ip' => request()->ip(),
            ]);
        }

        $case_id = date('Y').'-'.(AbtcBakunaRecords::whereYear('case_date', date('Y'))
        ->where('vaccination_site_id', session('vaccination_site_id'))
        ->count() + 1);

        $set_d0_date = Carbon::parse($base_date);
        
        if($set_d0_date->dayOfWeek == Carbon::WEDNESDAY) {
            $set_d0_date = Carbon::parse($set_d0_date)->addDays(1);
        }
        else if($set_d0_date->dayOfWeek == Carbon::SATURDAY) {
            $set_d0_date = Carbon::parse($set_d0_date)->addDays(2);
        }
        else if($set_d0_date->dayOfWeek == Carbon::SUNDAY) {
            $set_d0_date = Carbon::parse($set_d0_date)->addDays(1);
        }

        $base_date = Carbon::parse($set_d0_date)->format('Y-m-d');

        $set_d3_date = Carbon::parse($base_date)->addDays(3);

        if($set_d3_date->dayOfWeek == Carbon::WEDNESDAY) {
            $set_d3_date = Carbon::parse($set_d3_date)->addDays(1);
        }
        else if($set_d3_date->dayOfWeek == Carbon::SATURDAY) {
            $set_d3_date = Carbon::parse($set_d3_date)->addDays(2);
        }
        else if($set_d3_date->dayOfWeek == Carbon::SUNDAY) {
            $set_d3_date = Carbon::parse($set_d3_date)->addDays(1);
        }

        $set_d7_date = Carbon::parse($base_date)->addDays(7);

        if($set_d7_date->dayOfWeek == Carbon::WEDNESDAY) {
            $set_d7_date = Carbon::parse($set_d7_date)->addDays(1);
        }
        else if($set_d7_date->dayOfWeek == Carbon::SATURDAY) {
            $set_d7_date = Carbon::parse($set_d7_date)->addDays(2);
        }
        else if($set_d7_date->dayOfWeek == Carbon::SUNDAY) {
            $set_d7_date = Carbon::parse($set_d7_date)->addDays(1);
        }

        $set_d14_date = Carbon::parse($base_date)->addDays(14);

        if($set_d14_date->dayOfWeek == Carbon::WEDNESDAY) {
            $set_d14_date = Carbon::parse($set_d14_date)->addDays(1);
        }
        else if($set_d14_date->dayOfWeek == Carbon::SATURDAY) {
            $set_d14_date = Carbon::parse($set_d14_date)->addDays(2);
        }
        else if($set_d14_date->dayOfWeek == Carbon::SUNDAY) {
            $set_d14_date = Carbon::parse($set_d14_date)->addDays(1);
        }

        $set_d28_date = Carbon::parse($base_date)->addDays(28);

        if($set_d28_date->dayOfWeek == Carbon::WEDNESDAY) {
            $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
        }
        else if($set_d28_date->dayOfWeek == Carbon::SATURDAY) {
            $set_d28_date = Carbon::parse($set_d28_date)->addDays(2);
        }
        else if($set_d28_date->dayOfWeek == Carbon::SUNDAY) {
            $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
        }

        $br = AbtcBakunaRecords::create([
            'patient_id' => $p->id,
            'vaccination_site_id' => session('vaccination_site_id'),
            'case_id' => $case_id,
            'is_booster' => $is_booster,
            'case_date' => date('Y-m-d'),
            'case_location' => ($request->filled('case_location')) ? mb_strtoupper($request->case_location) : NULL,
            'animal_type' => $request->animal_type,
            'animal_type_others' => ($request->animal_type == 'O') ? mb_strtoupper($request->animal_type_others) : NULL,
            'if_animal_vaccinated' => ($request->if_animal_vaccinated == 'Y') ? 1 : 0,
            'bite_date' => $request->bite_date,
            'bite_type' => 'B',
            'body_site' => $request->body_site,
            'category_level' => ($request->ifbleeding == 'Y') ? 3 : 2,
            'washing_of_bite' => ($request->washing_of_bite == 'Y') ? 1 : 0,

            'pep_route' => 'ID',
            'd0_date' => $base_date,
            'd0_done' => 0,
            'd3_date' => $set_d3_date->format('Y-m-d'),
            'd7_date' => $set_d7_date->format('Y-m-d'),
            'd14_date' => $set_d14_date->format('Y-m-d'),
            'd28_date' => $set_d28_date->format('Y-m-d'),

            'outcome' => 'INC',
            'biting_animal_status' => 'N/A',
        ]);

        $pila_count = AbtcBakunaRecords::where('created_at', '<', $br->created_at)
        ->whereDate('d0_date', $base_date)
        ->count();

        $pila_count = $pila_count + 1;

        Session::put('regisphase', 'done');
        Session::put('regisid', $br->id);
        Session::put('pila_count', $pila_count);

        return view('abtc.walkin_part3', [
            'd' => $br,
            'pila_count' => $pila_count,
        ]);
    }

    public function qr_process($qr) {
        $s = AbtcPatient::where('qr', $qr)->first();

        if($s) {
            $b = AbtcBakunaRecords::where('patient_id', $s->id)->latest()->first();

            if(Auth::check()) {
                return redirect()->route('abtc_encode_edit', $b->id);
            }
            else {
                return view('abtc.qr_init', [
                    'found' => 1,
                    'b' => $b,
                ]);
            }
        }
        else {
            return view('abtc.qr_init', [
                'found' => 0,
            ]);
        }
    }

    public function selfReportIndex() {
        return view('abtc.selfreport.index');
    }

    public function selfReportStore(Request $r) {
        $r->validate([
            'lname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|max:50',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
        ]);
        //CHECK IF PATIENT ALREADY EXISTS

        if(AbtcPatient::ifDuplicateFound($r->lname, $r->fname, $r->mname, $r->suffix, $r->bdate)) {
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

            $get_age = Carbon::parse($r->bdate)->age;

            //BLOCK 09999999999
            if($r->contact_number == '09999999999') {
                return back()
                ->withInput()
                ->with('msg', 'Error: Invalid Contact Number. Please fill-out the contact number correctly and try again.')
                ->with('msgtype', 'danger');
            }

            //CREATE PATIENT DETAILS
            $create_patient = AbtcPatient::create([
                'register_status' => 'PENDING_REFERRAL',
                'enabled' => 0,
                'lname' => mb_strtoupper($r->lname),
                'fname' => mb_strtoupper($r->fname),
                'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
                'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
                'bdate' => $r->bdate,
                //'philhealth' => $r->philhealth,
                'age' => $get_age,
                'gender' => $r->gender,
                'contact_number' => $r->contact_number,
                'address_region_code' => $r->address_region_code,
                'address_region_text' => $r->address_region_text,
                'address_province_code' => $r->address_province_code,
                'address_province_text' => $r->address_province_text,
                'address_muncity_code' => $r->address_muncity_code,
                'address_muncity_text' => $r->address_muncity_text,
                'address_brgy_code' => $r->address_brgy_text,
                'address_brgy_text' => $r->address_brgy_text,
                'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
                'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
    
                'qr' => $for_qr,
                'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
                'ip' => request()->ip(),
                
                'referred_from' => mb_strtoupper($r->referred_from),
                'referred_date' => $r->referred_date,
            ]);

            //Check if Booster Dose (If May Case na dati)
            $booster_check = AbtcBakunaRecords::where('patient_id', $create_patient)->where('outcome', 'C')->first();
            if($booster_check) {
                $is_booster = 1;
            }
            else {
                //Override Booster
                if($r->is_booster == 'Y') {
                    $is_booster = 1;
                }
                else {
                    $is_booster = 0;
                }
            }

            if(date('Y', strtotime($r->case_date)) != date('Y')) {
                $case_id = date('Y', strtotime($r->case_date)).'-'.(AbtcBakunaRecords::whereYear('case_date', date('Y', strtotime($r->case_date)))
                ->where('vaccination_site_id', $r->vaccination_site_id)
                ->count() + 1);
            }
            else {
                $case_id = date('Y').'-'.(AbtcBakunaRecords::whereYear('case_date', date('Y'))
                ->where('vaccination_site_id', $r->vaccination_site_id)
                ->count() + 1);
            }

            //$is_preexp = 0;
            $bite_date = $r->bite_date;
            $case_location = ($r->filled('case_location')) ? mb_strtoupper($r->case_location) : NULL;
            $if_animal_vaccinated = ($r->if_animal_vaccinated == 'Y') ? 1 : 0;
            $animal_type = $r->animal_type;
            $bite_type = $r->bite_type;
            //$category_level = (!is_null($r->rig_date_given)) ? 3 : $r->category_level;

            //CREATE BAKUNA DETAILS
            $create_record = AbtcBakunaRecords::create([
                'patient_id' => $create_patient->id,
                //'vaccination_site_id' => $r->vaccination_site_id,
                //'case_id' => $case_id,
                'is_booster' => $is_booster,
                //'is_preexp' => $is_preexp,
                'case_date' => $r->case_date,
                'case_location' => $case_location,
                'animal_type' => $animal_type,
                'animal_type_others' => ($r->animal_type == 'O') ? mb_strtoupper($r->animal_type_others) : NULL,
                'if_animal_vaccinated' => $if_animal_vaccinated,
                'bite_date' => $bite_date,
                'bite_type' => $bite_type,
                'body_site' => ($r->filled('body_site')) ? mb_strtoupper($r->body_site) : NULL,
                //'category_level' => $category_level,
                'washing_of_bite' => ($r->washing_of_bite == 'Y') ? 1 : 0,
                //'rig_date_given' => $r->rig_date_given,

                //'pep_route' => $r->pep_route,
                //'brand_name' => $r->brand_name,
                //'d0_date' => $r->d0_date,
                //'d0_done' => 1,
                //'d0_vaccinated_inbranch' => ($r->d0_vaccinated_inbranch == 'Y') ? 1 : 0,
                //'d0_brand' => $r->brand_name,
                //'d3_date' => $set_d3_date->format('Y-m-d'),
                //'d3_brand' => $r->brand_name,
                //'d7_date' => $set_d7_date->format('Y-m-d'),
                //'d7_brand' => $r->brand_name,
                //'d14_date' => $set_d14_date->format('Y-m-d'),
                //'d14_brand' => $r->brand_name,
                //'d28_date' => $set_d28_date->format('Y-m-d'),
                //'d28_brand' => $r->brand_name,

                'outcome' => 'INC',
                'biting_animal_status' => 'N/A',

                //'date_died' => ($r->outcome == 'D') ? $r->date_died : NULL,
                //'animal_died_date' => ($r->biting_animal_status == 'DEAD') ? $r->animal_died_date : NULL,
                'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
            ]);

            //SHOW SUCCESS PAGE
            return view('abtc.selfreport.submit_complete');
        }
    }
}
