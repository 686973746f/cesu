@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Employees</b></div>
                <div><a href="{{route('employees_add')}}" class="btn btn-success">Add</a></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="mainTbl">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Sub-Office</th>
                        <th>BLS Trained</th>
                        <th>Type of Responder</th>
                        <th>Deployable in Duties</th>
                        <th>HERO Trained</th>
                        <th>HERT Team</th>
                        <th>Duty Cycle Status</th>
                        <th>Duty Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $d)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td><a href="{{route('employees_edit', $d->id)}}"><b>{{$d->getFullName()}}</b></a></td>
                        <td class="text-center">{{$d->gender}}</td>
                        <td class="text-center">{{$d->employment_status}}</td>
                        <td class="text-center">{{$d->type}}</td>
                        <td class="text-center">{{$d->job_position}}</td>
                        <td class="text-center">{{$d->office}}</td>
                        <td class="text-center">{{$d->sub_office}}</td>
                        <td class="text-center">{{$d->is_blstrained}}</td>
                        <td class="text-center">{{$d->bls_typeofrescuer}}</td>
                        <td class="text-center">{{$d->duty_canbedeployed}}</td>
                        <td class="text-center">{{$d->is_herotrained}}</td>
                        <td class="text-center">{{$d->duty_team}}</td>
                        <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                        <td class="text-center">{{$d->duty_balance}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#mainTbl').dataTable({
        dom: 'QBfritp',
        buttons: [
            {
                extend: 'excel',
                title: '',
            },
            'copy',
        ],
    });
</script>
@endsection