@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>CESU Gen. Trias - Fireworks Related Injury (FWRI) Online Reporting Tool</b></div>
            <div class="card-body">
                <div class="alert alert-success text-center" role="alert">
                    <div>The form was successfully submitted. Thank you for using the program.</div>
                    <div>For Hospitals, you may now also encode the patient in the <a href="https://oneiss.doh.gov.ph/login.php">ONEISS</a></div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{route('fwri_index', $code)}}" class="btn btn-link btn-block">Submit Another</a>
            </div>
        </div>
    </div>
@endsection