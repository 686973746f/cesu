<?php

namespace App\Http\Controllers;

use App\Models\EdcsBrgy;
use App\Models\SocialHygieneTcl;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SocialHygieneTclController extends Controller
{
    public function index() {
        $city_id = auth()->user()->opdfacility->brgy->city_id;

        $list = EdcsBrgy::where('city_id', $city_id)
        ->orderBy('name', 'asc')
        ->get();

        if(request()->input('month') && request()->input('year')) {
            $selectDate = Carbon::createFromDate(request()->input('year'), request()->input('month'), 1);

            // Check if month and year selected is not greater than current month and year
            // how about equal to current month and year? it should be alowed since encoding for current month and year is also allowed
            if($selectDate->greaterThan(Carbon::now()->startOfMonth())) {
                return redirect()
                ->back()
                ->with('msg', 'Selected month and year cannot be greater than current month and year.')
                ->with('msgtype', 'danger');
            }
        }
        else {
            $selectDate = Carbon::now();
        }
        
        return view('efhsis.etcl.shc.encode', compact('list', 'selectDate'));
    }

    public function store(Request $r) {
        $dateSelected = Carbon::createFromDate($r->year, $r->month, 1);

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

                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]
            );
        }

        return redirect()
        ->back()
        ->with('msg', 'Social Hygiene TCL Data for '.$dateSelected->format('F Y').' has been successfully saved.')
        ->with('msgtype', 'success');
    }
}
