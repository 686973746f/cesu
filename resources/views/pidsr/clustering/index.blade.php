@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Clustering View</b></div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Created at</th>
                            <th>Morbidity Week</th>
                            <th>Barangay</th>
                            <th>Purok/Subdivision</th>
                            <th>Total Cases</th>
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
                            </td>
                            <td>
                                <a href="{{route('dengue_clustering_edit', $d->id)}}" class="text-dark"><b>{{$d->purok_subdivision}}</b></a>
                                @if($d->getTotalPatients() != 0)
                                <ul>
                                    @foreach($d->fetchClusteringList() as $ind => $cl)
                                    <li>#{{$ind+1}}.) {{$cl->Streetpurok}} ({{$cl->getName()}})</li>
                                    @endforeach
                                </ul>
                                @endif
                            </td>
                            <td class="text-center">{{$d->getTotalPatients()}}</td>
                            <td class="text-center">{{$d->assigned_team ?: 'N/A'}}</td>
                            <td class="text-center">{{$d->getStatus()}}</td>
                            <td class="text-center">{{$d->getUpcomingCycleDate()}}</td>
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
        dom: 'Qbftrip',
    });
</script>
@endsection