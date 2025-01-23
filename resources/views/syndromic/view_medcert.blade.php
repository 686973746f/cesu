@extends('layouts.app')

@section('content')
<style>
    @if($d->facility_id == 10886 || $d->facility_id == 39708 || $d->facility_id == 11730)
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 70%;
    }
    @endif

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
                    <div><b>Medical Certificate</b></div>
                    <div><button type="button" class="btn btn-success" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print <i>(CTRL + P)</i></button></div>
                </div>
            </div>
            <div class="card-body" id="divToPrint">
                @if($d->facility_id == 10886 || $d->facility_id == 39708 || $d->facility_id == 11730)
                <h5>Control No.: {{$d->opdno}}</h5>
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                </div>
                @elseif($d->facility_id == 10525)
                <div class="d-flex justify-content-between text-center">
                    <div>
                        <img src="{{asset('assets/images/medicare_logo.png')}}" alt="" style="width: 8rem;">
                    </div>
                    <div>
                        <h4><b>CITY OF GENERAL TRIAS MEDICARE HOSPITAL</b></h4>
                        <h5>Gen. Trias City, Cavite</h5>
                        <h5>Tel. No. (046) 509-0064</h5>
                    </div>
                    <div>
                        <img src="{{asset('assets/images/gentri_icon_large.png')}}" alt="" style="width: 8rem;">
                        <img src="{{asset('assets/images/ljf.png')}}" alt="" style="width: 8rem;">
                    </div>
                </div>
                @else
                <h4 class="text-warning">NO LETTERHEAD DATA FOUND FOR FACILITY. PLEASE CONTACT CESU.</h4>
                @endif

                @if($d->facility_id == 10886 || $d->facility_id == 39708 || $d->facility_id == 11730)
                @if(in_array('DENTAL CARE', explode(",", $d->consultation_type)))
                <p style="font-size: 25px;" class="text-center mb-3"><b>DENTAL CERTIFICATE</b></p>
                @else
                <p style="font-size: 25px;" class="text-center mb-3"><b>MEDICAL CERTIFICATE</b></p>
                @endif
                <div class="text-right mb-3">
                    <h5>Date: <u>{{date('M. d, Y', strtotime($d->medcert_generated_date))}}</u></h5>
                </div>

                <p style="font-size: 20px;">To whom it may concern:</p>
                @if($d->facility_id == 11730)
                <p style="font-size: 20px;text-align:justify">This is to certify that I have examined/treated {{ucwords($d->syndromic_patient->getPrefix())}} <b><u>{{$d->syndromic_patient->getName()}}</u></b>, <b><u>{{$d->syndromic_patient->getAge()}}</u></b> years old, <u><b>{{$d->syndromic_patient->gender}}, {{$d->syndromic_patient->cs}}</b></u>, a resident of <u><b>{{$d->syndromic_patient->getFullAddress()}}</b></u> from <u>{{($d->medcert_start_date) ? date('F d, Y', strtotime($d->medcert_start_date)) : '                      '}}</u> to ______________________.</p>
                <p style="font-size: 20px;">BP: <u class="mr-3">{{(!is_null($d->bloodpressure)) ? $d->bloodpressure : '_____'}}</u> @if(!is_null($d->height))HT: <u class="mr-3">{{(!is_null($d->height)) ? $d->height.'cm' : '_____'}}</u>@endif @if(!is_null($d->weight))WT: <u class="mr-3">{{(!is_null($d->weight)) ? $d->weight.'kg' : '_____'}}</u>@endif TEMP: <u>{{(!is_null($d->temperature)) ? $d->temperature.'°C' : '_____'}}</u></p>
                @else
                <p style="font-size: 20px;text-align:justify">This is to certify that {{ucwords($d->syndromic_patient->getPrefix())}} <b><u>{{$d->syndromic_patient->getName()}}</u></b>, <b><u>{{$d->syndromic_patient->getAge()}}</u></b> years old <u><b>{{$d->syndromic_patient->gender}} / {{$d->syndromic_patient->cs}}</b></u>, a resident of <u><b>{{$d->syndromic_patient->getFullAddress()}}</b></u> was seen, examined or treated on our facility. @if($d->getMedCertStartDate() != '-') From <u>{{$d->getMedCertStartDate()}}</u> to <u>{{$d->getMedCertEndDate()}}</u>. @endif</p>
                <p style="font-size: 20px;">BP: <u class="mr-3">{{(!is_null($d->bloodpressure)) ? $d->bloodpressure : '_____'}}</u> PR: <u class="mr-3">{{(!is_null($d->pulserate)) ? $d->pulserate : '_____'}}</u> RR: <u class="mr-3">{{(!is_null($d->respiratoryrate)) ? $d->respiratoryrate : '_____'}}</u> HT: <u class="mr-3">{{(!is_null($d->height)) ? $d->height.'cm' : '_____'}}</u> WT: <u class="mr-3">{{(!is_null($d->weight)) ? $d->weight.'kg' : '_____'}}</u> TEMP: <u>{{(!is_null($d->temperature)) ? $d->temperature.'°C' : '_____'}}</u></p>
                @endif
                <p style="font-size: 20px;"><b>FINDINGS / IMPRESSION:</b></p>
                <p style="font-size: 20px;margin-bottom: 200px;margin-left: 50px;">{!! nl2br($d->dcnote_assessment) !!}</p>
                <p style="font-size: 20px;"><b>REMARKS:</b></p>
                <p style="font-size: 20px;margin-bottom: 200px;margin-left: 50px;">{!! nl2br($d->remarks) !!}</p>
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
                @elseif($d->facility_id == 10525)
                <p style="font-size: 25px;" class="text-center mb-3"><b>MEDICAL CERTIFICATE</b></p>
                <div class="text-right mb-3">
                    <h5>Date: <u>{{date('M. d,Y', strtotime($d->medcert_validity_date))}}</u></h5>
                </div>

                <div class="d-flex justify-content-between h5">
                    <div>
                        Name: <u><b>{{$d->syndromic_patient->getName()}}</b></u>
                    </div>
                    <div>
                        Age: <u>{{$d->syndromic_patient->getAgeInt()}}</u>
                    </div>
                    <div>
                        Sex: <u>{{$d->syndromic_patient->sg()}}</u>
                    </div>
                    <div>
                        Civil Status: <u>{{$d->syndromic_patient->cs}}</u>
                    </div>
                </div>
                <div class="d-flex justify-content-between h5">
                    <div>Address: <u>{{$d->syndromic_patient->getFullAddress()}}</u></div>
                    <div>Occupation: <u>{{(!is_null($d->syndromic_patient->occupation)) ? $d->syndromic_patient->occupation : 'N/A'}}</u></div>
                </div>
                <div class="row h5">
                    <div class="col-4">
                        Was seen examined / confined on
                    </div>
                    <div class="col-8 text-center">
                        <u>{{$d->getHospMedCertStartDate()}}</u>
                    </div>
                </div>
                <div class="row mt-3 h5">
                    <div class="col-3">DIAGNOSIS:</div>
                    <div class="col-9">
                        <u>{!! nl2br($d->dcnote_assessment) !!}</u>
                        <!--
                            <h5>______________________________________________________________________</h5>
                            <h5>______________________________________________________________________</h5>
                        -->
                    </div>
                </div>
                <div class="row h5">
                    <div class="col-3">TREATMENT:</div>
                    <div class="col-9">
                        <u>{!! nl2br($d->dcnote_diagprocedure) !!}</u>
                    </div>
                </div>
                <div class="row h5">
                    <div class="col-3">REMARKS:</div>
                    <div class="col-9">
                        @if(!is_null($d->remarks))
                        <u>{{$d->remarks}}</u>
                        @else
                            <h5>_________________________________________________________________</h5>
                            <h5>_________________________________________________________________</h5>
                        @endif
                    </div>
                </div>

                <h5 class="ml-5 mt-5 mb-3">Issued upon request of {!! (!is_null($d->medcert_purpose)) ? '<u><b>'.$d->medcert_purpose.'</b></u>' : '______________________' !!} for whatever purpose this may serve him/her best.</h5>
                <div class="row">
                    <div class="col-6 text-center">
                        <div>{!! QrCode::size(100)->generate(route('medcert_online_verify', $d->qr)) !!}</div>
                        <div>SCAN TO VERIFY MEDCERT</div>
                        <div><h6>(Not valid without seal)</h6></div>
                    </div>
                    <div class="col-6 text-center">
                        <h5 style="margin-bottom: 0px;" class="mt-5"><b><u>{{$d->name_of_physician}}</u></b></h5>                                
                        <h5 style="margin-bottom: 0px;">{{$d->getPhysicianDetails()->position}}</h5>
                        <h5>Reg. No. {{$d->getPhysicianDetails()->reg_no}}</h5>
                    </div>
                </div>
                @else
                
                @endif
                @if($d->facility_id == 10886 || $d->facility_id == 39708 || $d->facility_id == 11730)
                <h4 class="text-center mt-3"><b><span class="text-primary">Let's Join Forces</span> <span class="text-success">For a Healthier</span> <span class="text-success">Gen</span><span class="text-primary">Tri</span></b></h4>
                @endif
                @if($d->facility_id == 10886)
                <div class="text-center">
                    <hr>
                    <h6><b>Office of the City Health Officer</b></h6>
                    <h6>Hospital Rd., Brgy. Pinagtipunan, General Trias City, 4107 Cavite</h6>
                    <h6>Website: <a href="">https://generaltrias.gov.ph/cho</a> - Email: <a href="">chogentri@gmail.com</a></h6>
                    <h6>Contact No: (046) 509-5289</h6>
                </div>
                @elseif($d->facility_id == 39708)
                <div class="text-center">
                    <hr>
                    <h6><b>City of General Trias Super Health Center - Brgy. San Francisco</b></h6>
                    <h6>Arnaldo Highway, Sitio Elang, Brgy. San Francisco, General Trias, Cavite</h6>
                    <h6>Website: <a href="">https://generaltrias.gov.ph/cho</a> - Email: <a href="">chogentri@gmail.com</a></h6>
                </div>
                @elseif($d->facility_id == 11730)
                <div class="text-center">
                    <hr>
                    <h6><b>General Trias City Health Center - Brgy. Manggahan</b></h6>
                    <h6>Opal St., Sunshine Village, Brgy. Manggahan, General Trias, Cavite</h6>
                    <h6>Website: <a href="">https://generaltrias.gov.ph/cho</a> - Email: <a href="">chogentri@gmail.com</a></h6>
                </div>
                @else

                @endif
            </div>
        </div>
    </div>
@endsection