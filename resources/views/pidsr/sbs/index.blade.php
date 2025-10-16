@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>General Trias City CESU - School Based Disease Surveillance</b></div>
            <div class="card-body">
                <a href="{{route('sbs_new', $s->qr)}}" class="btn btn-lg btn-success btn-block">New Case</a>
                @if(!$s->qr)

                @else
                <a href="{{route('sbs_view', $s->qr)}}" class="btn btn-lg btn-primary btn-block">View List</a>
                @endif
            </div>
        </div>
    </div>
@endsection