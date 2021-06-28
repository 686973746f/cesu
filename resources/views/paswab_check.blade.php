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
                    Rejected
                @else
                    Approved
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