@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><b>VaxCert Concern Ticketing System: Follow-up Ticket Status</b></div>
            <div class="card-body">
                <div class="text-center">
                    <p class="h5">Good Day. Your ticket:</p>
                    <p class="h2 text-success font-weight-bold my-3">{{$d->sys_code}}</p>
                    @if($d->status == 'COMPLETED')
                    <p class="h4">Was <b class="text-success">RESOLVED</b> on {{date('F d, Y h:i A', strtotime($d->updated_at))}} by our staff.</p>
                    <p class="h5">You may now try to generate using the <a href="https://vaxcert.doh.gov.ph/#/request">VaxCertPH Website</a> and your records should appear properly now.</p>
                    <p class="h5">Thank you.</p>
                    @elseif($d->status == 'PENDING')
                    <p class="h4">Is still <b class="text-warning">PENDING</b> and not checked by our staff yet.</p>
                    <p class="h5">Checking usually takes 2-3 Days. Kindly check your email/text messages for updates or additional info needed by our staff in order complete your ticket.</p>
                    <p class="h5">Thank you for understanding.</p>
                    @else
                    <p class="h4">Was marked as <b class="text-danger">REJECTED</b></p>
                    <p class="h5">REASON: {{$d->user_remarks}}</p>
                    <p class="h5">Thank you for understanding.</p>
                    @endif
                </div>
                <hr>
                <h5>
                    <ul>
                        For concerns, questions, or suggestions, you may contact us at:
                        <li>Email: cesu.gentrias@gmail.com</li>
                        <li>Telephone: (046) 509 5289</li>
                        <li>Mobile Number:
                            <ul>
                                <li>0962 545 6998 (SMART)</li>
                                <li>0954 154 8355 (GLOBE)</li>
                            </ul>
                        </li>
                        <li>Facebook Page: <a href="https://www.facebook.com/cesugentrias/">CESU General Trias FB Page</a></li>
                    </ul>
                </h5>
            </div>
            <div class="card-footer">
                <a href="{{route('vaxcert_walkin_file')}}" class="btn btn-secondary btn-block">Go Back</a>
            </div>
        </div>
        <p class="mt-3 text-center">©2021 - {{date('Y')}} Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite</p>
    </div>
</div>
@endsection