@extends('layouts.app')

@section('content')
    <div class="container">
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
                            <td>Date Submitted</td>
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
                            <td class="text-danger"><strong>REJECTED</strong></td>
                        </tr>
                        <tr>
                            <td>Date Submitted</td>
                            <td>{{date('m/d/Y h:i A', strtotime($data->created_at))}}</td>
                        </tr>
                    </tbody>
                </table>
                @else
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Status</td>
                            <td><strong class="text-success">APPROVED</strong> @ {{date('m/d/Y h:i A', strtotime($data->updated_at))}}</td>
                        </tr>
                        <tr>
                            <td>Date Submitted</td>
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
                        <p>You have been scheduled at <strong>{{date('m/d/Y (l)', strtotime($form->testDateCollected1))}}.</strong> Please be on time at COVID-19 Testing Center on General Trias Oval for the collection of your swab.</p>
                        <p>Please be present as <strong class="text-danger">re-scheduling of swab collection is strictly prohibited.</strong> Please be guided accordingly.</p>
                    </div>
                </div>
                @endif
                <hr>
                <p>If you have any concerns, you may contact us at:</p>
                <table class="table text-center table-bordered">
                    <tbody>
                        <tr>
                            <td scope="row" rowspan="3" style="vertical-align: middle;">Mobile Numbers</td>
                            <td>+639190664324</td>
                        </tr>
                        <tr>
                            <td>+639190664325</td>
                        </tr>
                        <tr>
                            <td>+639190664327</td>
                        </tr>
                        <tr>
                            <td scope="row">Email Address</td>
                            <td>cesu.gentrias@gmail.com</td>
                        </tr>
                    </tbody>
                </table>
                <p class="font-weight-bold">Office Hours: 08:00 AM - 05:00 PM</p>
                <p>Thank you. Keep Safe.</p>
            </div>
            <div class="card-footer text-center">
                <a href="{{route('main')}}" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
@endsection