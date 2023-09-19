@extends('layouts.app')

@section('content')
<style>
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 70%;
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
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Medical Certificate Preview</b></div>
                    <div><button type="button" class="btn btn-success" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print <i>(CTRL + P)</i></button></div>
                </div>
            </div>
            <div class="card-body" id="divToPrint">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                </div>
                <p style="font-size: 25px;" class="text-center mb-3"><b>MEDICAL CERTIFICATE</b></p>
                <div class="text-right mb-3">
                    <h5>Date: <u>{{date('m/d/Y', strtotime($d->medcert_generated_date))}}</u></h5>
                </div>
                <p style="font-size: 20px;">To whom it may concern:</p>
                <p style="font-size: 20px;text-align:justify">This is to certify that I have examined / treated <b><u>{{$d->syndromic_patient->getName()}}</u></b>, <b><u>{{$d->syndromic_patient->getAge()}}</u></b> years old <u><b>{{$d->syndromic_patient->gender}} / {{$d->syndromic_patient->cs}}</b></u>, a resident of <u><b>{{$d->syndromic_patient->getFullAddress()}}</b></u> from {{$d->getMedCertStartDate()}} to {{$d->getMedCertEndDate()}}, inclusive.</p>
                <p style="font-size: 20px;">BP: <u class="mr-3">{{(!is_null($d->bloodpressure)) ? $d->bloodpressure : '_____'}}</u> PR: <u class="mr-3">{{(!is_null($d->pulserate)) ? $d->pulserate : '_____'}}</u> RR: <u class="mr-3">{{(!is_null($d->respiratoryrate)) ? $d->respiratoryrate : '_____'}}</u> HT: <u class="mr-3">{{(!is_null($d->height)) ? $d->height.'cm' : '_____'}}</u> WT: <u class="mr-3">{{(!is_null($d->weight)) ? $d->weight.'kg' : '_____'}}</u> TEMP: <u>{{(!is_null($d->temperature)) ? $d->temperature.'°C' : '_____'}}</u></p>
                <p style="font-size: 20px;margin-bottom: 300px;"><b>FINDINGS / IMPRESSION:</b></p>
                <p style="font-size: 20px;margin-bottom: 300px;"><b>REMARKS:</b></p>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <h5 style="margin-bottom: 0px;"><b><u>{{$d->name_of_physician}}</u></b></h5>                                
                                <h5 style="margin-bottom: 0px;">{{$d->getPhysicianDetails()->position}}</h5>
                                <h5>Reg. No. {{$d->getPhysicianDetails()->reg_no}}</h5>
                            </td>
                            <td class="text-center">
                                <div>{!! QrCode::size(120)->generate(route('medcert_online_verify', $d->qr)) !!}</div>
                                <div>SCAN TO VERIFY MEDCERT</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6 text-center">
                        
                    </div>
                    <div class="col-md-6 text-center">
                        
                    </div>
                </div>
                <h4 class="text-center"><b><span class="text-primary">Let's Join Forces</span> <span class="text-success">For a Healthier</span> <span class="text-success">Gen</span><span class="text-primary">Tri</span></b></h4>
                <hr>
                <div class="text-center">
                    <h6><b>Office of the City Health Officer</b></h6>
                    <h6>Hospital Rd., Brgy. Pinagtipunan, General Trias City, 4107 Cavite</h6>
                    <h6>Website: <a href="">https://generaltrias.gov.ph/cho</a></h6>
                    <h6>Contact No: (046) 509-5289</h6>
                </div>
            </div>
        </div>
    </div>
@endsection