@extends('layouts.app')

@section('content')
<style>
    #ulman {
        list-style: none;
    }

    #ulman li:before {
        content: '✓ ';
    }
    
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
<div class="container">
    <div class="card">
        <div class="card-header" id="titleBody">
            <div class="d-flex justify-content-between">
                <div>Online Medical Certificate</div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
            </div>
        </div>
        <div>
            <div class="card-body" style="font-family: Arial, Helvetica, sans-serif">
                <div id="divToPrint">
                    <div class="text-center">
                        <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                    </div>
                    <h4 class="font-weight-bold text-center my-3">MEDICAL CERTIFICATE</h4>
                    <p style="font-size:20px;"><strong>Date:</strong> <u>{{date('m/d/Y')}}</u></p>
                    <p style="font-size:20px;text-align: justify;"><strong>Last Name:</strong> <u>{{$data->records->lname}}</u> <strong>First Name:</strong> <u>{{$data->records->fname}}</u> <strong>M.I:</strong> <u>{{(!is_null($data->records->mname)) ? substr($data->records->mname,0,1).'.' : 'N/A'}}</u></p>
                    <p style="font-size:20px;">
                        <strong>Address:</strong> <u>{{$data->records->address_city}}</u>
                        <strong>Barangay:</strong> <u>{{$data->records->address_brgy}}</u>
                        <strong>Age/Sex:</strong> <u>{{$data->records->getAgeInt()}} / {{substr($data->records->gender,0,1)}}</u>
                    </p>
                    <p style="font-size:20px;"><strong>CAUSE OF INJURY/ILLNESS:</strong> <u>Medical</u></p>
                    <p style="font-size:20px;"><strong>PLACE OF ISOLATION/QUARANTINE:</strong> <u>{{$admitted}}</u></p>
                    <p style="font-size:20px;">
                        <strong>DATE START: </strong> <u>{{date('m/d/Y', strtotime($req->qDateStart))}}</u>
                        <strong>DATE END:</strong> <u>{{date('m/d/Y', strtotime($req->qDateEnd))}}</u>
                    </p>
                    <p style="font-size:20px;"><strong>DIAGNOSIS:</strong> <u>Clinically Recovered Confirmed</u></p>
                    <p style="font-size:20px;"><strong>PERIOD OF QUARANTINE:</strong> <u>{{Carbon\Carbon::parse($req->qDateEnd)->diffInDays($req->qDateStart)+1}} Days</u></p>
                    <p style="font-size:20px;"><strong>REMARKS:</strong> Patient has had no symptoms for at least 3 days prior to discharge and has completed {{Carbon\Carbon::parse($req->qDateEnd)->diffInDays($req->qDateStart)+1}} days quarantine.</p>
                    <p style="font-size:16px;margin-bottom: 3px;"><strong>DOH MEMORANDUM No 2020-00258 II.9</strong> Discharge and recovery criteria for suspect, probable and Confirmed COVID-19 cases shall no longer entail repeat testing. Symptomatic patients who have clinically recovered and are no longer symptomatic for at least 3 days and have completed at least 14 days of isolation either at home, temporary treatment and monitoring facility or hospital can be tagged as a recovered confirmed case and can be reintegrated to the community without the need for further testing provided that a licensed medical doctor clears the patients. Patients who test RT-PCR positive and remain asymptomatic for at least 14 days can discontinue quarantine and tagged as a recovered confirmed case without need for further testing, provided a licensed medical doctor clears the patient.</p>
                    <p style="font-size:20px;margin-bottom: 3px;"><strong>RECOMMENDATIONS:</strong></p>
                    <ul style="font-size:20px;" id="ulman">
                        <li>Wear mask always</li>
                        <li>Daily monitoring of body temperature, symptoms of cough, colds, sore throat and difficulty of breathing.</li>
                        <li>Promptly report to local health until if above symptoms are noticed after discharge from the quarantine.</li>
                    </ul>
                    <p style="font-size:18px;"><strong>Not valid as Medico-Legal Document</strong></p>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-3"></div>
                        <div class="col-6 text-center">
                            <p style="margin-bottom: 1px;"><strong>Noted by:</strong> <u>{{$whonote}}</u></p>
                            <p style="margin-bottom: 1px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Midwife/Nurse</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h5 style="margin-bottom: 1px;"><strong>CERTIFIED BY:</strong></h5>
                            <div class="text-center">
                                <img src="{{asset('assets/images/signatureonly_docyves.png')}}" style="width: 9rem;">
                                <h5 style="margin-bottom: 1px;"><strong>YVES M. TALOSIG, MD</strong></h5>
                                <h5 style="margin-bottom: 1px;">Medical Officer III</h5>
                                <h5>Reg. # 0112243</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 style="margin-bottom: 1px;"><strong>APPROVED BY:</strong></h5>
                            <div class="text-center">
                                <img src="{{asset('assets/images/signatureonly_docathan.png')}}" style="width: 9rem;">
                                <h5 style="margin-bottom: 1px;"><strong>JONATHAN P. LUSECO, MD</strong></h5>
                                <h5 style="margin-bottom: 1px;">City Health Officer II</h5>
                                <h5>Reg. # 102377</h5>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <p style="margin-bottom: 1px;"><strong>Office of the City Health Officer</strong></p>
                        <p style="margin-bottom: 1px;">Pria Rd, Brgy. Pinagtipunan, General Trias, City, 4107 Cavite</p>
                        <p style="margin-bottom: 1px;">Website: <a href="#">https://generaltrias.gov.ph/cho/</a></p>
                        <p style="margin-bottom: 1px;">Contact No: (046) 509 - 5289</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection