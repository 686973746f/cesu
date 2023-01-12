<?php

namespace App\Http\Controllers;

use App\Models\VaccineBrand;
use Illuminate\Http\Request;
use App\Models\VaccinationSite;

class AdminController extends Controller
{
    public function vaccinationsite_index() {
        $list = VaccinationSite::orderBy('site_name', 'ASC')->paginate(10);

        return view('vaccinationsite_index', [
            'list' => $list,
        ]);
    }
    
    public function vaccinationsite_store(Request $request) {
        $request->validate([
            'site_name' => 'required',
        ]);

        VaccinationSite::create([
            'site_name' => $request->site_name,
        ]);

        return redirect()->route('vaccinationsite_index')
        ->with('msg', 'Vaccination Site was added successfully.')
        ->with('msgtype', 'success');
    }

    public function vaccinebrand_index() {
        $list = VaccineBrand::orderBy('brand_name', 'ASC')->paginate(10);

        return view('vaccinebrand_index', [
            'list' => $list,
        ]);
    }

    public function vaccinebrand_store(Request $request) {
        $request->validate([
            'brand_name' => 'required',
            'generic_name' => 'required',
        ]);

        VaccineBrand::create([
            'brand_name' => mb_strtoupper($request->brand_name),
            'generic_name' => mb_strtoupper($request->generic_name),
        ]);

        return redirect()->route('vaccinebrand_index')
        ->with('msg', 'Anti-Rabies Brand '.strtoupper($request->brand_name).' was successfully added.')
        ->with('msgtype', 'success');
    }

    public function vaccinebrand_edit($id) {

    }

    public function vaccinebrand_update($id, Request $request) {

    }
}
