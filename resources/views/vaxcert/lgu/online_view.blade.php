@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                    <h4 class="mb-5"><b>Medical Certificate Verification</b></h4>
                    <h6>Beware of fake verification sites. The legitimate site should have this domain name <span class="text-success"><b>https://cesugentri.com/vaxcert_lgu/verify/</b></span></h6>
                </div>
                <hr>
                @if($d)
                
                @else
                <div class="text-center">
                    <h3 class="text-danger">INVALID QR CODE</h3>
                    <p>Sorry, your QR Code is invalid.</p>
                </div>
                @endif
            </div>
        </div>
        <div class="mt-3 text-center">
            <code class=" text-muted">CHO General Trias Online Verification Tool. Developed and Maintained by Christian James Historillo.</code>
        </div>
    </div>
@endsection