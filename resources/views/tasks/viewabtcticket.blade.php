@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>View ABTC to iClinicSys Ticket #{{$d->id}}</b></h5>
        </nav>
        <form action="{{route('abtctask_close', $d->id)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Mark as Done</button>
                </div>
            </div>
            
        </form>
    </div>
@endsection