<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbtcVaccineBrand;
use App\Imports\AnimalBiteImport;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccinationSite;
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
