@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card border-success">
            <div class="card-header font-weight-bold text-info">Completing the Registration</div>
            <div class="card-body text-center">
                <p>Thank you for submitting your details.</p>
                <p>Your request will be processed by CESU CHO General Trias in 1-5 Days.</p>
                <p>Please wait until you are called/messaged by your respective Barangay. You can also use the schedule code provided below to check the status of your swab schedule.</p>
                <hr>
                <p>Your Schedule Code is:</p>
                <strong class="my-3"><h3>{{session('majik')}}</h3></strong>
                <p><strong class="text-danger">PLEASE SAVE AND DO NOT FORGET YOUR SCHEDULE CODE.</strong> You can use it to directly check the status of your swab schedule in <a href="{{route('main')}}">cesugentri.com</a> then go to [I am a Patient] Section.</p>
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
                <a href="{{route('paswab.index')}}?rlink={{session('fcode')}}&s={{session('scode')}}" class="btn btn-link">Submit another Request</a>
                <hr>
                <a href="{{route('main')}}" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
@endsection