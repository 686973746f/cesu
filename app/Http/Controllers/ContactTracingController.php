<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class ContactTracingController extends Controller
{
    public function dashboard_index() {

        if(request()->input('pid')) {
            $pid = request()->input('pid');

            $check = Forms::where('id', $pid)
            ->whereNotNull('ccid_list')
            ->first();

            if($check) {
                $siv = true;
            }
            else {
                $siv = false;
            }
        }
        else {
            $check = NULL;
            $siv = false;
        }

        return view('ct_dashboard_index', [
            'search_is_valid' => $siv,
            'form' => $check,
        ]);
    }
}
