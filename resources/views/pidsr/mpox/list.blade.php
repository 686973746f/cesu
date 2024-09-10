@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Monkeypox</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection