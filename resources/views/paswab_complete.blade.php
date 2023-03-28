@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card border-success">
            <div class="card-header font-weight-bold text-white bg-success text-center"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Submission Complete</div>
            <div class="card-body text-center">
                <p>Thank you for submitting your details.</p>
                <p>Your request will be processed by CESU General Trias in 1-5 Days.</p>
                <p>Your respective Barangay Office will contact you <i>(via Call or Text)</i> regarding on your Schedule. You can also use the schedule code provided below to check the status of your swab schedule.</p>
                <hr>
                <p>Your Schedule Code is:</p>
                <strong class="my-3"><h3>{{session('majik')}}</h3></strong>
                <p><strong class="text-danger">PLEASE SAVE AND DO NOT FORGET YOUR SCHEDULE CODE.</strong></p>
                <p><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>You can use it to directly check the status of your swab schedule in <a href="{{route('main')}}">cesugentri.com</a> then go to [I am a Patient] Section</p>
                <hr>
                <div class="alert alert-info" role="alert">
                <p>In order to get in touch with you properly, please make sure that:</p>
                <li><i class="fas fa-mobile-alt mr-2"></i>The Phone where your Mobile Number is connected is <strong>NOT TURNED OFF</strong>.</li>
                <li><i class="fas fa-signal mr-2"></i>The Mobile Number you submitted is <strong>CORRECT and ACTIVE </strong> and has a <strong>GOOD SIGNAL RECEPTION</strong> on your current location.</li>
                </div>
                <hr>
                <p>If you have any concerns, you may contact us at:</p>
                <table class="table text-center table-bordered">
                    <tbody>
                        <tr>
                            <td scope="row" rowspan="3" style="vertical-align: middle;"><i class="fas fa-phone-alt mr-2 bg-light"></i>Mobile Numbers</td>
                            <td>0919 066 4324</td>
                        </tr>
                        <tr>
                            <td>0919 066 4325</td>
                        </tr>
                        <tr>
                            <td>0919 066 4327</td>
                        </tr>
                        <tr>
                            <td scope="row" class="bg-light">Telephone Number</td>
                            <td>(046) 509 - 5289</td>
                        </tr>
                        <tr>
                            <td scope="row" class="bg-light"><i class="fas fa-at mr-2"></i>Email Address</td>
                            <td><a href = "mailto: cesu.gentrias@gmail.com">cesu.gentrias@gmail.com</a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><a href="https://www.facebook.com/cesugentrias">Facebook Page</a></td>
                        </tr>
                        <tr>
                            <td scope="row" class="bg-light">Address</td>
                            <td>City Health Office (3rd Floor CESU Office), Pria Rd., Hospital Area - Main, Brgy. Pinagtipunan, General Trias, Cavite, 4107</td>
                        </tr>
                    </tbody>
                </table>
                <p class="font-weight-bold"><i class="far fa-clock mr-2"></i>Office Hours: 08:00 AM - 05:00 PM (from Monday - Friday ONLY)</p>
                <p>Thank you. Keep Safe.</p>                
            </div>
            <div class="card-footer text-center">
                <a href="{{route('paswab.index', app()->getLocale())}}?rlink={{session('fcode')}}&s={{session('scode')}}" class="btn btn-link">Submit another Request</a>
                <hr>
                <a href="{{route('main')}}" class="btn btn-link">Back to Home</a>
            </div>
        </div>
        <p class="mt-3 text-center">Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>
@endsection