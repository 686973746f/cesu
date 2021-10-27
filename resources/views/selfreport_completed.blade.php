@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-success">
            <div class="card-header bg-success font-weight-bold text-white text-center"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Submission Complete</div>
            <div class="card-body text-center">
                <p>Your request has been submitted. Thank you for your cooperation.</p>
                <p>We will contact you as soon as possible (via Call or Text) for the next instructions on how we can assist you.</p>
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
                            <td scope="row" rowspan="3" style="vertical-align: middle;"><i class="fas fa-phone-alt mr-2"></i>Mobile Numbers</td>
                            <td>+63919 066 4324</td>
                        </tr>
                        <tr>
                            <td>+63919 066 4325</td>
                        </tr>
                        <tr>
                            <td>+63919 066 4327</td>
                        </tr>
                        <tr>
                            <td scope="row"><i class="fas fa-at mr-2"></i>Email Address</td>
                            <td><a href = "mailto: cesu.gentrias.covid19positive@gmail.com">cesu.gentrias.covid19positive@gmail.com</a></td>
                        </tr>
                    </tbody>
                </table>
                <p class="font-weight-bold"><i class="far fa-clock mr-2"></i>Office Hours: 08:00 AM - 05:00 PM (from Monday - Friday ONLY)</p>
                <p>Thank you. Keep Safe.</p>
            </div>
        </div>
    </div>
@endsection