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
                    <div class="card-header"><b>Work Tickets</b></div>
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
                                            <button type="submit" class="btn btn-primary">Grab Ticket</button>
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
                    <div class="card-header"><b>OPD to iClinicSys Tickets</b></div>
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
                                        <button type="submit" class="btn btn-primary">Grab Ticket</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header"><b>ABTC to iClinicSys Tickets</b></div>
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
                                    <td class="text-center">
                                        <form action="{{route('task_grab', [
                                            'ticket_id' => $d->id,
                                            'type' => 'abtc',
                                        ])}}" method="POST">
                                            @csrf
                                        <button type="submit" class="btn btn-primary">Grab Ticket</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection