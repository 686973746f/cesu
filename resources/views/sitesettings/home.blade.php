@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Settings</b></div>
        <div class="card-body">
            <a href="{{route('settings_general_view')}}" class="btn btn-primary btn-block">General</a>
            <a href="{{route('subdivision_index')}}" class="btn btn-primary btn-block">Subdivisions</a>
            <hr>
            <a href="{{route('encoder_stats_index')}}" class="btn btn-primary btn-block">Encoder Daily Status</a>
        </div>
    </div>
</div>
@endsection