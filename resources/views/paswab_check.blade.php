@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card border-info">
            <div class="card-header font-weight-bold text-info">Check Schedule Details</div>
            <div class="card-body text-center">
                <p>Good Day: <strong>{{$data->getName()}}</strong></p>
                <hr>
                @if($data->status == 'pending')
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td style="color: orange;"><strong>PENDING</strong></td>
                        </tr>
                        <tr>
                            <td>Your Date of Submission</td>
                            <td>{{date('m/d/Y h:i A', strtotime($data->created_at))}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card">
                    <div class="card-header"><i class="fa fa-question-circle mr-2" aria-hidden="true"></i>What does it mean?</div>
                    <div class="card-body">
                        <p>Your swab schedule is <strong>still under pending</strong> and not yet checked.</p>
                        <p>It will be verified and processed by CESU Staff/Encoders as soon as possible.</p>
                        <p>Try to check after a few days.</p>
                    </div>
                </div>
                @elseif($data->status == 'rejected')
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td><strong class="text-danger">REJECTED</strong></td>
                        </tr>
                        <tr>
                            <td>Reason for Rejection from Staff</td>
                            <td>{{$data->remarks}}</td>
                        </tr>
                        <tr>
                            <td>Date Submitted</td>
                            <td>{{date('m/d/Y h:i A', strtotime($data->created_at))}}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="card">
                    <div class="card-header"><i class="fa fa-question-circle mr-2" aria-hidden="true"></i>What does it mean?</div>
                    <div class="card-body">
                        <p>Your request for swab has been <strong class="text-danger">rejected</strong> by CESU Staffs/Encoders. The reason for rejection is stated above.</p>
                        <p>You could try submitting again, together with correction following the message stated in the reason for rejection.</p>
                    </div>
                </div>
                @else
                @if(!is_null($form->isPresentOnSwabDay) && $form->isPresentOnSwabDay == 0 && $form->testDateCollected1 < date('Y-m-d'))
                <div class="card border-danger">
                    <div class="card-header bg-danger text-center text-white font-weight-bold">You did not attended on your Swab Collection Schedule on {{date('F d, Y (l)', strtotime($form->testDateCollected1))}}</div>
                    <div class="card-body text-center">
                        <p>You can report to your respective Barangay (BRGY. {{$data->address_brgy}}) about what happened and for possibilities of re-scheduling.</p>
                    </div>
                </div>
                @elseif(!is_null($form->isPresentOnSwabDay) && $form->isPresentOnSwabDay == 1 && $form->testDateCollected1 < date('Y-m-d'))
                <div class="card border-success">
                    <div class="card-header bg-success text-center font-weight-bold">Your swab test schedule was completed on {{date('F d, Y (l)', strtotime($form->testDateCollected1))}}</div>
                    <div class="card-body text-center">
                        @if($form->testType1 == 'OPS' || $form->testType1 == 'NPS' || $form->testType1 == 'OPS AND NPS')
                        <p>Please wait for 3-7 Days regarding the release your swab test result.</p>
                        <p>You will be also informed by your respective Barangay (BRGY. {{$data->address_brgy}}) if you already have a result.</p>
                        <p>Thank you for attending and cooperating with us.</p>
                        @else
                        <p>Thank you for attending and cooperating with us.</p>
                        @endif
                        <p></p>
                    </div>
                </div>
                @else
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td><strong class="text-success">APPROVED</strong></td>
                        </tr>
                        <tr>
                            <td>Your Date of Submission</td>
                            <td>{{date('m/d/Y h:i A', strtotime($data->created_at))}}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>You are scheduled for swab at</td>
                            <td>{{date('m/d/Y (l)', strtotime($form->testDateCollected1))}}</td>
                        </tr>
                        <tr>
                            <td>Test Type</td>
                            <td>{{$form->testType1}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card">
                    <div class="card-header"><i class="fa fa-question-circle mr-2" aria-hidden="true"></i>What does it mean?</div>
                    <div class="card-body">
                        <p>Your swab schedule is now <strong class="text-success">approved</strong> by CESU Staff/Encoders.</p>
                        <p>You have been scheduled at <strong>{{date('m/d/Y (l)', strtotime($form->testDateCollected1))}}.</strong> Please be on time at COVID-19 Testing Center on General Trias Oval, Brgy. Santiago for the collection of your swab.</p>
                        <p>Please be present as <strong class="text-danger">re-scheduling of swab collection is strictly prohibited.</strong> No Face Mask and Face Shield, No Swab.</p>
                        <p>Also bring all of the photocopies of your requirements (such as Philhealth ID or MDR, PSA/Birth Certificate, and Any Valid IDs)</p>
                        <hr>
                        @if($form->testType1 == 'OPS' || $form->testType1 == 'NPS' || $form->testType1 == 'OPS AND NPS')
                        <p>Your swab test will be collected using <strong>{{$form->testType1}}</strong>. Results will take 5-7 Days to release. After your requirements is checked and your swab has been collected, you can leave the testing facility immediately.</p>
                        @else
                        <p>Your swab test will be collected using <strong>{{$form->testType1}}</strong>. Results will take 30 Minutes or Less to finish. Do not leave the testing facility until the result paper was given to you.</p>
                        @endif
                        <hr>
                        <div class="alert alert-info" role="alert">
                            Note: Due to the limited slots of patients that can be entertained per day, your pa-swab schedule could be re-scheduled without prior notice.
                        </div>
                        <p>Please be guided accordingly.</p>
                    </div>
                </div>
                @endif
                @endif
                <hr>
                <p>If you have any concerns, you may contact us at:</p>
                <table class="table text-center table-bordered">
                    <tbody>
                        <tr>
                            <td scope="row" rowspan="3" style="vertical-align: middle;">Mobile Numbers</td>
                            <td>+63919 066 4324</td>
                        </tr>
                        <tr>
                            <td>+63919 066 4325</td>
                        </tr>
                        <tr>
                            <td>+63919 066 4327</td>
                        </tr>
                        <tr>
                            <td scope="row">Email Address</td>
                            <td>cesu.gentrias@gmail.com</td>
                        </tr>
                    </tbody>
                </table>
                <p class="font-weight-bold">Office Hours: 08:00 AM - 05:00 PM (from Monday - Friday)</p>
                <p>Thank you. Keep Safe.</p>
            </div>
            <div class="card-footer text-center">
                <a href="{{route('main')}}" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
@endsection