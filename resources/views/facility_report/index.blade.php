@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>CESU General Trias - Facility Reporting Tool</b></div>
            <div class="card-body">
                <a href="{{route('fwri_index', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">Report Fireworks-Related Injury</a>
                <a href="{{route('facility_report_injury_index', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">Report Vehicular Accident and other Injuries</a>
                <hr>
                <a href="{{route('edcs_facility_weeklysubmission_view', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">EDCS-IS Weekly Submission</a>
            </div>
        </div>
    </div>
@endsection