<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function home() {
        return view('pharmacy.home');
    }
}
