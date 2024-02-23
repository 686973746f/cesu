@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Viewing Previous Consulation/s of <b>{{$d->getName()}} (#{{$d->id}})</b> (from Newest to Oldest)</div>
        <div class="card-body">
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Case ID</th>
                        <th>Facility</th>
                        <th>Date of Consultation</th>
                        <th>Chief Complain</th>
                        <th>Diagnosis</th>
                        <th>Attending Physician</th>
                        <th>Date Encoded / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $l)
                    <tr>
                        <td><a href="{{route('syndromic_viewRecord', $l->id)}}">{{$l->id}}</a></td>
                        <td>{{$l->facility->facility_name}}</td>
                        <td>{{date('m/d/Y h:i A', strtotime($l->consultation_date))}}</td>
                        <td>{{$l->chief_complain}}</td>
                        <td>{{$l->dcnote_assessment}}</td>
                        <td>{{$l->name_of_physician}}</td>
                        <td>
                            <h6>{{date('m/d/Y h:i A', strtotime($l->created_at))}}</h6>
                            <h6>{{$l->user->name}}</h6>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection