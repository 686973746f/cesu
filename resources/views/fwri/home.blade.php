@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Fireworks-Related Injury (FWRI) - Home</b> (Total: {{$list->total()}})</div>
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reportMod">Report</button></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date Submitted</th>
                                <th>from Facility/Name of Reporter</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Date Reported</th>
                                <th>Nature of Injury</th>
                                <th>Injury Occurred at</th>
                                <th>Name of Firecracker</th>
                                <th>Date of Injury</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $d)
                            <tr>
                                <td class="text-center"><b>{{$list->total() - $ind}}</b></td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                <td class="text-center"><small>{{$d->hospital_name}}/{{$d->reported_by}}</small></td>
                                <td><b><a href="{{route('fwri_view', $d->id)}}">{{$d->getName()}}</a></b></td>
                                <td class="text-center">{{$d->getAge()}}/{{$d->sg()}}</td>
                                <td class="text-center"><small>{{$d->getCompleteAddress()}}</small></td>
                                <td class="text-center">{{$d->contact_number}}</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($d->report_date))}}</td>
                                <td class="text-center">{{$d->nature_injury}}</td>
                                <td class="text-center"><small>{{$d->getInjuryAddress()}}</small></td>
                                <td class="text-center">{{$d->firework_name}}</td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->injury_date))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="reportMod" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <a href="{{route('fwri_report')}}" class="btn btn-primary btn-block">Open Report Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection