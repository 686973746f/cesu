<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PIDSRController extends Controller
{
    public function home() {
        return view('pidsr.home');
    }

    public function threshold_index() {
        if(!(request()->input('sd')) && !(request()->input('year'))) {

        }

        $s = request()->input('sd');
        $y = request()->input('year');

        if($s == 'DENGUE') {
            
        }
    }
}
