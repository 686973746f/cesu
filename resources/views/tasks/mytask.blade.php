@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>My Tasks</b></h5>
        </nav>
        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="card">
                    <div class="card-header bg-success text-white"><b>Work Tickets</b></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>
                                            <div>Task ID /</div>
                                            <div>Date Added</div>
                                        </th>
                                        <th>Task Name</th>
                                        <th>Finish Until</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grabbed_worklist as $d)
                                    @php
                                    $status = $d->status;

                                    if($status == 'OPEN') {
                                        $color = 'secondary';
                                    }
                                    else if($status == 'PENDING') {
                                        $color = 'warning';
                                    }
                                    else if($status == 'FINISHED') {
                                        $color = 'success';
                                    }
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <div>#{{$d->id}}</div>
                                            <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                                        </td>
                                        <td>{{$d->name}}</td>
                                        <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                        <td class="text-center">
                                            <b><span class="badge badge-{{$color}} p-2">{{$d->status}}</span></b>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('worktask_view', $d->id)}}" class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>OPD to iClinicSys Tickets</b></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>
                                        <div>Ticket ID /</div>
                                        <div>Date Created</div>
                                    </th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grabbed_opdlist as $d)
                                @php
                                $status = $d->ics_ticketstatus;

                                if($status == 'OPEN') {
                                    $color = 'secondary';
                                }
                                else if($status == 'PENDING') {
                                    $color = 'warning';
                                }
                                else if($status == 'FINISHED') {
                                    $color = 'success';
                                }
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div>#{{$d->id}}</div>
                                        <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                                    </td>
                                    <td>{{$d->syndromic_patient->getName()}}</td>
                                    <td class="text-center">{{$d->syndromic_patient->getAge()}}/{{$d->syndromic_patient->sg()}}</td>
                                    <td class="text-center">
                                        <b><span class="badge badge-{{$color}} p-2">{{$d->ics_ticketstatus}}</span></b>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('opdtask_view', $d->id)}}" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>ABTC to OPD Tickets</b></div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>
                                        <div>Ticket ID /</div>
                                        <div>Date Created</div>
                                    </th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grabbed_abtclist as $d)
                                @php
                                $status = $d->ics_ticketstatus;

                                if($status == 'OPEN') {
                                    $color = 'secondary';
                                }
                                else if($status == 'PENDING') {
                                    $color = 'warning';
                                }
                                else if($status == 'FINISHED') {
                                    $color = 'success';
                                }
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div>#{{$d->id}}</div>
                                        <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                                    </td>
                                    <td>{{$d->patient->getName()}}</td>
                                    <td class="text-center">{{$d->patient->getAge()}}/{{$d->patient->sg()}}</td>
                                    <td class="text-center">
                                        <b><span class="badge badge-{{$color}} p-2">{{$d->ics_ticketstatus}}</span></b>
                                    </td>
                                    <td class="text-center">
                                        
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>ABTC Category 3 to iClinicSys</b></div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>
                                        <div>Ticket ID /</div>
                                        <div>Date Created</div>
                                    </th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grabbed_abtclist as $d)
                                @php
                                $status = $d->ics_ticketstatus;

                                if($status == 'OPEN') {
                                    $color = 'secondary';
                                }
                                else if($status == 'PENDING') {
                                    $color = 'warning';
                                }
                                else if($status == 'FINISHED') {
                                    $color = 'success';
                                }
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div>#{{$d->id}}</div>
                                        <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                                    </td>
                                    <td>{{$d->patient->getName()}}</td>
                                    <td class="text-center">{{$d->patient->getAge()}}/{{$d->patient->sg()}}</td>
                                    <td class="text-center">
                                        <b><span class="badge badge-{{$color}} p-2">{{$d->ics_ticketstatus}}</span></b>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('abtctask_view', $d->id)}}" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{route('mytask_viewmore')}}?c=ABTC_CAT2">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection