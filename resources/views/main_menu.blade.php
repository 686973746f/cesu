@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Main Menu</b></div>
        <div class="card-body">
            @if(auth()->user()->canAccessCovid())
            <a href="{{route('covid_home')}}" class="btn btn-block btn-primary">COVID-19</a>
            @endif
            @if(auth()->user()->canAccessAbtc())
            <a href="{{route('abtc_home')}}" class="btn btn-block btn-primary">Animal Bite (ABTC)</a>
            @endif
            @if(auth()->user()->canAccessVaxcert())
            <a href="{{route('vaxcert_home')}}" class="btn btn-block btn-primary">VaxCert Concerns</a>
            @endif
            @if(auth()->user()->canAccessSyndromic())
            <a href="{{route('syndromic_home')}}" class="btn btn-block btn-primary">Syndromic (Individual Treatment Records - ITR)</a>
            @endif
            <hr>
            @if(auth()->user()->canAccessPidsr())
            <a href="{{route('pidsr.home')}}" class="btn btn-block btn-primary">PIDSR (Integrated)</a>
            @endif
            @if(auth()->user()->canAccessFhsis())
            <a href="{{route('fhsis_home')}}" class="btn btn-block btn-primary">eFHSIS (Integrated)</a>
            @endif
        </div>
    </div>
</div>
@endsection