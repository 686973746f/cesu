@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">Reports</div>
        <div class="card-body">
            <a href="{{route('report.daily')}}" class="btn btn-primary btn-block">Daily Report</a>
            <a href="" class="btn btn-primary btn-block">Barangay Report</a>
            <a href="" class="btn btn-primary btn-block">Company Report</a>
        </div>
    </div>
</div>
@endsection