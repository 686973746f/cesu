@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header text-center">
                <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 10rem;" class="mb-3">
                <img src="{{asset('assets/images/gentri_icon_large.png')}}" style="width: 10rem;" class="mb-3">
                <hr>
                <h5>Province of Cavite</h5>
                <h5>City of General Trias</h5>
                <h5>City Health Office</h5>
                <h5>City Epidemiology and Surveillance Unit</h5>
                <hr>
                <h5 class="font-weight-bold">COVID-19 Self-Reporting System</h5>
            </div>
            <div class="card-body text-center">
                <p>Select Language</p>
                <p>Pumili ng Wika</p>
                <hr>
                <a name="" id="" class="btn btn-primary btn-block" href="{{route('selfreport.index', ['locale' => 'en'])}}" role="button"><span class="flag-icon flag-icon-gb mr-2"></span>English</a>
                <a name="" id="" class="btn btn-primary btn-block" href="{{route('selfreport.index', ['locale' => 'fil'])}}" role="button"><span class="flag-icon flag-icon-ph mr-2"></span>Filipino / Tag-lish</a>
            </div>
        </div>
    </div>
@endsection