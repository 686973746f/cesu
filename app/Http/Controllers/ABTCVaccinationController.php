<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AbtcPatient;
use App\Models\AbtcVaccineBrand;
use Illuminate\Http\Request;
use App\Models\AbtcVaccinationSite;
use App\Models\AbtcBakunaRecords;

class ABTCVaccinationController extends Controller
{
    public function search_init(Request $request) {
        $request->validate([
            'patient_id' => 'required',
        ]);

        $id = $request->patient_id;

        $p = AbtcPatient::findOrFail($id);

        $data = AbtcBakunaRecords::where('patient_id', $p->id)->orderBy('created_at', 'DESC')->first();

        if($data) {
            return redirect()->route('abtc_encode_existing', ['id' => $data->patient->id]);
        }
        else {
            return redirect()->route('abtc_encode_create_new', [
                'id' => $p->id,
            ])
            ->with('msg', 'No Existing Vaccination Records found. You may continue encoding.')
            ->with('msgtype', 'success');
        }
    }

    public function encode_existing($id) {
        $p = AbtcPatient::findOrFail($id);

        $data = AbtcBakunaRecords::where('patient_id', $p->id)->orderBy('created_at', 'DESC')->first();

        return view('abtc.encode_existing', ['d' => $data]);
    }

    public function create_new($id) {
        $p = AbtcPatient::findOrFail($id);

        $data = AbtcBakunaRecords::where('patient_id', $p->id)->first();

        if(!$data) {
            $vblist = AbtcVaccineBrand::where('enabled', 1)->orderBy('brand_name', 'ASC')->get();
            $vslist = AbtcVaccinationSite::where('enabled', 1)->orderBy('id', 'ASC')->get();

            return view('abtc.encode_new', [
                'd' => $p,
                'vblist' => $vblist,
                'vslist' => $vslist,
            ]);
        }
        else {
            return abort(401);
        }
    }

    public function create_store($id, Request $request) {
        $request->validate([
            'vaccination_site_id' => 'required|numeric',
            'case_date' => 'required|date',
            'case_location' => 'nullable',
            'animal_type' => 'required',
            'animal_type_others' => ($request->animal_type == 'O') ? 'required' : 'nullable',
            'bite_date' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
            'bite_type' => 'required',
            'body_site' => 'nullable',
            'category_level' => 'required',
            'washing_of_bite' => 'required',
            'rig_date_given' => 'nullable|date',
            'pep_route' => 'required',
            'brand_name' => 'required',
            'outcome' => 'required',
            'biting_animal_status' => 'required',
            'remarks' => 'nullable',
        ]);

        $check = AbtcBakunaRecords::where('patient_id', $id)
        ->where(function($q) use ($request) {
            $q->whereDate('created_at', date('Y-m-d'))
            ->orWhereDate('d0_date', $request->d0_date);
        })->first();

        if(!($check)) {
            //Check if Booster Dose (If May Case na dati)
            $booster_check = AbtcBakunaRecords::where('patient_id', $id)->where('outcome', 'C')->first();
            if($booster_check) {
                $is_booster = 1;
            }
            else {
                //Override Booster
                if($request->if_booster == 'Y') {
                    $is_booster = 1;
                }
                else {
                    $is_booster = 0;
                }
            }

            if(date('Y', strtotime($request->case_date)) != date('Y')) {
                $case_id = date('Y', strtotime($request->case_date)).'-'.(AbtcBakunaRecords::whereYear('created_at', date('Y', strtotime($request->case_date)))->count() + 1);
            }
            else {
                $case_id = date('Y').'-'.(AbtcBakunaRecords::whereYear('created_at', date('Y'))->count() + 1);
            }

            //Days Calculation (Skip and Wednesdays, Saturdays and Sundays due to Government Office Hours)
            $base_date = $request->d0_date;

            $set_d3_date = Carbon::parse($request->d0_date)->addDays(3);

            if($set_d3_date->dayOfWeek == Carbon::WEDNESDAY) {
                $set_d3_date = Carbon::parse($set_d3_date)->addDays(1);
            }
            else if($set_d3_date->dayOfWeek == Carbon::SATURDAY) {
                $set_d3_date = Carbon::parse($set_d3_date)->addDays(2);
            }
            else if($set_d3_date->dayOfWeek == Carbon::SUNDAY) {
                $set_d3_date = Carbon::parse($set_d3_date)->addDays(1);
            }

            $set_d7_date = Carbon::parse($request->d0_date)->addDays(7);

            if($set_d7_date->dayOfWeek == Carbon::WEDNESDAY) {
                $set_d7_date = Carbon::parse($set_d7_date)->addDays(1);
            }
            else if($set_d7_date->dayOfWeek == Carbon::SATURDAY) {
                $set_d7_date = Carbon::parse($set_d7_date)->addDays(2);
            }
            else if($set_d7_date->dayOfWeek == Carbon::SUNDAY) {
                $set_d7_date = Carbon::parse($set_d7_date)->addDays(1);
            }

            $set_d14_date = Carbon::parse($request->d0_date)->addDays(14);

            if($set_d14_date->dayOfWeek == Carbon::WEDNESDAY) {
                $set_d14_date = Carbon::parse($set_d14_date)->addDays(1);
            }
            else if($set_d14_date->dayOfWeek == Carbon::SATURDAY) {
                $set_d14_date = Carbon::parse($set_d14_date)->addDays(2);
            }
            else if($set_d14_date->dayOfWeek == Carbon::SUNDAY) {
                $set_d14_date = Carbon::parse($set_d14_date)->addDays(1);
            }

            $set_d28_date = Carbon::parse($request->d0_date)->addDays(28);

            if($set_d28_date->dayOfWeek == Carbon::WEDNESDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
            }
            else if($set_d28_date->dayOfWeek == Carbon::SATURDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(2);
            }
            else if($set_d28_date->dayOfWeek == Carbon::SUNDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
            }

            $f = $request->user()->abtcbakunarecord()->create([
                'patient_id' => $id,
                'vaccination_site_id' => $request->vaccination_site_id,
                'case_id' => $case_id,
                'is_booster' => $is_booster,
                'case_date' => $request->case_date,
                'case_location' => ($request->filled('case_location')) ? mb_strtoupper($request->case_location) : NULL,
                'animal_type' => $request->animal_type,
                'animal_type_others' => ($request->animal_type == 'O') ? mb_strtoupper($request->animal_type_others) : NULL,
                'if_animal_vaccinated' => ($request->if_animal_vaccinated == 'Y') ? 1 : 0,
                'bite_date' => $request->bite_date,
                'bite_type' => $request->bite_type,
                'body_site' => ($request->filled('body_site')) ? mb_strtoupper($request->body_site) : NULL,
                'category_level' => $request->category_level,
                'washing_of_bite' => ($request->washing_of_bite == 'Y') ? 1 : 0,
                'rig_date_given' => $request->rig_date_given,

                'pep_route' => $request->pep_route,
                'brand_name' => $request->brand_name,
                'd0_date' => $request->d0_date,
                'd0_done' => 1,
                'd0_brand' => $request->brand_name,
                'd3_date' => $set_d3_date->format('Y-m-d'),
                'd3_brand' => $request->brand_name,
                'd7_date' => $set_d7_date->format('Y-m-d'),
                'd7_brand' => $request->brand_name,
                'd14_date' => $set_d14_date->format('Y-m-d'),
                'd14_brand' => $request->brand_name,
                'd28_date' => $set_d28_date->format('Y-m-d'),
                'd28_brand' => $request->brand_name,

                'outcome' => $request->outcome,
                'biting_animal_status' => $request->biting_animal_status,
                'remarks' => $request->remarks,
            ]);

            return view('abtc.encode_finished', [
                'f' => $f,
            ])
            ->with('msg', 'You have finished your 1st Dose of your Anti-Rabies Vaccine.')
            ->with('dose', 1);
        }
        else {
            return redirect()->route('abtc_home')
            ->with('msg', 'You are not allowed to do that')
            ->with('msgtype', 'warning');
        }
    }

    public function encode_edit($bakuna_id) {
        $p = AbtcBakunaRecords::findOrFail($bakuna_id);

        $vblist = AbtcVaccineBrand::orderBy('brand_name', 'ASC')->get();
        $vslist = AbtcVaccinationSite::orderBy('id', 'ASC')->get();

        return view('abtc.encode_edit', [
            'd' => $p,
            'vblist' => $vblist,
            'vslist' => $vslist,
        ]);
    }

    public function encode_update($bakuna_id, Request $request) {
        $request->validate([
            'vaccination_site_id' => 'required|numeric',
            'case_date' => 'required|date',
            'case_location' => 'nullable',
            'animal_type' => 'required',
            'animal_type_others' => ($request->animal_type == 'O') ? 'required' : 'nullable',
            'bite_date' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
            'bite_type' => 'required',
            'body_site' => 'nullable',
            'category_level' => 'required',
            'washing_of_bite' => 'required',
            'rig_date_given' => 'nullable|date',
            'pep_route' => 'required',
            'brand_name' => 'required',
            'outcome' => 'required',
            'biting_animal_status' => 'required',
            'remarks' => 'nullable',
        ]);

        $b = AbtcBakunaRecords::findOrFail($bakuna_id);

        $b->is_booster = ($request->is_booster == 'Y') ? 1 : 0;
        $b->vaccination_site_id = $request->vaccination_site_id;
        $b->case_date = $request->case_date;
        $b->case_location = ($request->filled('case_location')) ? mb_strtoupper($request->case_location) : NULL;
        $b->animal_type = $request->animal_type;
        $b->animal_type_others = ($request->animal_type == 'O') ? mb_strtoupper($request->animal_type_others) : NULL;
        $b->if_animal_vaccinated = ($request->if_animal_vaccinated == 'Y') ? 1 : 0;
        $b->bite_date = $request->bite_date;
        $b->bite_type = $request->bite_type;
        $b->body_site = ($request->filled('body_site')) ? mb_strtoupper($request->body_site) : NULL;
        $b->category_level = $request->category_level;
        $b->washing_of_bite = ($request->washing_of_bite == 'Y') ? 1 : 0;
        $b->rig_date_given = $request->rig_date_given;

        $b->pep_route = $request->pep_route;
        $b->brand_name = $request->brand_name;

        $b->outcome = $request->outcome;
        $b->biting_animal_status = $request->biting_animal_status;
        $b->remarks = $request->remarks;

        //Checking of Outcome on Category 3 with Erig
        if($b->category_level == 3 && $b->d28_done == 1 && !is_null($b->rig_date_given)) {
            $b->outcome == 'C';
        }

        if($b->outcome == 'INC' && $b->is_booster == 1 && $b->d0_done == 1 && $b->d3_done == 1) {
            $b->outcome == 'C';
        }

        if($b->isDirty()) {
            $b->updated_by = auth()->user()->id;
            
            $b->save();
        }

        return redirect()->back()
        ->with('msg', 'Patient Vaccination Information was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function bakuna_again($patient_id) {
        $b = AbtcBakunaRecords::where('patient_id', $patient_id)
        ->where('outcome', 'C')
        ->orderBy('created_at', 'DESC')
        ->first();

        if($b) {
            $vblist = AbtcVaccineBrand::where('enabled', 1)->orderBy('brand_name', 'ASC')->get();
            $vslist = AbtcVaccinationSite::where('enabled', 1)->orderBy('id', 'ASC')->get();
            
            //Check duration 3 months
            if(date('Y-m-d', strtotime($b->b0_date.' + 90 Days')) < date('Y-m-d')) {
                return view('abtc.encode_new', [
                    'd' => $b->patient,
                    'vblist' => $vblist,
                    'vslist' => $vslist,
                ]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'Unable to process. Patient was vaccinated 90 Days (3 Months) ago. Booster is not yet required.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return abort(401);
        }
    }

    public function encode_process($br_id, $dose) {
        $get_br = AbtcBakunaRecords::findOrFail($br_id);

        if(!is_null($get_br->brand_name)) {
            if($dose == 1) {
                if($get_br->ifAbleToProcessD0() == 'Y') {
                    $get_br->d0_done = 1;
                    $get_br->d0_brand = $get_br->brand_name;
                }
                else {
                    return abort(401);
                }

                if($get_br->patient->register_status == 'PENDING') {
                    $c = AbtcPatient::findOrFail($get_br->patient_id);
                    
                    $c->created_by = auth()->user()->id;
                    $c->register_status = 'VERIFIED';
                    $c->save();
                }
    
                $msg = 'You have finished your 1st Dose of your Anti-Rabies Vaccine.';
            }
            else if($dose == 2) { //Day 3
                if($get_br->ifAbleToProcessD3() == 'Y') {
                    $get_br->d3_done = 1;
                    $get_br->d3_brand = $get_br->brand_name;
                }
                else {
                    return abort(401);
                }
    
                if($get_br->is_booster == 1) {
                    $get_br->outcome = 'C';
                    $msg = 'You have finished your Booster Dose of your Anti-Rabies Vaccine.';
                }
                else {
                    $msg = 'You have finished your 2nd Dose of your Anti-Rabies Vaccine.';
    
                    //Check if delay ang d3 bakuna then move next schedules
                    if($get_br->d3_date != date('Y-m-d')) {
                        $ad = Carbon::parse($get_br->d3_date);
                        $bd = Carbon::parse(date('Y-m-d'));
    
                        $date_diff = $ad->diffInDays($bd);
                        if($date_diff >= 3) {
                            $get_br->d3_date = date('Y-m-d');
                            $get_br->d7_date = Carbon::parse($get_br->d7_date)->addDays(4)->format('Y-m-d');
                            $get_br->d14_date = Carbon::parse($get_br->d14_date)->addDays(4)->format('Y-m-d');
                            $get_br->d28_date = Carbon::parse($get_br->d28_date)->addDays(4)->format('Y-m-d');
                        }
                    }
                }
            }
            else if($dose == 3) { //Day 7
                if($get_br->d7_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 0) {
                    $get_br->d7_done = 1;
                    $get_br->d7_brand = $get_br->brand_name;
                }
                else {
                    return abort(401);
                }

                /*
                if($get_br->category_level == 2) {
                    $get_br->outcome = 'C';
                }
                else if($get_br->category_level == 3 && !is_null($get_br->rig_date_given)) {
                    $get_br->outcome = 'C';
                }
                */

                $get_br->outcome = 'C';
    
                $msg = 'You have finished your 3rd Dose of your Anti-Rabies Vaccine.';
            }
            else if($dose == 4 && $get_br->pep_route == 'IM') { //Day 14
                if($get_br->d14_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d14_done == 0) {
                    $get_br->d14_done = 1;
                    $get_br->d14_brand = $get_br->brand_name;
                }
                else {
                    return abort(401);
                }

                $get_br->outcome = 'C';
                
                $msg = 'You have finished your 4th Dose of your Anti-Rabies Vaccine.';
            }
            else if($dose == 5) { //Day 28
                if($get_br->pep_route == 'IM') {
                    if($get_br->d28_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d14_done == 1 && $get_br->d28_done == 0) {
                        $get_br->d28_done = 1;
                        $get_br->d28_brand = $get_br->brand_name;
                    }
                    else {
                        return abort(401);
                    }
                }
                else if($get_br->pep_route == 'ID') { //Skip 14 Day
                    if($get_br->d28_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d28_done == 0) {
                        $get_br->d28_done = 1;
                        $get_br->d28_brand = $get_br->brand_name;
                    }
                    else {
                        return abort(401);
                    }
                }
    
                /*
                if($get_br->category_level == 2) {
                    $get_br->outcome = 'C';
                }
                else if($get_br->category_level == 3 && !is_null($get_br->rig_date_given)) {
                    $get_br->outcome = 'C';
                }
                */

                $get_br->outcome = 'C';
    
                $msg = 'Congratulations. You have completed your doses of Anti-Rabies Vaccine!';
            }
    
            $get_br->save();
    
            return view('abtc.encode_finished', [
                'f' => $get_br,
            ])
            ->with('msg', $msg)
            ->with('dose' , $dose);
        }
        else {
            return redirect()->back()
            ->with('msg', 'Unable to proceed. Please put Vaccine Brand first, click [Save] then try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function qr_quicksearch(Request $request) {
        $sqr = $request->qr;

        $search = AbtcPatient::where('qr', $sqr)
        ->first();

        if($search) {
            //load latest bakuna record

            $b = AbtcBakunaRecords::where('patient_id', $search->id)->orderBy('created_at', 'DESC')->first();
            if($b) {
                return redirect()->route('abtc_encode_existing', ['id' => $search->id]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'No Anti-Rabies Vaccination Record found for '.$search->getName())
                ->with('msgtype', 'warning');
            }
        }
        else {
            $asearch = AbtcBakunaRecords::where('case_id', $sqr)->first();
            if($asearch) {
                return redirect()->route('abtc_encode_edit', $asearch->id)
                ->with('msg', 'Result found with same Registration Number.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->with('msg', 'User does not exist on the server.')
                ->with('msgtype', 'warning');
            }
        }
    }

    public function override_schedule($id) {
        $d = AbtcBakunaRecords::findOrFail($id);
        $vblist = AbtcVaccineBrand::where('enabled', 1)->orderBy('brand_name', 'ASC')->get();

        return view('abtc.encode_schedule_override', [
            'd' => $d,
            'vblist' => $vblist,
        ]);
    }

    public function override_schedule_process($id, Request $request) {
        $d = AbtcBakunaRecords::findOrFail($id);

        $request->validate([
            
        ]);

        $d->d0_date = $request->d0_date;
        $d->d0_brand = $request->d0_brand;

        if($d->d0_done == 0) {
            if($request->d0_ostatus == 'C') {
                $d->d0_done = 1;
            }
        }

        $d->d3_date = $request->d3_date;
        $d->d3_brand = $request->d3_brand;

        if($d->d3_done == 0) {
            if($request->d3_ostatus == 'C') {
                $d->d0_done = 1;
                $d->d3_done = 1;

                if($d->outcome == 'INC' && $d->is_booster == 1) {
                    $d->outcome = 'C';
                }
            }
        }

        if($d->is_booster == 0) {

            $d->d7_date = $request->d7_date;
            $d->d7_brand = $request->d7_brand;

            if($d->d7_done == 0) {
                if($request->d7_ostatus == 'C') {
                    $d->d0_done = 1;
                    $d->d3_done = 1;
                    $d->d7_done = 1;

                    if($d->outcome == 'INC' && $d->is_booster == 0) {
                        $d->outcome = 'C';
                    }
                }
            }
    
            if($d->pep_route == 'IM') {

                $d->d14_date = $request->d14_date;
                $d->d14_brand = $request->d14_brand;
                
                if($d->d14_done == 0) {
                    if($request->d14_ostatus == 'C') {
                        $d->d0_done = 1;
                        $d->d3_done = 1;
                        $d->d7_done = 1;
                        $d->d14_done = 1;

                        if($d->outcome == 'INC' && $d->is_booster == 0) {
                            $d->outcome = 'C';
                        }
                    }
                }
            }

            $d->d28_date = $request->d28_date;
            $d->d28_brand = $request->d28_brand;
    
            if($d->d28_done == 0) {
                if($request->d28_ostatus == 'C') {
                    $d->d0_done = 1;
                    $d->d3_done = 1;
                    $d->d7_done = 1;
                    $d->d14_done = 1;
                    $d->d28_done = 1;

                    if($d->outcome == 'INC' && $d->is_booster == 0) {
                        $d->outcome = 'C';
                    }
                }
            }
        }

        if($d->isDirty()) {
            if(is_null($d->created_by)) {
                $d->created_by = auth()->user()->id;
            }

            if($request->d0_date > date('Y-m-d') && $request->d0_ostatus == 'C') {
                return redirect()->back()
                ->with('msg', 'Day 0 cannot be marked as completed because the date is ahead the present date.')
                ->with('msgtype', 'warning');
            }
            if($request->d3_date > date('Y-m-d') && $request->d3_ostatus == 'C') {
                return redirect()->back()
                ->with('msg', 'Day 3 cannot be marked as completed because the date is ahead the present date.')
                ->with('msgtype', 'warning');
            }
            if($request->d7_date > date('Y-m-d') && $request->d7_ostatus == 'C') {
                return redirect()->back()
                ->with('msg', 'Day 7 cannot be marked as completed because the date is ahead the present date.')
                ->with('msgtype', 'warning');
            }
            if($request->d14_date > date('Y-m-d') && $request->d14_ostatus == 'C') {
                return redirect()->back()
                ->with('msg', 'Day 14 cannot be marked as completed because the date is ahead the present date.')
                ->with('msgtype', 'warning');
            }
            if($request->d28_date > date('Y-m-d') && $request->d28_ostatus == 'C') {
                return redirect()->back()
                ->with('msg', 'Day 28 cannot be marked as completed because the date is ahead the present date.')
                ->with('msgtype', 'warning');
            }

            $d->save();
        }

        return redirect()->route('abtc_encode_edit', ['br_id' => $d->id])
        ->with('msg', 'Schedule has been manually changed successfully.')
        ->with('msgtype', 'success');
    }

    public function schedule_index() {
        if(is_null(auth()->user()->abtc_default_vaccinationsite_id)) {
            return redirect()->back()
            ->with('msg', 'Please set default vaccination site first before proceeding. Option is available on the [Account Options]')
            ->with('msgtype', 'warning');
        }

        if(request()->input('d')) {
            $sdate = request()->input('d');
        }
        else {
            $sdate = date('Y-m-d');
        }

        $new = AbtcBakunaRecords::whereDate('d0_date', $sdate)
        ->where('d0_done', 0)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->orderBy('created_at', 'ASC')
        ->get();

        /*
        $ff = AbtcBakunaRecords::where(function ($q) {
            $q->whereDate('d3_date', date('Y-m-d'))
            ->orWhereDate('d7_date', date('Y-m-d'))
            ->orWhereDate('d14_date', date('Y-m-d'))
            ->orWhereDate('d28_date', date('Y-m-d'));
        })
        ->orderBy('created_at', 'ASC')
        ->get();
        */

        $ff = AbtcBakunaRecords::where(function ($q) use ($sdate) {
            $q->where(function ($r) use ($sdate) {
                $r->where('d3_date', $sdate)
                ->where('d3_done', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->where('d7_date', $sdate)
                ->where('d7_done', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->where('d14_date', $sdate)
                ->where('d14_done', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->where('d28_date', $sdate)
                ->where('d28_done', 0);
            });
        })->where('outcome', 'INC')
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->orderBy('created_at', 'ASC')
        ->get();

        return view('abtc.schedule_index', [
            'new' => $new,
            'ff' => $ff,
        ]);
    }

    public function print_view($bid) {
        $f = AbtcBakunaRecords::findOrFail($bid);

        return view('abtc.encode_finished', [
            'f' => $f,
        ]);
    }
}
