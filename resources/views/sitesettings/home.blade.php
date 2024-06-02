@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Settings</b></div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <a href="{{route('settings_general_view')}}" class="btn btn-primary btn-block">General</a>
            <hr>
            <a href="{{route('settings_bhs')}}" class="btn btn-primary btn-block">Barangay Health Stations</a>
            <a href="{{route('subdivision_index')}}" class="btn btn-primary btn-block">Subdivisions</a>
            <hr>
            <a href="{{route('covid_home')}}" class="btn btn-block btn-primary btn-lg">View Tasks</a>
        </div>
    </div>
</div>
@endsection