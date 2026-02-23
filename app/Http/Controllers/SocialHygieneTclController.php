<?php

namespace App\Http\Controllers;

use App\Models\EdcsBrgy;
use App\Models\SocialHygieneTcl;
use Illuminate\Http\Request;

class SocialHygieneTclController extends Controller
{
    public function index() {
        $city_id = auth()->user()->opdfacility->brgy->city_id;

        $list = EdcsBrgy::where('city_id', $city_id)
        ->get();
        
        return view('efhsis.etcl.shc.encode', compact('list'));
    }

    public function store(Request $r) {
        foreach($r->brgy_id as $index => $brgy_id) {
            $updateOrCreate = SocialHygieneTcl::updateOrCreate(
                [
                    'year' => $r->year,
                    'month' => $r->month,
                    'address_brgy_code' => $brgy_id,
                ],
                [
                    'r_preg_syphilis_a' => $r->r_preg_syphilis_a[$index] ?? 0,
                    'nr_preg_syphilis_a' => $r->nr_preg_syphilis_a[$index] ?? 0,
                    'treated_preg_syphilis_a' => $r->treated_preg_syphilis_a[$index] ?? 0,
                    'r_preg_hiv_a' => $r->r_preg_hiv_a[$index] ?? 0,
                    'nr_preg_hiv_a' => $r->nr_preg_hiv_a[$index] ?? 0,
                    'r_preg_hepab_a' => $r->r_preg_hepab_a[$index] ?? 0,
                    'nr_preg_hepab_a' => $r->nr_preg_hepab_a[$index] ?? 0,

                    'r_preg_syphilis_b' => $r->r_preg_syphilis_b[$index] ?? 0,
                    'nr_preg_syphilis_b' => $r->nr_preg_syphilis_b[$index] ?? 0,
                    'treated_preg_syphilis_b' => $r->treated_preg_syphilis_b[$index] ?? 0,
                    'r_preg_hiv_b' => $r->r_preg_hiv_b[$index] ?? 0,
                    'nr_preg_hiv_b' => $r->nr_preg_hiv_b[$index] ?? 0,
                    'r_preg_hepab_b' => $r->r_preg_hepab_b[$index] ?? 0,
                    'nr_preg_hepab_b' => $r->nr_preg_hepab_b[$index] ?? 0,

                    'r_preg_syphilis_c' => $r->r_preg_syphilis_c[$index] ?? 0,
                    'nr_preg_syphilis_c' => $r->nr_preg_syphilis_c[$index] ?? 0,
                    'treated_preg_syphilis_c' => $r->treated_preg_syphilis_c[$index] ?? 0,
                    'r_preg_hiv_c' => $r->r_preg_hiv_c[$index] ?? 0,
                    'nr_preg_hiv_c' => $r->nr_preg_hiv_c[$index] ?? 0,
                    'r_preg_hepab_c' => $r->r_preg_hepab_c[$index] ?? 0,
                    'nr_preg_hepab_c' => $r->nr_preg_hepab_c[$index] ?? 0,
                ]
            );
        }

        return redirect()
        ->back()
        ->with('success', 'Data saved successfully.');
    }

    public function show($id) {

    }

    public function update(Request $r, $id) {

    }
}
