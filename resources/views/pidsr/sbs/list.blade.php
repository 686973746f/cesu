@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>General Trias City CESU - School Based Disease Surveillance: Home Page ({{$s->name}})</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgType')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                
            </div>
        </div>
    </div>
@endsection