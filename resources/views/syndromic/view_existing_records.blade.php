@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">View Existing ITR Records (from Newest to Oldest)</div>
        <div class="card-body">
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Case ID</th>
                        <th>Case Date</th>
                        <th>Date Encoded / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $l)
                    <tr>
                        <td><a href="{{route('syndromic_viewRecord', $l->id)}}">{{$l->id}}</a></td>
                        <td>{{date('m/d/Y h:i A', strtotime($l->consultation_date))}}</td>
                        <td>{{date('m/d/Y h:i A', strtotime($l->created_at))}} - {{$l->user->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection