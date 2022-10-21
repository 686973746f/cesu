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

        $sType = 'ANTIGEN';

        if($check->testType2 == 'ANTIGEN') {
            $sDate = date('d-M-Y', strtotime($check->testDateCollected2));
            $sDateReleased = (!is_null($check->testDateReleased2)) ? date('d-M-Y', strtotime($check->testDateReleased2)) : 'N/A';
            $sResult = $check->testResult2;
        }
        else {
            $sDate = date('d-M-Y', strtotime($check->testDateCollected1));
            $sDateReleased = (!is_null($check->testDateReleased1)) ? date('d-M-Y', strtotime($check->testDateReleased1)) : 'N/A';
            $sResult = $check->testResult1;
        }

        if($sResult == 'POSITIVE') {
            $txtc = 'text-danger';
        }
        else if($sResult == 'NEGATIVE') {
            $txtc = 'text-success';
        }
        else if($sResult == 'PENDING') {
            $txtc = 'text-warning';
        }
        else {
            $txtc = '';
        }
        
        return view('verify_qr', [
            'c' => $check,
            'sType' => $sType,
            'sDate' => $sDate,
            'sDateReleased' => $sDateReleased,
            'sResult' => $sResult,
            'txtc' => $txtc,
        ]);
    }
}
