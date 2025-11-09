@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Disaster Summary Report</b></div>
        <div class="card-body">
            <h6><b>Name of LGU:</b> CITY GOVERNMENT OF GENERAL TRIAS</h6>
            <h6><b>Name of Incident:</b> {{$d->name}}</h6>
            <h6><b>Date & Time:</b> {{date('M. d, Y h:i A')}}</h6>
            <hr>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Evacuation Center(s)</th>
                        <th>No. of Families</th>
                        <th>No. of Individuals</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_evac as $e)
                    <tr>
                        <td>{{$e->name}}</td>
                        <td class="text-center">{{$e->familiesinside->count()}}</td>
                        <td class="text-center">{{$e->getTotalIndividualsAttribute()}}</td>
                        <td class="text-center">{{$e->countIndividualsByGender('M')}}</td>
                        <td class="text-center">{{$e->countIndividualsByGender('F')}}</td>
                        <td class="text-center"><b>{{($e->countIndividualsByGender('M') + $e->countIndividualsByGender('F'))}}</b></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection