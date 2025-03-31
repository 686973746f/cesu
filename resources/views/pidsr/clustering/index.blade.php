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

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createSchedule">Create Custom Schedule</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="alert alert-primary" role="alert">
                <h4><b>Summary:</b></h4>
                <hr>
                <h5>Completed Cycle: {{$completed_cycle}}</h5>
            </div>
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

<form action="{{route('dengue_store_customschedule')}}" method="POST">
    @csrf
    <div class="modal fade" id="createSchedule" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Create Custom Schedule</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type"><b class="text-danger">*</b>Schedule Type</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="" disabled {{(is_null(old('type'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="AUTO" {{((old('type')) == 'AUTO') ? 'selected' : ''}}>Normal Schedule (4 Cycles)</option>
                            <option value="REQUEST_1CYCLE" {{((old('type')) == 'REQUEST_1CYCLE') ? 'selected' : ''}}>Request (1 Cycle Only)</option>
                            <option value="REQUEST_4CYCLE" {{((old('type')) == 'REQUEST_4CYCLE') ? 'selected' : ''}}>Request (4 Cycles)</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purok_subdivision"><b class="text-danger">*</b>Area/Subdivision Name</label>
                                <input type="text" class="form-control" name="purok_subdivision" id="purok_subdivision" value="{{old('purok_subdivision')}}" style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brgy_id"><b class="text-danger">*</b>Barangay</label>
                                <select class="form-control" name="brgy_id" id="brgy_id" required>
                                    @foreach($brgy_list as $b)
                                    <option value="{{$b->id}}" {{($b->id == old('brgy_id')) ? 'selected' : ''}}>{{$b->alt_name ?: $b->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_team"><b class="text-danger">*</b>Responsible Team</label>
                                <select class="form-control" name="assigned_team" id="assigned_team" required>
                                    <option value="" disabled {{(is_null(old('assigned_team'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="CHO" {{((old('assigned_team')) == 'CHO') ? 'selected' : ''}}>CHO</option>
                                    <option value="CENRO" {{((old('assigned_team')) == 'CENRO') ? 'selected' : ''}}>CENRO</option>
                                    <option value="GSO" {{((old('assigned_team')) == 'GSO') ? 'selected' : ''}}>GSO</option>
                                    <option value="DOH REGIONAL" {{((old('assigned_team')) == 'DOH REGIONAL') ? 'selected' : ''}}>DOH REGIONAL</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status"><b class="text-danger">*</b>Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="PENDING" {{(old('status') == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                    <option value="CYCLE1" {{(old('status') == 'CYCLE1') ? 'selected' : ''}}>1ST CYCLE DONE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cycle1_date"><b class="text-danger">*</b>1st Cycle Date</label>
                        <input type="datetime-local" class="form-control" name="cycle1_date" id="cycle1_date" value="{{old('cycle1_date')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#mainTbl').dataTable({
        iDisplayLength: -1,
        fixedHeader: true,
        order: [[7, 'asc']],
        dom: 'Qbftrip',
    });
</script>
@endsection