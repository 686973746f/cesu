<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbtcVaccineBrand;
use App\Imports\AnimalBiteImport;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;

class ABTCAdminController extends Controller
{
    public function vaccinationsite_index() {
        $list = AbtcVaccinationSite::orderBy('site_name', 'ASC')->paginate(10);

        return view('abtc.vaccinationsite_index', [
            'list' => $list,
        ]);
    }
    
    public function vaccinationsite_store(Request $request) {
        $request->validate([
            'site_name' => 'required',
        ]);

        $str = mb_strtoupper(Str::random(5));

        AbtcVaccinationSite::create([
            'site_name' => $request->site_name,
            'referral_code' => $str,
        ]);

        return redirect()->route('abtc_vaccinationsite_index')
        ->with('msg', 'Vaccination Site was added successfully.')
        ->with('msgtype', 'success');
    }

    public function vaccinationsite_edit($id) {
        $d = AbtcVaccinationSite::findOrFail($id);

        $doctor_list = Employee::where('employment_status', 'ACTIVE')
        ->where('emp_access_list', 'LIKE', '%ABTC_DOCTOR%')
        ->get();

        return view('abtc.vaccinationsite_edit', [
            'd' => $d,
            'doctor_list' => $doctor_list,
        ]);
    }

    public function vaccinationsite_update($id, Request $r) {
        $update = AbtcVaccinationSite::where('id', $id)
        ->update([
            'site_name' => mb_strtoupper($r->site_name),

            'ph_facility_name' => mb_strtoupper($r->ph_facility_name),
            'ph_facility_code' => mb_strtoupper($r->ph_facility_code),
            'ph_address_houseno' => mb_strtoupper($r->ph_address_houseno),
            'ph_doh_certificate' => mb_strtoupper($r->ph_doh_certificate),
            'ph_professional1_id' => $r->ph_professional1_id,
            'ph_professional2_id' => $r->ph_professional2_id,
            'ph_professional3_id' => $r->ph_professional3_id,
            'ph_head_id' => $r->ph_head_id,
            'ph_accountant_name_position' => ($r->ph_accountant_name_position) ? mb_strtoupper($r->ph_accountant_name_position) : NULL,
        ]);

        return redirect()->back()
        ->with('msg', 'ABTC Facility was updated successfully.')
        ->with('msgtype', 'success');
    }

    public function vaccinebrand_index() {
        $list = AbtcVaccineBrand::orderBy('brand_name', 'ASC')->paginate(10);

        return view('abtc.vaccinebrand_index', [
            'list' => $list,
        ]);
    }

    public function vaccinebrand_store(Request $request) {
        $request->validate([
            'brand_name' => 'required',
            'generic_name' => 'required',
        ]);

        AbtcVaccineBrand::create([
            'brand_name' => mb_strtoupper($request->brand_name),
            'generic_name' => mb_strtoupper($request->generic_name),
        ]);

        return redirect()->route('abtc_vaccinebrand_index')
        ->with('msg', 'Anti-Rabies Brand '.strtoupper($request->brand_name).' was successfully added.')
        ->with('msgtype', 'success');
    }

    public function vaccinebrand_edit($id) {

    }

    public function vaccinebrand_update($id, Request $request) {

    }

    public function gupdate() {
        /*
        $l = AbtcBakunaRecords::where('vaccination_site_id', 2)
        ->whereYear('case_date', '2023')
        ->orderBy('created_at', 'ASC')
        ->get();

        $c = 1;

        foreach($l as $a) {
            $u = AbtcBakunaRecords::findOrFail($a->id);

            $u->case_id = '2023-'.$c;

            $u->save();

            $c++;
        }

        //return 'done';

        $l = AbtcBakunaRecords::where('outcome', 'INC')
        ->where('is_booster', 0)
        ->where('d0_done', 1)
        ->where('d3_done', 1)
        ->where('d7_done', 1)
        ->update([
            'outcome' => 'C'
        ]);

        $j = AbtcBakunaRecords::where('outcome', 'INC')
        ->where('is_booster', 1)
        ->where('d0_done', 1)
        ->where('d3_done', 1)
        ->update([
            'outcome' => 'C'
        ]);

        return 'done';
        */

        
    }

    public function xlimport(Request $request) {
        Excel::import(new AnimalBiteImport('ABD'), $request->abtcfile);
    }
}
