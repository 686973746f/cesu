@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Settings</b></div>
        <div class="card-body">
            <a href="{{route('settings_general_view')}}" class="btn btn-primary btn-block">General</a>
        </div>
    </div>
</div>
@endsection