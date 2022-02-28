@extends('layouts.app_pdf')
<style>
    @page { margin: 0; }
    body { margin: 0; }
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
<div class="container-fluid" style="font-family: Arial, Helvetica, sans-serif">
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td class="text-right">
                    <img src="{{asset('assets/images/gentriheader.png')}}" style="width: 8rem" alt="">
                </td>
                <td style="width: 400px;" class="text-center">
                    <h6>REPUBLIC OF THE PHILIPPINES</h6>
                    <h6>PROVINCE OF CAVITE</h6>
                    <h6>CITY OF GENERAL TRIAS</h6>
                    <h6>OFFICE OF THE CITY HEALTH OFFICER</h6>
                </td>
                <td class="text-left">
                    <img src="{{asset('assets/images/choheader.png')}}" style="width: 8rem" alt="">
                </td>
            </tr>
        </tbody>
    </table>
    <h6 class="text-center">LABORATORY RESULT FORM FOR COVID-19 ANTIGEN RAPID TEST</h6>
    
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

    <table class="table table-borderless">
        <tbody>
            <tr>
                <td>Date Performed: <u>{{$dateCollected}}</u></td>
                <td class="text-center">Date Released: <u>{{$dateReleased}}</u></td>
            </tr>
            <tr>
                <td>
                    <img src="{{asset('assets/images/mac.png')}}" style="width: 12rem" alt="">
                </td>
                <td class="text-center">
                    <img src="{{asset('assets/images/docathan.png')}}" style="width: 12rem" alt="">
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection