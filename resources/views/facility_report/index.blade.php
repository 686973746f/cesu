@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>Welcome, {{$d->facility_name}}</div>
                <div><b>CESU General Trias - Facility Reporting Tool</b></div>
            </div>
            <div class="card-body">
                <div class="alert alert-info text-center" role="alert">
                    <b class="text-danger">WARNING:</b> Please don't share your unique link to unauthorized personnel.
                </div>
                <a href="{{route('facility_report_injury_index', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">Injury Reporting Tool</a>
                <a href="{{route('facility_report_case_checker', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">EDCS-IS Viewer</a>
                <hr>
                <a href="{{route('edcs_facility_weeklysubmission_view', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">EDCS-IS Weekly Submission</a>
            </div>
        </div>
    </div>
    
    @if(session('openEncodeModal'))
    <script>
        $(document).ready(function(){
            $('#addCase').modal('show');
        });
    </script>
    @endif
@endsection