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

                <h4 class="text-center mt-3 mb-5"><b><u>SPECIMEN SENDING FORM</u></b></h4>
            
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
                        <h5><b>Type of Case:</b></h5>
                        <h5>{{$d->disease_tag}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Title:</b></h5>
                        <h5>{{$d->title}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Encoded at/by:</b></h5>
                        <h5>
                            <div>{{date('M. d, Y h:i A', strtotime($d->created_at))}}</div>
                            <div>by - {{$d->user->name}}</div>
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h5><b>Specimen Type:</b></h5>
                        <h5></h5>
                        <h5><b>Test Type:</b></h5>
                        <h5></h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Name of Collector/Swabber and Signature:</b></h5>
                        <h5>{{$d->base_collector_name}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Sent to RITM?:</b></h5>
                        <h5>{{$d->sent_to_ritm}}</h5>
                    </div>
                </div>
                @if($d->sent_to_ritm == 'Y')
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
                    <div class="col-4">

                    </div>
                </div>
                @endif
                <hr>
                <div class="mb-5">
                    <h5><b>Remarks:</b> {{(!is_null($d->remarks)) ? $d->remarks : 'N/A'}}</h5>
                </div>

                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>
                                <div>Specimen Type/</div>
                                <div>Test Type</div>
                            </th>
                            <th>Date Collected</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fetch_list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td>{{$l->getName()}}</td>
                            <td class="text-center">{{$l->age}}/{{$l->gender}}</td>
                            <td class="text-center">
                                <div>{{$l->specimen_type}}</div>
                                <div>{{$l->test_type}}</div>
                            </td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->date_collected))}}</td>
                            <td class="text-center">{{(!is_null($l->remarks)) ? $l->remarks : ''}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center mt-3">
                    <p class="h5">-- END OF LINELIST --</p>
                </div>

                <div class="text-center mt-5">
                    <hr>
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

        $('#mainTbl').dataTable({
            dom: 't',
            iDisplayLength: -1,
            ordering: false,
        });
    </script>
@endsection