<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AbtcPatient;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use App\Models\AbtcVaccineStocks;
use App\Models\SiteSettings;
use PhpOffice\PhpWord\TemplateProcessor;

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
            'case_date' => 'required|date|before_or_equal:today',
            'case_location' => 'nullable',
            'animal_type' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'animal_type_others' => ($request->animal_type == 'O') ? 'required' : 'nullable',
            'bite_date' => ($request->is_preexp == 'N') ? 'required|date|after_or_equal:2000-01-01|before_or_equal:today' : 'nullable',
            'bite_type' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'body_site' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'category_level' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'washing_of_bite' => 'required',
            'rig_date_given' => 'nullable|date|before_or_equal:today',
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
                if($request->is_booster == 'Y') {
                    $is_booster = 1;
                }
                else {
                    $is_booster = 0;
                }
            }

            if(date('Y', strtotime($request->case_date)) != date('Y')) {
                $case_id = date('Y', strtotime($request->case_date)).'-'.(AbtcBakunaRecords::whereYear('case_date', date('Y', strtotime($request->case_date)))
                ->where('vaccination_site_id', $request->vaccination_site_id)
                ->count() + 1);
            }
            else {
                $case_id = date('Y').'-'.(AbtcBakunaRecords::whereYear('case_date', date('Y'))
                ->where('vaccination_site_id', $request->vaccination_site_id)
                ->count() + 1);
            }

            $get_siteSettings = SiteSettings::find(1);

            $default_holidays = explode(',', $get_siteSettings->default_holiday_dates);
            $custom_holidays = explode(',', $get_siteSettings->custom_holiday_dates);

            $combined_holidays = array_merge($default_holidays, $custom_holidays);

            //Days Calculation (Skip and Wednesdays, Saturdays and Sundays due to Government Office Hours)
            $base_date = $request->d0_date;

            $set_d3_date = Carbon::parse($request->d0_date)->addDays(3);

            //Adjust D3 Date if Holidays
            while(in_array($set_d3_date->format('m-d'), $combined_holidays)) {
                $set_d3_date = Carbon::parse($set_d3_date)->addDays(1);
            }

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

            //Adjust D7 Date if Holidays
            while(in_array($set_d7_date->format('m-d'), $combined_holidays)) {
                $set_d7_date = Carbon::parse($set_d7_date)->addDays(1);
            }

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

            //Adjust D14 Date if Holidays
            while(in_array($set_d14_date->format('m-d'), $combined_holidays)) {
                $set_d14_date = Carbon::parse($set_d14_date)->addDays(1);
            }

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

            //Adjust D3 Date if Holidays
            while(in_array($set_d28_date->format('m-d'), $combined_holidays)) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
            }

            if($set_d28_date->dayOfWeek == Carbon::WEDNESDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
            }
            else if($set_d28_date->dayOfWeek == Carbon::SATURDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(2);
            }
            else if($set_d28_date->dayOfWeek == Carbon::SUNDAY) {
                $set_d28_date = Carbon::parse($set_d28_date)->addDays(1);
            }
            
            if($request->is_preexp == 'Y') {
                $is_preexp = 1;
                $bite_date = NULL;
                $case_location = NULL;
                $if_animal_vaccinated = 0;
                $animal_type = NULL;
                $bite_type = NULL;
                $category_level = 1;
            }
            else {
                $is_preexp = 0;
                $bite_date = $request->bite_date;
                $case_location = ($request->filled('case_location')) ? mb_strtoupper($request->case_location) : NULL;
                $if_animal_vaccinated = ($request->if_animal_vaccinated == 'Y') ? 1 : 0;
                $animal_type = $request->animal_type;
                $bite_type = $request->bite_type;
                $category_level = (!is_null($request->rig_date_given)) ? 3 : $request->category_level;
            }

            $f = $request->user()->abtcbakunarecord()->create([
                'patient_id' => $id,
                'vaccination_site_id' => $request->vaccination_site_id,
                'case_id' => $case_id,
                'is_booster' => $is_booster,
                'is_preexp' => $is_preexp,
                'case_date' => $request->case_date,
                'case_location' => $case_location,
                'animal_type' => $animal_type,
                'animal_type_others' => ($request->animal_type == 'O') ? mb_strtoupper($request->animal_type_others) : NULL,
                'if_animal_vaccinated' => $if_animal_vaccinated,
                'bite_date' => $bite_date,
                'bite_type' => $bite_type,
                'body_site' => ((strlen(implode(",", $request->body_site)) != 0)) ? implode(",", $request->body_site) : NULL,
                'category_level' => $category_level,
                'washing_of_bite' => ($request->washing_of_bite == 'Y') ? 1 : 0,
                'rig_date_given' => $request->rig_date_given,
                'height' => $request->height,
                'weight' => $request->weight,

                'pep_route' => $request->pep_route,
                'brand_name' => $request->brand_name,
                'd0_date' => $request->d0_date,
                'd0_done' => 1,
                'd0_vaccinated_inbranch' => ($request->d0_vaccinated_inbranch == 'Y') ? 1 : 0,
                'd0_brand' => $request->brand_name,
                'd0_done_by' => auth()->user()->id,
                'd0_done_date' => date('Y-m-d H:i:s'),
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

                'date_died' => ($request->outcome == 'D') ? $request->date_died : NULL,
                'animal_died_date' => ($request->biting_animal_status == 'DEAD') ? $request->animal_died_date : NULL,
                'remarks' => $request->remarks,
            ]);

            if($request->d0_vaccinated_inbranch == 'Y') {
                $get_vbrand = AbtcVaccineBrand::where('brand_name', $request->brand_name)->first();

                //Init Vaccine Stocks
                $vstock = AbtcVaccineStocks::where('vaccine_id', $get_vbrand->id)
                ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                ->first();

                if($request->d0_date <= $vstock->initial_date) {
                    $vstock->patient_dosecount_init++;
                
                    if($vstock->patient_dosecount_init == $get_vbrand->est_maxdose_perbottle) {
                        $vstock->current_stock--;
                        $vstock->patient_dosecount_init = 0;
                    }

                    if($vstock->isDirty()) {
                        $vstock->save();
                    }
                }
            }

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
            'animal_type' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'animal_type_others' => ($request->animal_type == 'O') ? 'required' : 'nullable',
            'bite_date' => ($request->is_preexp == 'N') ? 'required|date|after_or_equal:2000-01-01|before_or_equal:today' : 'nullable',
            'bite_type' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'body_site' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'category_level' => ($request->is_preexp == 'N') ? 'required' : 'nullable',
            'washing_of_bite' => 'required',
            'rig_date_given' => 'nullable|date',
            'pep_route' => 'required',
            'brand_name' => 'required',
            'outcome' => 'required',
            'biting_animal_status' => 'required',
            'remarks' => 'nullable',
        ]);

        $b = AbtcBakunaRecords::findOrFail($bakuna_id);

        if($request->is_preexp == 'Y') {
            $is_preexp = 1;
            $bite_date = NULL;
            $case_location = NULL;
            $if_animal_vaccinated = 0;
            $animal_type = NULL;
            $bite_type = NULL;
            $category_level = 1;
        }
        else {
            $is_preexp = 0;
            $bite_date = $request->bite_date;
            $case_location = ($request->filled('case_location')) ? mb_strtoupper($request->case_location) : NULL;
            $if_animal_vaccinated = ($request->if_animal_vaccinated == 'Y') ? 1 : 0;
            $animal_type = $request->animal_type;
            $bite_type = $request->bite_type;
            $category_level = (!is_null($request->rig_date_given)) ? 3 : $request->category_level;
        }

        $b->is_booster = ($request->is_booster == 'Y') ? 1 : 0;
        $b->is_preexp = $is_preexp;
        $b->vaccination_site_id = $request->vaccination_site_id;
        $b->case_date = $request->case_date;
        $b->case_location = $case_location;
        $b->animal_type = $animal_type;
        $b->animal_type_others = ($request->animal_type == 'O') ? mb_strtoupper($request->animal_type_others) : NULL;
        $b->if_animal_vaccinated = $if_animal_vaccinated;
        $b->bite_date = $bite_date;
        $b->bite_type = $bite_type;
        $b->body_site = ((strlen(implode(",", $request->body_site)) != 0)) ? implode(",", $request->body_site) : NULL;
        $b->category_level = $category_level;
        $b->washing_of_bite = ($request->washing_of_bite == 'Y') ? 1 : 0;
        $b->rig_date_given = $request->rig_date_given;

        $b->height = $request->height;
        $b->weight = $request->weight;

        $b->pep_route = $request->pep_route;
        $b->brand_name = $request->brand_name;

        $b->outcome = $request->outcome;
        $b->biting_animal_status = $request->biting_animal_status;

        $b->date_died = ($request->outcome == 'D') ? $request->date_died : NULL;
        $b->animal_died_date = ($request->biting_animal_status == 'DEAD') ? $request->animal_died_date : NULL;
        $b->remarks = $request->remarks;

        //Checking of Outcome on Category 3 with Erig
        if($b->category_level == 3 && $b->d28_done == 1 && !is_null($b->rig_date_given)) {
            $b->outcome = 'C';
        }

        if($b->outcome == 'INC' && $b->is_booster == 1 && $b->d0_done == 1 && $b->d3_done == 1) {
            $b->outcome = 'C';
        }

        if($b->outcome == 'INC' && $b->is_booster == 0 && $b->d0_done == 1 && $b->d3_done == 1 && $b->d7_done == 1) {
            $b->outcome = 'C';
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
            if(date('Y-m-d', strtotime($b->d0_date.' + 90 Days')) < date('Y-m-d')) {
                return view('abtc.encode_new', [
                    'd' => $b->patient,
                    'vblist' => $vblist,
                    'vslist' => $vslist,
                ]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'Unable to process. Patient was still not past the 90 Days (3 Months) period from his/her last anti-rabies vaccine. Booster is not yet required at this point.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            $btwo = AbtcBakunaRecords::where('patient_id', $patient_id)
            ->where('outcome', 'INC')
            ->orderBy('created_at', 'DESC')
            ->first();

            if($btwo) {
                $vblist = AbtcVaccineBrand::where('enabled', 1)->orderBy('brand_name', 'ASC')->get();
                $vslist = AbtcVaccinationSite::where('enabled', 1)->orderBy('id', 'ASC')->get();

                if($btwo->rebakunaIncompleteCheck() == true) {
                    return view('abtc.encode_new', [
                        'd' => $btwo->patient,
                        'vblist' => $vblist,
                        'vslist' => $vslist,
                    ]);
                }
                else {
                    return redirect()->back()
                    ->with('msg', 'Unable to process. 1 week has not yet passed since the last incomplete Vaccination //DURATION TEST ONLY, CONTACT CHRISTIAN JAMES HISTORILLO TO UPDATE CODE HERE')
                    ->with('msgtype', 'warning');
                }
            }
            else {
                return abort(401);
            }            
        }
    }

    public function encode_process($br_id, $dose) {
        $get_br = AbtcBakunaRecords::findOrFail($br_id);

        if(is_null($get_br->patient->bdate)) {
            return redirect()->route('abtc_patient_edit', $get_br->patient->id)
            ->with('msg', 'Error: Paki-tanong muna sa pasyente kung ano ang Birthdate niya bago bakunahan.')
            ->with('msgtype', 'warning');
        }

        if(!is_null($get_br->brand_name)) {
            if($dose == 1) {
                if($get_br->ifAbleToProcessD0() == 'Y') {
                    $get_br->d0_done = 1;
                    $get_br->d0_brand = $get_br->brand_name;
                    $get_br->d0_vaccinated_inbranch = 1;
                    //$get_br->d0_done_by = auth()->user()->id;
                    //$get_br->d0_done_date = date('Y-m-d H:i:s');
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
                    $get_br->d3_vaccinated_inbranch = 1;
                    $get_br->d3_done_by = auth()->user()->id;
                    $get_br->d3_done_date = date('Y-m-d H:i:s');
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
                if($get_br->d7_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 0 && $get_br->is_booster == 0) {
                    $get_br->d7_done = 1;
                    $get_br->d7_brand = $get_br->brand_name;
                    $get_br->d7_vaccinated_inbranch = 1;
                    $get_br->d7_done_by = auth()->user()->id;
                    $get_br->d7_done_date = date('Y-m-d H:i:s');
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
                if($get_br->d14_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d14_done == 0 && $get_br->is_booster == 0) {
                    $get_br->d14_done = 1;
                    $get_br->d14_brand = $get_br->brand_name;
                    $get_br->d14_vaccinated_inbranch = 1;
                    $get_br->d14_done_by = auth()->user()->id;
                    $get_br->d14_done_date = date('Y-m-d H:i:s');
                }
                else {
                    return abort(401);
                }

                $get_br->outcome = 'C';
                
                $msg = 'You have finished your 4th Dose of your Anti-Rabies Vaccine.';
            }
            else if($dose == 5) { //Day 28
                if($get_br->pep_route == 'IM') {
                    if($get_br->d28_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d14_done == 1 && $get_br->d28_done == 0 && $get_br->is_booster == 0) {
                        $get_br->d28_done = 1;
                        $get_br->d28_brand = $get_br->brand_name;
                        $get_br->d28_vaccinated_inbranch = 1;
                        $get_br->d28_done_by = auth()->user()->id;
                        $get_br->d28_done_date = date('Y-m-d H:i:s');
                    }
                    else {
                        return abort(401);
                    }
                }
                else if($get_br->pep_route == 'ID') { //Skip 14 Day
                    if($get_br->d28_date == date('Y-m-d') && $get_br->d0_done == 1 && $get_br->d3_done == 1 && $get_br->d7_done == 1 && $get_br->d28_done == 0 && $get_br->is_booster == 0) {
                        $get_br->d28_done = 1;
                        $get_br->d28_brand = $get_br->brand_name;
                        $get_br->d28_vaccinated_inbranch = 1;
                        $get_br->d28_done_by = auth()->user()->id;
                        $get_br->d28_done_date = date('Y-m-d H:i:s');
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

            $get_vbrand = AbtcVaccineBrand::where('brand_name', $get_br->brand_name)->first();

            //Init Vaccine Stocks
            $vstock = AbtcVaccineStocks::where('vaccine_id', $get_vbrand->id)
            ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
            ->first();

            $vstock->patient_dosecount_init++;
            
            if($vstock->patient_dosecount_init == $get_vbrand->est_maxdose_perbottle) {
                $vstock->current_stock--;
                $vstock->patient_dosecount_init = 0;
            }

            if($vstock->isDirty()) {
                $vstock->save();
            }
            
            $get_br->updated_by = auth()->user()->id;
            $get_br->save();

            if(!(request()->input('fsc'))) {
                return view('abtc.encode_finished', [
                    'f' => $get_br,
                ])
                ->with('msg', $msg)
                ->with('dose' , $dose);
            }
            else {
                return redirect()->route('abtc_schedule_index')
                ->with('msg', 'Patient (#'.$get_br->case_id.') '.$get_br->patient->getName().' latest dose was marked as completed successfully.')
                ->with('msgtype', 'success');
            }
        }
        else {
            return redirect()->back()
            ->with('msg', 'Unable to proceed. Please put Vaccine Brand first, click [Save] then try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function encode_processLate($br_id, $dose) {
        $get_br = AbtcBakunaRecords::findOrFail($br_id);
        $now = Carbon::now();

        if(!is_null($get_br->brand_name)) {
            if($dose == 1) {
                $date_check = Carbon::parse($get_br->d0_date);

                if($date_check->diffInDays($now) < 3) {
                    $get_br->d0_date = date('Y-m-d');
                    $get_br->d0_done = 1;
                    $get_br->d0_brand = $get_br->brand_name;
                    $get_br->d0_vaccinated_inbranch = 1;
                    //$get_br->d0_done_by = auth()->user()->id;
                    //$get_br->d0_done_date = date('Y-m-d H:i:s');
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
    
                $msg = 'D0 Late Vaccination was processed successfully.';
            }
            else if($dose == 2) {
                $date_check = Carbon::parse($get_br->d3_date);

                if($date_check->diffInDays($now) < 3) {
                    $get_br->d3_date = date('Y-m-d');
                    $get_br->d3_done = 1;
                    $get_br->d3_brand = $get_br->brand_name;
                    $get_br->d3_vaccinated_inbranch = 1;
                    $get_br->d3_done_by = auth()->user()->id;
                    $get_br->d3_done_date = date('Y-m-d H:i:s');
                }
                else {
                    return abort(401);
                }

                if($get_br->is_booster == 1) {
                    $get_br->outcome = 'C';
                    $msg = 'D3 (Booster) Late Vaccination was processed successfully.';
                }
                else {
                    $msg = 'D3 Late Vaccination was processed successfully.';
    
                    //Check if delay ang d3 bakuna then move next schedules
                    /*
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
                    */
                }
            }
            else if($dose == 3) {
                $date_check = Carbon::parse($get_br->d7_date);

                if($date_check->diffInDays($now) < 3) {
                    $get_br->d7_date = date('Y-m-d');
                    $get_br->d7_done = 1;
                    $get_br->d7_brand = $get_br->brand_name;
                    $get_br->d7_vaccinated_inbranch = 1;
                    $get_br->d7_done_by = auth()->user()->id;
                    $get_br->d7_done_date = date('Y-m-d H:i:s');
                }
                else {
                    return abort(401);
                }

                $get_br->outcome = 'C';

                $msg = 'D7 Late Vaccination was processed successfully.';
            }
            else if($dose == 4 && $get_br->pep_route == 'IM') {
                $date_check = Carbon::parse($get_br->d14_date);

                if($date_check->diffInDays($now) < 3) {
                    $get_br->d14_date = date('Y-m-d');
                    $get_br->d14_done = 1;
                    $get_br->d14_brand = $get_br->brand_name;
                    $get_br->d14_vaccinated_inbranch = 1;
                    $get_br->d14_done_by = auth()->user()->id;
                    $get_br->d14_done_date = date('Y-m-d H:i:s');
                }
                else {
                    return abort(401);
                }

                $get_br->outcome = 'C';
                
                $msg = 'D7 Late Vaccination was processed successfully.';
            }
            else if($dose == 5) {
                $date_check = Carbon::parse($get_br->d28_date);

                if($date_check->diffInDays($now) < 3) {
                    $get_br->d28_date = date('Y-m-d');
                    $get_br->d28_done = 1;
                    $get_br->d28_brand = $get_br->brand_name;
                    $get_br->d28_vaccinated_inbranch = 1;
                    $get_br->d28_done_by = auth()->user()->id;
                    $get_br->d28_done_date = date('Y-m-d H:i:s');
                }
                else {
                    return abort(401);
                }
                
                $get_br->outcome = 'C';
    
                $msg = 'D28 Late Vaccination was processed successfully.';
            }

            $get_vbrand = AbtcVaccineBrand::where('brand_name', $get_br->brand_name)->first();

            //Init Vaccine Stocks
            $vstock = AbtcVaccineStocks::where('vaccine_id', $get_vbrand->id)
            ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
            ->first();

            $vstock->patient_dosecount_init++;
            
            if($vstock->patient_dosecount_init == $get_vbrand->est_maxdose_perbottle) {
                $vstock->current_stock--;
                $vstock->patient_dosecount_init = 0;
            }

            if($vstock->isDirty()) {
                $vstock->save();
            }
            
            $get_br->updated_by = auth()->user()->id;
            $get_br->save();

            if(!(request()->input('fsc'))) {
                return view('abtc.encode_finished', [
                    'f' => $get_br,
                ])
                ->with('msg', $msg)
                ->with('dose' , $dose);
            }
            else {
                return redirect()->route('abtc_schedule_index')
                ->with('msg', 'Patient (#'.$get_br->case_id.') '.$get_br->patient->getName().' latest dose was marked as completed successfully.')
                ->with('msgtype', 'success');
            }
        }
        else {
            return redirect()->back()
            ->with('msg', 'Unable to proceed. Please put Vaccine Brand first, click [Save] then try again.')
            ->with('msgtype', 'warning');
        }
    }

    public function qr_quicksearch(Request $request) {
        $sqr = $request->qr;

        if(str_starts_with($sqr, 'http')) {
            $sqr = basename($sqr);
        }

        $search = AbtcPatient::where('qr', $sqr)
        ->first();

        if($search) {
            //load latest bakuna record

            $b = AbtcBakunaRecords::where('patient_id', $search->id)->orderBy('created_at', 'DESC')->first();
            if($b) {
                return redirect()->route('abtc_encode_edit', $b->id)
                ->with('msg', 'Result found with same Registration Number.')
                ->with('msgtype', 'success');

                //return redirect()->route('abtc_encode_existing', ['id' => $search->id]);
            }
            else {
                return redirect()->back()
                ->with('msg', 'No Anti-Rabies Vaccination Record found for '.$search->getName())
                ->with('msgtype', 'warning');
            }
        }
        else {
            if(strlen($sqr) < 6) {
                $sqr = '2023-'.$sqr;
            }
            
            $asearch = AbtcBakunaRecords::where('case_id', $sqr)
            ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
            ->first();
            
            if($asearch) {
                return redirect()->route('abtc_encode_edit', $asearch->id)
                ->with('msg', 'Result found with same Registration Number.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()->back()
                ->with('msg', 'QR/Registration Number is Invalid.')
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
        if($request->p_submit == 'oride') {
            $request->validate([
                
            ]);

            $d->d0_date = $request->d0_date;
            $d->d0_brand = $request->d0_brand;

            if($d->d0_done == 0) {
                if($request->d0_ostatus == 'C') {
                    $d->d0_done = 1;

                    if(is_null($d->d0_done_by)) {
                        $d->d0_done_by = auth()->user()->id;
                        $d->d0_done_date = date('Y-m-d H:i:s');
                    }

                    if($request->d0_vaccinated_inbranch == 'Y') {
                        $bsearch = AbtcVaccineBrand::where('brand_name', $request->d0_brand)->first();
                        $stock_search = AbtcVaccineStocks::where('vaccine_id', $bsearch->id)
                        ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                        ->first();

                        if(Carbon::parse($request->d0_date)->lt(Carbon::parse($stock_search->initial_date))) {
                            $d->d0_vaccinated_inbranch = 0;
                        }
                        else if(Carbon::parse($stock_search->initial_date)->gt(Carbon::parse(date('Y-m-d')))) {
                            $d->d0_vaccinated_inbranch = 0;
                        }
                        else {
                            $d->d0_vaccinated_inbranch = 1;
    
                            $stock_search->patient_dosecount_init++;
                
                            if($stock_search->patient_dosecount_init == $bsearch->est_maxdose_perbottle) {
                                $stock_search->current_stock--;
                                $stock_search->patient_dosecount_init = 0;
                            }
    
                            if($stock_search->isDirty()) {
                                $stock_search->save();
                            }
                        }
                    }
                }
            }

            $d->d3_date = $request->d3_date;
            $d->d3_brand = $request->d3_brand;

            if($d->d3_done == 0) {
                if($request->d3_ostatus == 'C') {
                    $d->d0_done = 1;
                    $d->d3_done = 1;

                    if(is_null($d->d3_done_by)) {
                        $d->d3_done_by = auth()->user()->id;
                        $d->d3_done_date = date('Y-m-d H:i:s');
                    }

                    if($d->outcome == 'INC' && $d->is_booster == 1) {
                        $d->outcome = 'C';
                    }

                    if($request->d3_vaccinated_inbranch == 'Y') {
                        $bsearch = AbtcVaccineBrand::where('brand_name', $request->d3_brand)->first();
                        $stock_search = AbtcVaccineStocks::where('vaccine_id', $bsearch->id)
                        ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                        ->first();
                        
                        if(Carbon::parse($request->d3_date)->lt(Carbon::parse($stock_search->initial_date))) {
                            $d->d3_vaccinated_inbranch = 0;
                        }
                        else if(Carbon::parse($stock_search->initial_date)->gt(Carbon::parse(date('Y-m-d')))) {
                            $d->d3_vaccinated_inbranch = 0;
                        }
                        else {
                            $d->d3_vaccinated_inbranch = 1;
    
                            $stock_search->patient_dosecount_init++;
                
                            if($stock_search->patient_dosecount_init == $bsearch->est_maxdose_perbottle) {
                                $stock_search->current_stock--;
                                $stock_search->patient_dosecount_init = 0;
                            }
    
                            if($stock_search->isDirty()) {
                                $stock_search->save();
                            }
                        }
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
                        
                        if(is_null($d->d7_done_by)) {
                            $d->d7_done_by = auth()->user()->id;
                            $d->d7_done_date = date('Y-m-d H:i:s');
                        }

                        if($d->outcome == 'INC' && $d->is_booster == 0) {
                            $d->outcome = 'C';
                        }

                        if($request->d7_vaccinated_inbranch == 'Y') {
                            $bsearch = AbtcVaccineBrand::where('brand_name', $request->d7_brand)->first();
                            $stock_search = AbtcVaccineStocks::where('vaccine_id', $bsearch->id)
                            ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                            ->first();
                            
                            if(Carbon::parse($request->d7_date)->lt(Carbon::parse($stock_search->initial_date))) {
                                $d->d7_vaccinated_inbranch = 0;
                            }
                            else if(Carbon::parse($stock_search->initial_date)->gt(Carbon::parse(date('Y-m-d')))) {
                                $d->d7_vaccinated_inbranch = 0;
                            }
                            else {
                                $d->d7_vaccinated_inbranch = 1;
        
                                $stock_search->patient_dosecount_init++;
                    
                                if($stock_search->patient_dosecount_init == $bsearch->est_maxdose_perbottle) {
                                    $stock_search->current_stock--;
                                    $stock_search->patient_dosecount_init = 0;
                                }
        
                                if($stock_search->isDirty()) {
                                    $stock_search->save();
                                }
                            }
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

                            if(is_null($d->d14_done_by)) {
                                $d->d14_done_by = auth()->user()->id;
                                $d->d14_done_date = date('Y-m-d H:i:s');
                            }

                            if($d->outcome == 'INC' && $d->is_booster == 0) {
                                $d->outcome = 'C';
                            }

                            if($request->d14_vaccinated_inbranch == 'Y') {
                                $bsearch = AbtcVaccineBrand::where('brand_name', $request->d14_brand)->first();
                                $stock_search = AbtcVaccineStocks::where('vaccine_id', $bsearch->id)
                                ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                                ->first();
                                
                                if(Carbon::parse($request->d14_date)->lt(Carbon::parse($stock_search->initial_date))) {
                                    $d->d14_vaccinated_inbranch = 0;
                                }
                                else if(Carbon::parse($stock_search->initial_date)->gt(Carbon::parse(date('Y-m-d')))) {
                                    $d->d14_vaccinated_inbranch = 0;
                                }
                                else {
                                    $d->d14_vaccinated_inbranch = 1;
            
                                    $stock_search->patient_dosecount_init++;
                        
                                    if($stock_search->patient_dosecount_init == $bsearch->est_maxdose_perbottle) {
                                        $stock_search->current_stock--;
                                        $stock_search->patient_dosecount_init = 0;
                                    }
            
                                    if($stock_search->isDirty()) {
                                        $stock_search->save();
                                    }
                                }
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

                        if(is_null($d->d28_done_by)) {
                            $d->d28_done_by = auth()->user()->id;
                            $d->d28_done_date = date('Y-m-d H:i:s');
                        }

                        if($d->outcome == 'INC' && $d->is_booster == 0) {
                            $d->outcome = 'C';
                        }

                        if($request->d28_vaccinated_inbranch == 'Y') {
                            $bsearch = AbtcVaccineBrand::where('brand_name', $request->d28_brand)->first();
                            $stock_search = AbtcVaccineStocks::where('vaccine_id', $bsearch->id)
                            ->where('branch_id', auth()->user()->abtc_default_vaccinationsite_id)
                            ->first();
                            
                            if(Carbon::parse($request->d28_date)->lt(Carbon::parse($stock_search->initial_date))) {
                                $d->d28_vaccinated_inbranch = 0;
                            }
                            else if(Carbon::parse($stock_search->initial_date)->gt(Carbon::parse(date('Y-m-d')))) {
                                $d->d28_vaccinated_inbranch = 0;
                            }
                            else {
                                $d->d28_vaccinated_inbranch = 1;
        
                                $stock_search->patient_dosecount_init++;
                    
                                if($stock_search->patient_dosecount_init == $bsearch->est_maxdose_perbottle) {
                                    $stock_search->current_stock--;
                                    $stock_search->patient_dosecount_init = 0;
                                }
        
                                if($stock_search->isDirty()) {
                                    $stock_search->save();
                                }
                            }
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
        else if($request->p_submit == 'reset') {
            if(auth()->user()->isAdmin == 1) {
                $d->outcome = 'INC';
                $d->d0_done = 0;
                $d->d3_done = 0;
                if($d->is_booster == 0) {
                    $d->d7_done = 0;
                    $d->d14_done = 0;
                    $d->d28_done = 0;
                }

                if($d->isDirty()) {
                    $d->save();
                }

                return redirect()
                ->back()
                ->with('msg', 'Schedule reset was successful.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()
                ->back()
                ->with('msg', 'You are not allowed to do that.')
                ->with('msgtype', 'warning');
            }
        }
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
                $r->whereDate('d3_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d7_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 0)
                ->where('is_booster', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d14_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 1)
                ->where('d14_done', 0)
                ->where('pep_route', 'IM')
                ->where('is_booster', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d28_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 1)
                ->where('d28_done', 0)
                ->where('is_booster', 0);
            });
        })
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id);

        $ff_row = $ff->where('outcome', 'INC')
        ->orderBy('created_at', 'ASC')
        ->get();

        $ff_total = AbtcBakunaRecords::where(function ($q) use ($sdate) {
            $q->where(function ($r) use ($sdate) {
                $r->whereDate('d3_date', $sdate)
                ->where('d0_done', 1);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d7_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('is_booster', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d14_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 1)
                ->where('pep_route', 'IM')
                ->where('is_booster', 0);
            });
        })
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id);

        /*
        ->where('outcome', 'INC')
        ->orderBy('created_at', 'ASC')
        ->get();
        */

        $possible_d28_count = AbtcBakunaRecords::where('outcome', 'C')
        ->whereDate('d28_date', $sdate)
        ->where('d28_done', 0)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();

        $completed_count = AbtcBakunaRecords::where(function ($r) use ($sdate) {
            $r->where(function ($q) use ($sdate) {
                $q->whereDate('d3_date', $sdate)
                ->where('d3_done', 1);
            })->orWhere(function ($q) use ($sdate) {
                $q->whereDate('d7_date', $sdate)
                ->where('d7_done', 1);
            })->orWhere(function ($q) use ($sdate) {
                $q->whereDate('d14_date', $sdate)
                ->where('d14_done', 1)
                ->where('pep_route', 'IM');
            })->orWhere(function ($q) use ($sdate) {
                $q->whereDate('d28_date', $sdate)
                ->where('d28_done', 1);
            });
        })->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();
        
        $completed_d0 = AbtcBakunaRecords::where('d0_done', 1)
        ->whereDate('d0_date', $sdate)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();

        $completed_d0_total = AbtcBakunaRecords::where('d0_done', 1)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->where(function ($q) use ($sdate) {
            $q->where(function ($r) use ($sdate) {
                $r->whereDate('d0_date', $sdate);
            })
            ->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d0_date', '<', $sdate)
                ->whereDate('created_at', $sdate);
            });
        })->count();

        $completed_d0_otherarea = AbtcBakunaRecords::whereDate('created_at', $sdate)
        ->where('d0_done', 1)
        ->whereDate('d0_date', '<', $sdate)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();

        $completed_d3 = AbtcBakunaRecords::where('d3_done', 1)
        ->whereDate('d3_date', $sdate)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();

        $completed_d7 = AbtcBakunaRecords::where('d7_done', 1)
        ->whereDate('d7_date', $sdate)
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->count();

        return view('abtc.schedule_index', [
            'new' => $new,
            'ff' => $ff,
            'ff_row' => $ff_row,
            'ff_total' => $ff_total,
            'completed_count' => $completed_count,
            'sdate' => $sdate,
            'possible_d28_count' => $possible_d28_count,
            'completed_d0' => $completed_d0,
            'completed_d0_total' => $completed_d0_total,
            'completed_d0_otherarea' => $completed_d0_otherarea,
            'completed_d3' => $completed_d3,
            'completed_d7' => $completed_d7,
        ]);
    }

    public function print_view($bid) {
        $f = AbtcBakunaRecords::findOrFail($bid);

        return view('abtc.encode_finished', [
            'f' => $f,
        ]);
    }

    public function markdead($br_id) {
        $b = AbtcBakunaRecords::findOrFail($br_id);

        if($b->d0_done == 1 && $b->d3_done == 1 && $b->d7_done == 1 && $b->d28_done == 0 && $b->is_booster == 0) {
            if($b->d28_date >= date('Y-m-d')) {
                $b->biting_animal_status = 'DEAD';
                $b->d28_done = 1;

                if($b->isDirty()) {
                    $b->save();
                }
                
                return redirect()
                ->back()
                ->with('msg', 'Animal was marked as Dead. Day 28 Vaccination of Patient completed successfully.')
                ->with('msgtype', 'success');
            }
            else {
                return redirect()
                ->back()
                ->with('msg', 'Error: Hindi pa Day 28 ng Pasyente ngayon. Maaaring palitan ito gamit ng [Schedule Override] sa baba tsaka ito subukang ulit.')
                ->with('msgtype', 'warning');
            }
        }
        else {
            return redirect()
            ->back()
            ->with('msg', 'Error: Hindi pa tapos ang bakuna ng pasyente.')
            ->with('msgtype', 'warning');
        }
    }

    public function destroy($pid) {
        if(auth()->user()->isAdmin == 1) {
            $p = AbtcBakunaRecords::findOrFail($pid);
            $p->delete();

            return redirect()
            ->route('abtc_patient_index')
            ->with('msg', 'Patient Vaccination Record was deleted successfully.')
            ->with('msgtype', 'success');
        }
        else {
            return abort(401);
        }
    }

    public function ffsms() {
        $sdate = request()->input('d');

        $ff = AbtcBakunaRecords::where(function ($q) use ($sdate) {
            $q->where(function ($r) use ($sdate) {
                $r->whereDate('d3_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d7_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 0)
                ->where('is_booster', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d14_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 1)
                ->where('d14_done', 0)
                ->where('pep_route', 'IM')
                ->where('is_booster', 0);
            })->orWhere(function ($r) use ($sdate) {
                $r->whereDate('d28_date', $sdate)
                ->where('d0_done', 1)
                ->where('d3_done', 1)
                ->where('d7_done', 1)
                ->where('d28_done', 0)
                ->where('is_booster', 0);
            });
        })->where('outcome', 'INC')
        ->where('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id)
        ->get();

        echo 'Send SMS to the following Mobile Numbers using Google Messages for Web App:<br><br>';

        foreach($ff as $ind => $f) {
            if(!is_null($f->patient->contact_number) || $f->patient->contact_number != '09999999999')
            if($ind === $ff->count() - 1) {
                echo $f->patient->contact_number;
            }
            else {
                echo $f->patient->contact_number.', ';
            }
        }

        echo '<br><br>Message:<br><br>';

        echo 'Magandang araw! Ito ang Animal Bite Treatment Center ng CHO General Trias na nagpapaalalang may follow-up na schedule ka ng bakuna ngayong araw ('.date('F d, Y', strtotime($sdate)).'). Pumunta ng 1PM upang mabakunahan. Maraming salamat.';
    }

    public function medcert($br_id) {
        $b = AbtcBakunaRecords::findOrFail($br_id);

        return view('abtc.medcert', [
            'b' => $b,
        ]);
    }

    public function referralslip($br_id) {
        $b = AbtcBakunaRecords::findOrFail($br_id);

        if(request()->input('reas') == "1") {
            $reason = 'No Available Anti-rabies Vaccine in City Health Office ABTC';
            $rec = 'Please give Anti-rabies Vaccine to Animal Bite Clinic of choice.';
        }
        else {
            $reason = 'For ERIG, No Available ERIG in City Health Office ABTC';
            $rec = 'Please give ERIG to Animal Bite Clinic of choice.';
        }
        
        return view('abtc.referralslip', [
            'b' => $b,
            'reason' => $reason,
            'rec' => $rec,
        ]);
    }

    public function itr($br_id) {
        $b = AbtcBakunaRecords::findOrFail($br_id);

        return view('abtc.itr', [
            'b' => $b,
        ]);
    }

    public function newprint($br_id) {
        $b = AbtcBakunaRecords::findOrFail($br_id);

        header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=CARDABTC.docx");

        $templateProcessor  = new TemplateProcessor(storage_path('CARDABTC.docx'));

        $templateProcessor->setValue('rid', $b->case_id);
        $templateProcessor->setValue('rdate', date('m/d/Y', strtotime($b->case_date)));
        $templateProcessor->setValue('fullname', $b->patient->getName());
        $templateProcessor->setValue('age', $b->patient->getAge());
        $templateProcessor->setValue('gend', $b->patient->sg());
        $templateProcessor->setValue('brgy', $b->patient->address_brgy_text);
        $templateProcessor->setValue('muncity', $b->patient->address_muncity_text.', '.$b->patient->address_province_text);
        $templateProcessor->setValue('dexp', ($b->is_preexp == 0) ? date('m/d/Y', strtotime($b->bite_date)) : 'N/A');
        $templateProcessor->setValue('dplace', $b->case_location);
        $templateProcessor->setValue('dtype', $b->getBiteType());
        $templateProcessor->setValue('dsource', $b->getSource());
        
        if($b->category_level == 3) {
            $templateProcessor->setValue('dcat', '3');
        }
        else {
            if($b->d3_done == 0) {
                if($b->is_preexp == 0) {
                    $templateProcessor->setValue('dcat', '2');
                }
                else {
                    $templateProcessor->setValue('dcat', '1 - PRE-EXPOSURE');
                }
            }
            else {
                $templateProcessor->setValue('dcat', $b->category_level);
            }
        }

        //$templateProcessor->setValue('dcat', ($b->d3_done == 0) ? '' : $b->category_level);
        $templateProcessor->setValue('dwash', ($b->washing_of_bite == 1) ? 'Y' : 'N');
        $templateProcessor->setValue('drig', $b->showRigNew());
        $templateProcessor->setValue('dgen', $b->getGenericName());
        $templateProcessor->setValue('dbrand', $b->brand_name);
        $templateProcessor->setValue('eroute', $b->pep_route);
        $templateProcessor->setValue('qcode', route('abtc_qr_process', $b->patient->qr));
        $templateProcessor->setValue('day0', date('m/d/Y', strtotime($b->d0_date)));
        $templateProcessor->setValue('day3', date('m/d/Y', strtotime($b->d3_date)));
        if($b->is_booster != 1) {
            $templateProcessor->setValue('day7', date('m/d/Y', strtotime($b->d7_date)));
            $templateProcessor->setValue('day14', ($b->pep_route == 'IM') ? date('m/d/Y', strtotime($b->d14_date)) : 'N/A');
            $templateProcessor->setValue('day28', date('m/d/Y', strtotime($b->d28_date)));

            $templateProcessor->setValue('isbooster', '');
        }
        else {
            $templateProcessor->setValue('day7', 'N/A');
            $templateProcessor->setValue('day14', 'N/A');
            $templateProcessor->setValue('day28', 'N/A');

            $templateProcessor->setValue('isbooster', ' - BOOSTER');
        }

        $templateProcessor->setValue('outcome', 'ANG SUNOD NA BALIK (FOLLOW-UP) AY SA D3 AT D7, 1PM');

        /*
        if($b->outcome == 'INC') {
            $templateProcessor->setValue('outcome', 'PAUNAWA: HAPON PO ANG FOLLOW-UP, 1PM');
        }
        else {
            $templateProcessor->setValue('outcome', '');
        }
        */
        
        $templateProcessor->saveAs('php://output');
        //$templateProcessor->save('php://output');
    }

    public function remainingPt() {
        $date1 = Carbon::parse(request()->input('date1'));
        $date2 = Carbon::parse(request()->input('date2'));

        $searc_d3 = AbtcBakunaRecords::where('d0_done', 1)
        ->where('vaccination_site_id', 1)
        ->whereBetween('d0_date', [$date1->format('Y-m-d'), $date2->format('Y-m-d')])
        ->where('d3_done', 0)
        ->count();

        $searc_d7 = AbtcBakunaRecords::where('d0_done', 1)
        ->where('vaccination_site_id', 1)
        ->whereBetween('d0_date', [$date1->format('Y-m-d'), $date2->format('Y-m-d')])
        ->where('is_booster', 0)
        ->where('d7_done', 0)
        ->count();

        dd($searc_d3.' '.$searc_d7);
    }
}
