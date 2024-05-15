@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>List of Barangay Health Stations</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Assigned Personnel/Position</th>
                            <th>System Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">{{$d->id}}</td>
                            <td><a href="{{route('settings_bhs_view', $d->id)}}">{{$d->name}}</a></td>
                            <td class="text-center">{{$d->brgy->brgyName}}</td>
                            <td class="text-center">
                                <div>{{$d->assigned_personnel_name}}</div>
                                <div>{{$d->assigned_personnel_position}}</div>
                            </td>
                            <td class="text-center">{{$d->sys_code1}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#mainTbl').dataTable({
            theme: 'bootstrap',
            order: [[1, 'asc']],
        });
    </script>
@endsection