@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Clustering View</b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Created at</th>
                            <th>Morbidity Week</th>
                            <th>Barangay</th>
                            <th>Purok/Subdivision</th>
                            <th>Total Patients</th>
                            <th>Responsible Team</th>
                            <th>Status</th>
                            <th>Schedule Date/Cycle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                            <td class="text-center">{{$d->morbidity_week}}</td>
                            <td>
                                <div><b>{{$d->brgy->name}}</b></div>
                                <hr>
                                <ul>
                                    @foreach($d->fetchClusteringList() as $cl)
                                    <div></div>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{$d->purok_subdivision}}</td>
                            <td class="text-center">{{$d->getTotalPatients()}}</td>
                            <td class="text-center">{{$d->assigned_team ?: 'N/A'}}</td>
                            <td class="text-center">{{$d->status}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection