@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Success</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-success" role="alert">
                    {{ session('msg') }}
                </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('facility_report_index', request()->route('facility_code')) }}" class="btn btn-primary btn-block">Submit Another Data</a>
            </div>
        </div>
    </div>
@endsection