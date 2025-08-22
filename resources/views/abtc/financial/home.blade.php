@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>For Uploading</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>Record ID</th>
                            <th>Name</th>
                            <th>Facility</th>
                            <th>Date Admitted</th>
                            <th>Date Discharged</th>
                            <th>Transmittal Days</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">#{{$d->id}}</td>
                            <td>{{$d->patient->getName()}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><a href="{{route('abtc_financial_claimticket', ['ticket_id' => $d->id])}}">Select Ticket</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection