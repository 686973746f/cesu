@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-success">
            <div class="card-header bg-success font-weight-bold text-white text-center"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Submission Complete</div>
            <div class="card-body text-center">
                <p>Your request has been submitted. Thank you for your cooperation.</p>
                <p>We will try to contact you as soon as possible for the next instructions on how we can assist you.</p>
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
        </div>
    </div>
@endsection