<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class OutsideController extends Controller
{
    public function qrcodeverify($qr) {
        $check = Forms::where('antigenqr', $qr)->first();
        
        return view('verify_qr', ['c' => $check]);
    }
}
