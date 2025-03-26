@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Dengue Clustering View</b></div>
                <div>
                    <a href="{{route('dengue_clustering_calendar')}}" class="btn btn-primary">Calendar</a>
                    @if(request()->input('showNonClustering'))
                    <a href="{{route('dengue_clustering_viewer')}}" class="btn btn-warning">Show Clustering Cases Only</a>
                    @else
                    <a href="{{route('dengue_clustering_viewer')}}?showNonClustering=1" class="btn btn-primary">Show Non-Clustering Cases</a>
                    @endif
                    
                </div>
            </div>
        </div>
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
                        @php
                            $allow = true;
                        @endphp
                        @if($allow)
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
                                    <li>#{{$ind+1}}.) {{$cl->Streetpurok}} (<a href="{{route('pidsr_casechecker_edit', ['DENGUE', $cl->EPIID])}}" class="text-dark">{{$cl->getName()}}</a>)</li>
                                    @endforeach
                                </ul>
                                @endif
                            </td>
                            <td class="text-center">{{$d->getTotalPatients()}}</td>
                            <td class="text-center">{{$d->assigned_team ?: 'N/A'}}</td>
                            <td class="text-center">{{$d->getStatus()}}</td>
                            <td class="text-center">
                                @if($d->getUpcomingCycleDate() != 'N/A' && $d->getUpcomingCycleDate() != '3RD CYCLE DONE')
                                <div>{{Carbon\Carbon::parse($d->getUpcomingCycleDate())->format('m/d/Y (D)')}}</div>
                                <div>{{Carbon\Carbon::parse($d->getUpcomingCycleDate())->format('H:i / h:i A')}}</div>
                                <div>{{$d->getUpcomingCycle()}}</div>
                                @else
                                <div>{{$d->getUpcomingCycleDate()}}</div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $('#mainTbl').dataTable({
        iDisplayLength: -1,
        fixedHeader: true,
        order: [[7, 'asc']],
        dom: 'Qbftrip',
    });
</script>
@endsection