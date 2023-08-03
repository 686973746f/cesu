@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Main Menu</b></div>
        <div class="card-body">
            <a href="{{route('covid_home')}}" class="btn btn-block btn-primary">COVID-19</a>
            <a href="{{route('abtc_home')}}" class="btn btn-block btn-primary">Animal Bite (ABTC)</a>
            <a href="{{route('vaxcert_home')}}" class="btn btn-block btn-primary">VaxCert Concerns</a>
            <a href="{{route('syndromic_home')}}" class="btn btn-block btn-primary">Syndromic (Individual Treatment Records - ITR)</a>
            <hr>
            <a href="{{route('pidsr.home')}}" class="btn btn-block btn-primary">PIDSR (Integrated)</a>
            <a href="{{route('fhsis_home')}}" class="btn btn-block btn-primary">eFHSIS (Integrated)</a>
        </div>
    </div>
</div>
@endsection