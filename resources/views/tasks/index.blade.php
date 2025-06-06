@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <div><h5><b>Pending Tasks List</b></h5></div>
            <div>
                <a href="{{route('mytask_index')}}" class="btn btn-primary">My Tickets</a>
                @if(auth()->user()->canAccessTask())
                <a href="{{route('taskgenerator.index')}}" class="btn text-white" style="background-color: orange;">Task Generator</a>
                @endif
            </div>
        </nav>
        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="card">
                    <div class="card-header bg-success text-white"><b>Work Tickets</b>  - Total: {{$open_worklist->total()}}</div>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($open_worklist as $d)
                                    <tr>
                                        <td class="text-center">
                                            <div>#{{$d->id}}</div>
                                            <div><small>{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}}</small></div>
                                        </td>
                                        <td>{{$d->name}}</td>
                                        <td class="text-center">{{date('m/d/Y (D) h:i A', strtotime($d->until))}}</td>
                                        <td class="text-center">
                                            <form action="{{route('task_grab', [
                                                'ticket_id' => $d->id,
                                                'type' => 'work',
                                            ])}}" method="POST">
                                                @csrf
                                            <button type="submit" class="btn btn-primary">Grab Work Ticket</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>OPD to iClinicSys Tickets</b> - Total: {{$open_opdlist->total()}}</div>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($open_opdlist as $d)
                                <tr>
                                    <td class="text-center">
                                        <div>#{{$d->id}}</div>
                                        <div><small>{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}}</small></div>
                                    </td>
                                    <td>{{$d->syndromic_patient->getName()}}</td>
                                    <td class="text-center">{{$d->syndromic_patient->getAge()}}/{{$d->syndromic_patient->sg()}}</td>
                                    <td class="text-center">
                                        <form action="{{route('task_grab', [
                                            'ticket_id' => $d->id,
                                            'type' => 'opd',
                                        ])}}" method="POST">
                                            @csrf
                                        <button type="submit" class="btn btn-primary">Grab OPD Ticket</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>ABTC to iClinicSys Tickets</b> - Total: {{$open_abtclist->count()}}</div>
                    <div class="card-body">
                        @if($open_abtclist->count() != 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="abtcTable">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>
                                            <div>Ticket ID /</div>
                                            <div>Date Created</div>
                                        </th>
                                        <th>Name</th>
                                        <th>Age/Sex</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($open_abtclist as $d)
                                    <tr>
                                        <td class="text-center">
                                            <div>#{{$d->id}}</div>
                                            <div><small>{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}}</small></div>
                                        </td>
                                        <td>{{$d->patient->getName()}}</td>
                                        <td class="text-center">{{$d->patient->getAge()}}/{{$d->patient->sg()}}</td>
                                        <td class="text-center">{{$d->category_level}}</td>
                                        <td class="text-center">
                                            <form action="{{route('task_grab', [
                                                'ticket_id' => $d->id,
                                                'type' => 'abtc',
                                            ])}}" method="POST">
                                                @csrf
                                            <button type="submit" class="btn btn-primary">Grab ABTC Ticket</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-center">No open tickets yet. Come back again later.</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-success text-white"><b>ABTC Category 3 To Philhealth Claims</b> - Total: {{$abtc_claimslist->count()}}</div>
                    <div class="card-body">
                        @if($abtc_claimslist->count() != 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="abtcTable2">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>
                                            <div>Ticket ID /</div>
                                            <div>Date Created</div>
                                        </th>
                                        <th>Name</th>
                                        <th>Age/Sex</th>
                                        <th>Category</th>
                                        <th>Facility</th>
                                        <th>Date Admitted</th>
                                        <th>Date Discharged</th>
                                        <th>Transmittal Days</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($abtc_claimslist as $d)
                                    @if(Carbon\Carbon::parse($d->d7_date)->diffInDays() <= 61)
                                    <tr>
                                        <td class="text-center">
                                            <div>#{{$d->id}}</div>
                                            <div><small>{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}}</small></div>
                                        </td>
                                        <td>{{$d->patient->getName()}}</td>
                                        <td class="text-center">{{$d->patient->getAge()}}/{{$d->patient->sg()}}</td>
                                        <td class="text-center">{{$d->category_level}}</td>
                                        <td class="text-center">{{$d->vaccinationsite->site_name}}</td>
                                        <td class="text-center">{{date('m/d/Y', strtotime($d->d0_date))}}</td>
                                        <td class="text-center">{{date('m/d/Y', strtotime($d->d7_date))}}</td>
                                        <td class="text-center">{{Carbon\Carbon::parse($d->d7_date)->diffInDays()}}</td>
                                        <td class="text-center">
                                            <form action="{{route('task_grab', [
                                                'ticket_id' => $d->id,
                                                'type' => 'abtc',
                                            ])}}" method="POST">
                                                @csrf
                                            <button type="submit" class="btn btn-primary">Grab ABTC Ticket</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-center">No open tickets yet. Come back again later.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#abtcTable').dataTable();

        $('#abtcTable2').dataTable();
    </script>
@endsection