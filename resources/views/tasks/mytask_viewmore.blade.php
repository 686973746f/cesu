@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>View More Task</b></div>
        <div class="card-body">
            @if($c == 'ABTC_CAT3' && $list->count() != 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mainTbl">
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
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
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
                                <b><span class="badge badge-{{$color}} p-2">{{$d->ics_ticketstatus}}</span></b>
                            </td>
                            <div class="text-center">
                                <a href="{{route('abtctask_view', $d->id)}}" class="btn btn-primary">View</a>
                            </div>
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
</div>

<script>
$('#mainTbl').dataTable();
</script>
@endsection