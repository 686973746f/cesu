@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Fireworks-Related Injury (FWRI) - Home</b></div>
                    <div><a href="" class="btn btn-primary">Report</a></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date Submitted</th>
                                <th>from Facility</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Date Reported</th>
                                <th>Nature of Injury</th>
                                <th>Injury Occurred at</th>
                                <th>Date of Injury</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $d)
                            <tr>
                                <td class="text-center"><b>{{$list->lastItem() + $ind}}</b></td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                <td class="text-center">{{$d->hospital_name}}</td>
                                <td><b><a href="{{route('fwri_view', $d->id)}}">{{$d->getName()}}</a></b></td>
                                <td class="text-center">{{$d->getAge()}}/{{$d->sg()}}</td>
                                <td class="text-center"><small>{{$d->getCompleteAddress()}}</small></td>
                                <td class="text-center">{{$d->contact_number}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($d->report_date))}}</td>
                                <td class="text-center">{{$d->nature_injury}}</td>
                                <td class="text-center"><small>{{$d->getInjuryAddress()}}</small></td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->injury_date))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection