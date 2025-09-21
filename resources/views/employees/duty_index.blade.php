@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of HERT Duties/Events</b> (Sorted by Newest to Oldest)</div>
                <div>
                    @if(auth()->user()->isGlobalAdmin())
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#options">Options</button>
                    @endif
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newDuty">New Event</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="alert alert-info" role="alert">
                <div class="text-center">
                    <h4>Total Responders: <b>{{$tot_emp_duty}}</b> (Male: {{$tot_emp_duty_male}}, Female: {{$tot_emp_duty_female}})</h4>
                    <h5><b>Cycle {{$cycle_count}}</b> (Already Deployed: {{$tot_emp_duty_alreadyassigned}} - Not Yet Deployed: {{$tot_emp_duty_notyetassigned}})</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="text-center">Team A (Total: {{$ta_total}})</h5>
                        <h6>Deployed: {{$ta_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$ta_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team B (Total: {{$tb_total}})</h5>
                        <h6>Deployed: {{$tb_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tb_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team C (Total: {{$tc_total}})</h5>
                        <h6>Deployed: {{$tc_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tc_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team D (Total: {{$td_total}})</h5>
                        <h6>Deployed: {{$td_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$td_notdeployed}}</h6>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Event Date</th>
                            <th>Cycle No.</th>
                            <th>Status</th>
                            <th>Created at / by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td class="text-center">{{$l->id}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$l->event_name}}</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="{{route('duty_view', $l->id)}}" class="dropdown-item">Responders</a>
                                        <a href="{{route('duty_viewpatients', $l->id)}}" class="dropdown-item">Patients</a>
                                    </div>
                                </div>
                                
                            </td>
                            <td class="text-center">{{($l->event_date) ? date('M. d, Y - D', strtotime($l->event_date)) : 'N/A'}}</td>
                            <td class="text-center">{{$l->cycle_number}}</td>
                            <td class="text-center">{{$l->status}}</td>
                            <td class="text-center">
                                <div>{{date('M. d, Y h:i A', strtotime($l->created_at))}}</div>
                                <div>by {{$l->user->name}}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

<form action="{{route('duty_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="newDuty" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Duty</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="event_name"><b class="text-danger">*</b>Event Name</label>
                      <input type="text" class="form-control" name="event_name" id="event_name" style="text-transform: uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" style="text-transform: uppercase">
                    </div>
                    <div class="form-group">
                        <label for="event_date">Date of Event (Leave blank if TBA)</label>
                        <input type="date" class="form-control" name="event_date" id="event_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@if(auth()->user()->isGlobalAdmin())
<div class="modal fade" id="options" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{route('duty_mainoptions')}}" method="POST">
                    @csrf
                    <button type="submit" name="submit" value="reset_cycle" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to Reset the Duty Cycle?')">Reset Cycle</button>
                </form>

                <form action="">
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection