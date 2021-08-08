@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h5>Province of Cavite</h5>
                <h5>City of General Trias</h5>
                <h5>Office of the City Health Officer</h5>
                <hr>
                <h5 class="font-weight-bold">CESU Self-Report</h5>
            </div>
            <div class="card-body text-center">
                <p>Select Language</p>
                <p>Pumili ng Wika</p>
                <hr>
                <a name="" id="" class="btn btn-primary btn-block" href="selfreport/en/" role="button">English</a>
                <a name="" id="" class="btn btn-primary btn-block" href="selfreport/fil/" role="button">Filipino / Tag-lish</a>
            </div>
        </div>
    </div>
@endsection