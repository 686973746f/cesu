@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header text-center">
                <h5>Province of Cavite</h5>
                <h5>City of General Trias</h5>
                <h5>City Health Office</h5>
                <h5>City Epidemiology and Surveillance Unit</h5>
                <hr>
                <h5 class="font-weight-bold">COVID-19 Swab Scheduling System (Pa-swab)</h5>
            </div>
            <div class="card-body text-center">
                <p>Select Language</p>
                <p>Pumili ng Wika</p>
                <hr>
                <a class="btn btn-primary btn-block" href="{{route('paswab.index', ['locale' => 'en'])}}?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button">English</a>
                <a class="btn btn-primary btn-block" href="{{route('paswab.index', ['locale' => 'fil'])}}?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button">Filipino / Tag-lish</a>
            </div>
        </div>
    </div>
@endsection