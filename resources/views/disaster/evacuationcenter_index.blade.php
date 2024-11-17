@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Evacuation Center</b></div>
                    <div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#newEvacuationCenter">Settings</button>
                        <a href="{{route('gtsecure_newpatient', $d->id)}}" class="btn btn-success">New Patient</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection