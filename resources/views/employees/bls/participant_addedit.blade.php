@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>{{$d->batch_name}}</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParticipant">Add Participant</button>
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