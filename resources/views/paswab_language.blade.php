@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header text-center">
                <h5>Province of Cavite</h5>
                <h5>City of General Trias</h5>
                <h5>Office of the City Health Officer</h5>
                <hr>
                <h5 class="font-weight-bold">CESU Schedule For Swab (Pa-swab)</h5>
            </div>
            <div class="card-body text-center">
                <p>Select Language</p>
                <p>Pumili ng Wika</p>
                <hr>
                <a class="btn btn-primary btn-block" href="{{route('paswab.index')}}/en?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button">English</a>
                <a class="btn btn-primary btn-block" href="{{route('paswab.index')}}/fil?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button">Filipino / Tag-lish</a>
            </div>
        </div>
    </div>
@endsection