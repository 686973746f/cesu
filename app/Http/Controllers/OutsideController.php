<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use Illuminate\Http\Request;

class OutsideController extends Controller
{
    public function qrcodeverify($qr) {
        $check = Forms::where('antigenqr', $qr)
        ->where(function ($q) {
            $q->where('testType1', 'ANTIGEN')
            ->orWhere('testType2', 'ANTIGEN');
        })
        ->first();
        
        return view('verify_qr', ['c' => $check]);
    }
}
