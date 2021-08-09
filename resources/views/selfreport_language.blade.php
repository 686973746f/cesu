@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
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
                <a name="" id="" class="btn btn-primary btn-block" href="{{route('selfreport.index', ['locale' => 'en'])}}" role="button">English</a>
                <a name="" id="" class="btn btn-primary btn-block" href="{{route('selfreport.index', ['locale' => 'fil'])}}" role="button">Filipino / Tag-lish</a>
            </div>
        </div>
    </div>
@endsection