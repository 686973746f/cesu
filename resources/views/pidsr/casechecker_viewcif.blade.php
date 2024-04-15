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
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>View Online CIF</div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
            </div>
        </div>
        <div class="card-body" id="divToPrint">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-left"><img src="{{asset('assets/images/pidsrlogo.png')}}" style="width: 5rem;" class="img-responsive"></td>
                        <td class="text-center">
                            <h5>Case Investigation Form</h5>
                            <h4><b>{{$flavor_title}}</b></h4>
                        </td>
                        <td class="text-right"><img src="{{asset('assets/images/doh_logo.png')}}" style="width: 5rem;" class="img-responsive"></td>
                    </tr>
                </tbody>
            </table>
            <div class="row text-center">
                <div class="col-6">
                    <h6><b>EPI ID:</b> {{$p->EPIID}}</h6>
                </div>
                <div class="col-6">
                    <h6><b>Case ID:</b> {{$p->edcs_caseid}}</h6>
                </div>
            </div>
            <table class="table table-bordered mt-3">
                <tbody>
                    <tr>
                        <td colspan="3">
                            <h6><b>Name of DRU:</b></h6>
                            <h6>{{$p->NameOfDru}}</h6>
                        </td>
                        <td colspan="2">
                            <h6><b>Date Encoded:</b></h6>
                            <h6>{{date('M d, Y', strtotime($p->DateOfEntry))}}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="bg-light"><h6><b>I. PATIENT PROFILE</b></h6></td>
                    </tr>
                    <tr>
                        <td>
                            <h6>Patient No.:</h6>
                            <h6>{{$p->PatientNumber}}</h6>
                        </td>
                        <td>
                            <h6><b>Last Name:</b></h6>
                            <h6>{{$p->FamilyName}}</h6>
                        </td>
                        <td>
                            <h6><b>First Name:</b></h6>
                            <h6>{{$p->FirstName}}</h6>
                        </td>
                        <td>
                            <h6><b>Middle Name:</b></h6>
                            <h6>{{(!is_null($p->middle_name)) ? $p->middle_name : 'N/A'}}</h6>
                        </td>
                        <td>
                            <h6><b>Suffix:</b></h6>
                            <h6>{{!is_null($p->suffix) ? $p->suffix : 'N/A'}}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6><b>Birthdate:</b></h6>
                            <h6>{{date('m/d/Y', strtotime($p->DOB))}}</h6>
                        </td>
                        <td>
                            <h6><b>Sex:</b></h6>
                            <h6>{{$p->Sex}}</h6>
                        </td>
                        <td>
                            <h6><b>Age (Years):</b></h6>
                            <h6>{{$p->AgeYears}}</h6>
                        </td>
                        <td>
                            <h6><b>Age (Months):</b></h6>
                            <h6>{{$p->AgeMons}}</h6>
                        </td>
                        <td>
                            <h6><b>Age (Days):</b></h6>
                            <h6>{{$p->AgeDays}}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <h6><b>Current Address:</b></h6>
                            <h6>{{(!is_null($p->Streetpurok)) ? $p->Streetpurok.', ' : ''}} BRGY. {{$p->Barangay}}, {{$p->Muncity}}, {{$p->Province}}</h6>
                        </td>
                        <td colspan="2">
                            <h6><b>Contact Number:</b></h6>
                            <h6></h6>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card mt-3">
                <div class="card-header"><b>CASE DETAILS</b></div>
                <div class="card-body">
                    @if($disease == 'PERT')
                    <div class="row">
                        <div class="col-6">
                            <h6><b>Name of Parent/Caregiver:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-6">
                            <h6><b>Contact Nos.:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Date of Report:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Name of Reporter:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Contact Nos.:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Date of Investigation:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Name of Investigator/s:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Contact Nos.:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Pertussis-containing vaccine doses:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>If Yes, Number of total doses:</b></h6>
                            <h6></h6>
                        </div>
                        <div class="col-4">
                            <h6><b>Date of last vaccination:</b></h6>
                            <h6></h6>
                        </div>
                    </div>
                    @else

                    @endif
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header"><b>LABORATORY DETAILS</b></div>
                <div class="card-body">
                    @if($lab_details->count() != 0)
                    @foreach($lab_details as $ind => $lb)
                    <table class="table table-bordered mb-3">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="5">
                                    <div class="d-flex justify-content-between">
                                        <div>#{{$ind+1}}</div>
                                        <div>Lab ID: {{$lb->lab_id}}</div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <h6><b>Specimen Type:</b></h6>
                                    <h6>{{$lb->test_type}}</h6>
                                </td>
                                <td>
                                    <h6><b>Date Specimen Collected:</b></h6>
                                    <h6>{{(date('m/d/Y', strtotime($lb->specimen_collected_date)))}}</h6>
                                </td>
                                <td>
                                    <h6><b>Sent to RITM:</b></h6>
                                    <h6>{{$lb->sent_to_ritm}}</h6>
                                </td>
                                <td>
                                    <h6><b>Date Sent to RITM:</b></h6>
                                    <h6>{{(!is_null($lb->date_sent)) ? (date('m/d/Y', strtotime($lb->date_sent))) : 'N/A'}}</h6>
                                </td>
                                <td>
                                    <h6><b>Date Received by Laboratory:</b></h6>
                                    <h6>{{(!is_null($lb->date_received)) ? (date('m/d/Y', strtotime($lb->date_received))) : 'N/A'}}</h6>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6><b>Result:</b></h6>
                                    <h6>{{$lb->result}}</h6>
                                </td>
                                <td>
                                    <h6><b>Type of Test Conducted:</b></h6>
                                    <h6>{{$lb->specimen_type}}</h6>
                                </td>
                                <td colspan="3">
                                    <h6><b>Interpretation:</b></h6>
                                    <h6>{{$lb->interpretation}}</h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                    @else
                    <p class="text-center">No Laboratory details to show.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection