@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">Reports</div>
        <div class="card-body">
            <a href="{{route('report.daily')}}" class="btn btn-primary btn-block">Daily Report</a>
            <a href="" class="btn btn-primary btn-block">Barangay Report</a>
            <a href="" class="btn btn-primary btn-block">Company Report</a>
            <hr>
            <a href="{{route('report.situational.index')}}" class="btn btn-primary btn-block">COVID-19 Situational Report</a>
            <a href="{{route('report.situationalv2.index')}}" class="btn btn-primary btn-block">COVID-19 Situational Report V2</a>
            <hr>
            <a href="{{route('report.DOHExportAll')}}" class="btn btn-primary btn-block">DOH Export All to Excel</a>
        </div>
    </div>
</div>
@endsection