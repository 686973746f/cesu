<?php

namespace App\Http\Controllers;

use App\Models\AbtcVaccineBrand;
use Illuminate\Http\Request;
use App\Models\AbtcVaccinationSite;
use App\Models\AbtcBakunaRecords;

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
        $l = AbtcBakunaRecords::where('vaccination_site_id', 1)
        ->whereYear('case_date', '2022')
        ->orderBy('created_at', 'ASC')
        ->get();

        $c = 1;

        foreach($l as $a) {
            $u = AbtcBakunaRecords::findOrFail($a->id);

            $u->case_id = '2022-'.$c;

            $u->save();

            $c++;
        }

        return 'done';
    }
}
