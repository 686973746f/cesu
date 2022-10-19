@extends('layouts.app')
@section('content')
<style>
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 50%;
    }
    
    @media print {
        #PrintBtn, #titleBody {
            display: none;
        }

        @page {
            margin: 0;
        }

        body {
            background-color: white;
            margin-top: 0;
        }
        
        body * {
        visibility: hidden;
        }

        #divToPrint, #divToPrint * {
            visibility: visible;
        }

        #divToPrint {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
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
<div class="container" style="font-family: Arial, Helvetica, sans-serif">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" id="titleBody">
                    <div class="d-flex justify-content-between">
                        <div>Antigen Result Form</div>
                        <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
                    </div>
                </div>
                <div class="card-body" id="divToPrint">
                    <div class="text-center">
                        <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid">
                    </div>
                    <p class="text-center mt-3"><b>LABORATORY RESULT FORM FOR COVID-19 ANTIGEN RAPID TEST</b></p>
                    <div class="row my-3">
                        <div class="col-sm-8">
                            <p style="margin-bottom: 0px;">Name: <u>{{$details->records->lname.", ".$details->records->fname." ".$details->records->mname}}</u></p>
                            <p>Address: <u><small>{{$details->records->address_street.", BRGY.".$details->records->address_brgy.", ".$details->records->address_city.", ".$details->records->address_province}}</small></u></p>
                        </div>
                        <div class="col-sm-4">
                            <p style="margin-bottom: 0px;">Date Requested: <u>{{date('m/d/Y', strtotime($details->interviewDate))}}</u></p>
                            <p>Age / Gender: <u>{{$details->records->getAge()." / ".$details->records->gender}}</u></p>
                        </div>
                    </div>
                    
                    <table class="table table-bordered text-center" id="mainTbl">
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
                                <td style="vertical-align: middle;"><p class="my-3">{{$aname}}</p></td>
                                <td style="vertical-align: middle;">{{$alot}}</td>
                                <td style="vertical-align: middle;">{{$time}}</td>
                                <td style="vertical-align: middle;" class="{{$resultColor}}">{{$result}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row text-center my-5">
                        <div class="col-sm-6">
                            <p>Date Performed: <u>{{$dateCollected}}</u></p>
                        </div>
                        <div class="col-sm-6">
                            <p>Date Released: <u>{{$dateReleased}}</u></p>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-sm-4">
                            <div class="text-center">
                                <p>Performed By:</p>
                                <img src="{{asset('assets/images/PIRMA_MACALFRED.png')}}" style="width: 16rem; margin-bottom: -30px; margin-top: 60px;" alt="">
                                <p style="margin-bottom: 1px;"><b><u>MACK ALFRED L. CHICO, RMT</u></b></p>
                                <p style="margin-bottom: 1px;">Medical Technologist I</p>
                                <p>License No. 0082495</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-center">
                                <p>Verified By:</p>
                                <img src="{{asset('assets/images/signatureonly_docathan.png')}}" style="width: 9rem; margin-bottom: -30px;" alt="">
                                <p style="margin-bottom: 1px;"><b><u>JONATHAN P. LUSECO, M.D</u></b></p>
                                <p style="margin-bottom: 1px;">City Health Officer II</p>
                                <p>License No. 102377</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <td class="text-center">
                                <div>{!! QrCode::size(130)->generate(route('qrcodeverify.index', ['qr' => $details->antigenqr])) !!}</div>
                                <span>SCAN TO VERIFY RESULT</span>
                            </td>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection