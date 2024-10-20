@extends('layouts.app_pdf')
<style>
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 50%;
    }
</style>
@section('content')
@php
if($testType == 1) {
    $dateCollected = date('m/d/Y', strtotime($details->testDateCollected1));
    if(!is_null($details->testDateReleased1)) {
        $dateReleased = date('m/d/Y', strtotime($details->testDateReleased1));
    }
    else {
        $dateReleased = date('m/d/Y', strtotime($details->testDateCollected1));
    }

    $time = (!is_null($details->oniTimeCollected1)) ? date('h:i A', strtotime($details->oniTimeCollected1)) : '';
    $result = ($details->testResult1 != "PENDING") ? $details->testResult1 : '';

    $resultColor = ($details->testResult1 == "POSITIVE") ? 'text-danger font-weight-bold' : '';
}
else {
    $dateCollected = date('m/d/Y', strtotime($details->testDateCollected2));
    if(!is_null($details->testDateReleased2)) {
        $dateReleased = date('m/d/Y', strtotime($details->testDateReleased2));
    }
    else {
        $dateReleased = date('m/d/Y', strtotime($details->testDateCollected2));
    }

    $time = (!is_null($details->oniTimeCollected2)) ? date('h:i A', strtotime($details->oniTimeCollected2)) : '';
    $result = ($details->testResult2 != "PENDING") ? $details->testResult2 : '';

    $resultColor = ($details->testResult2 == "POSITIVE") ? 'text-danger font-weight-bold' : '';
}
@endphp
<div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif" id="divToPrint">
    <div class="text-center">
        <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
    </div>
    <p class="text-center mt-3"><b>LABORATORY RESULT FORM FOR COVID-19 ANTIGEN RAPID TEST</b></p>
    
    <table cellspacing="0" cellpadding="0" class="mt-3 mb-3">
        <tbody>
            <tr>
                <td>Name: <u>{{$details->records->lname.", ".$details->records->fname." ".$details->records->mname}}</u></td>
                <td>Date Requested: <u>{{date('m/d/Y', strtotime($details->interviewDate))}}</u></td>
            </tr>
            <tr style="vertical-align: top;">
                <td>Address: <u><small>{{$details->records->address_street.", BRGY.".$details->records->address_brgy.", ".$details->records->address_city.", ".$details->records->address_province}}</small></u></td>
                <td style="width: 200px;">Age & Gender: <u>{{$details->records->getAge()." / ".$details->records->gender}}</u></td>
            </tr>
        </tbody>
    </table>
    
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>KIT/REAGENT USED</th>
                <th>LOT #</th>
                <th style="width: 150px">TIME</th>
                <th style="width: 200px">RESULT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: middle;">{{$aname}}</td>
                <td style="vertical-align: middle;">{{$alot}}</td>
                <td style="vertical-align: middle;">{{$time}}</td>
                <td style="vertical-align: middle;" class="{{$resultColor}}">{{$result}}</td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6 text-center">
            
        </div>
        <div class="col-md-6 text-center">
            
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            
        </div>
        <div class="col-md-6 text-center">
            
        </div>
    </div>
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td class="text-center"><p>Date Performed: <u>{{$dateCollected}}</u></p></td>
                <td class="text-center"><p>Date Released: <u>{{$dateReleased}}</u></p></td>
            </tr>
            <tr>
                <td>
                    <div class="text-center">
                        <p>Performed By:</p>
                        <img src="{{asset('assets/images/PIRMA_MACALFRED.png')}}" style="width: 16rem; margin-bottom: -30px; margin-top: 60px;" alt="">
                        <p style="margin-bottom: 1px;"><b><u>MACK ALFRED L. CHICO, RMT</u></b></p>
                        <p style="margin-bottom: 1px;">Medical Technologist I</p>
                        <p>License No. 0082495</p>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <p>Verified By:</p>
                        <img src="{{asset('assets/images/signatureonly_docathan.png')}}" style="width: 9rem; margin-bottom: -30px;" alt="">
                        <p style="margin-bottom: 1px;"><b><u>JONATHAN P. LUSECO, M.D</u></b></p>
                        <p style="margin-bottom: 1px;">City Health Officer II</p>
                        <p>License No. 102377</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection