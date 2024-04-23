@extends('layouts.app')

@section('content')
<style>
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
                    <div><b>Print Specimen Sending Form</b></div>
                    <div><button type="button" class="btn btn-success" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print <i>(CTRL + P)</i></button></div>
                </div>
            </div>
            <div class="card-body" id="divToPrint">
                <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="img-fluid" style="margin-top: 0px;">

                <h4 class="text-center mt-3 mb-5"><b>SPECIMEN SENDING FORM</b></h4>
            
                <div class="row">
                    <div class="col-4">

                    </div>
                    <div class="col-4">

                    </div>
                    <div class="col-4">

                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h5><b>For Case:</b></h5>
                        <h5>{{$d->disease_tag}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Linked EDCS-IS Case ID:</b></h5>
                        <h5>{{(!is_null($d->for_case_id)) ? $d->for_case_id : 'N/A'}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Encoded at/by:</b></h5>
                        <h5>
                            <div>{{date('F d, Y h:i A', strtotime($d->created_at))}}</div>
                            <div>by - {{$d->user->name}}</div>
                        </h5>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <h5><b>Name:</b></h5>
                        <h5>{{$d->getName()}}</h5>
                    </div>
                    <div class="col-6">
                        <h5><b>Age/Sex:</b></h5>
                        <h5>{{$d->age}}/{{$d->gender}}</h5>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <h5><b>Specimen Type:</b></h5>
                        <h5>{{$d->specimen_type}}</h5>
                        <h5><b>Test Type:</b></h5>
                        <h5>{{$d->test_type}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Date and Time Collected:</b></h5>
                        <h5>{{date('m/d/Y', strtotime($d->date_collected))}}</h5>
                        <h5><b>Name of Collector/Swabber and Signature:</b></h5>
                        <h5>{{$d->collector_name}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Sent to RITM?:</b></h5>
                        <h5>{{$d->sent_to_ritm}}</h5>
                    </div>
                </div>
                @if($d->sent_to_ritm == 'Y')
                <hr>
                <div class="row">
                    <div class="col-4">
                        <h5><b>Date Sent to RITM:</b></h5>
                        <h5>{{date('m/d/Y', strtotime($d->ritm_date_sent))}}</h5>
                        <h5><b>Name of Driver and Signature:</b></h5>
                        <h5>{{$d->driver_name}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Date Received by RITM:</b></h5>
                        <h5>{{(!is_null($d->ritm_date_received)) ? date('m/d/Y', strtotime($d->ritm_date_received)) : '_________________________'}}</h5>
                        <h5><b>Name of Receiver/Signature:</b></h5>
                        <h5>_________________________</h5>
                    </div>
                </div>
                @endif

                @if($d->result != 'PENDING')
                <hr>
                <div class="row">
                    <div class="col-4">
                        <h5><b>Date Released:</b></h5>
                        <h5>{{(!is_null($d->ritm_date_received)) ? date('m/d/Y', strtotime($d->ritm_date_received)) : ''}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Result:</b></h5>
                        <h5>{{$d->result}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Interpretation:</b></h5>
                        <h5>{{$d->interpretation}}</h5>
                    </div>
                </div>
                @endif
                <hr>
                <div class="mb-5">
                    <h5><b>Remarks:</b></h5>
                    <h5>{{$d->remarks}}</h5>
                </div>

                <div class="text-center mt-5">
                    <h6><b>City Epidemiology and Surveillance Unit (CESU)</b></h6>
                    <h6>3rd Floor CESU Room, City Health Office, Hospital Rd., Brgy. Pinagtipunan, General Trias, Cavite</h6>
                    <h6>Email: <a href="">cesu.gentrias@gmail.com</a></h6>
                    <h6>Contact Numbers: 0962 545 6998 or 0954 154 8355</h6>
                    <h6>Telephone: (046) 509 5289</h6>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            window.print();
        });
    </script>
@endsection