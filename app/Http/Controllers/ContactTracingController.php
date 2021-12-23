<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class ContactTracingController extends Controller
{
    public function dashboard_index() {
        $list_arr = [];
        $main_arr = [];

        $get_ct = Forms::whereNotNull('ccid_list')->pluck('ccid_list')->toArray();

        foreach($get_ct as $i) {
            $exploded = explode(",", $i);
        }

        foreach($get_ct as $p) {
            $get_primary = Forms::whereIn('id', [explode(",", $p->ccid_list)])->get();

            array_push($main_arr, $get_primary);
        }

        foreach($get_primary as $q) {
            $get_cc = Forms::whereNot('id', $q)
            ->where('ccid_list', 'LIKE', '%'.$q)
            ->get();
        }

        return view('ct_dashboard_index', [

        ]);
    }
}
