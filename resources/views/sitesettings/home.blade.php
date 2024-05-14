@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Settings</b></div>
        <div class="card-body">
            <a href="{{route('settings_general_view')}}" class="btn btn-primary btn-block">General</a>
            <hr>
            <a href="" class="btn btn-primary btn-block">Barangay Health Stations</a>
            <a href="{{route('subdivision_index')}}" class="btn btn-primary btn-block">Subdivisions</a>
        </div>
    </div>
</div>
@endsection