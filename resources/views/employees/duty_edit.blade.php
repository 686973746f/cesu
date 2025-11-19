@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Responders</b></div>
                <div>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#options">Options</button>
                    @if($d->status == 'OPEN')
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addEmployee">Add Responder</button>
                    @else
                    <button type="button" class="btn btn-success" disabled>Add Responder</button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif

            @if($d->status == 'CLOSED')
            <div class="alert alert-primary" role="alert">
                <h5>This Event was marked as <b class="text-danger">CLOSED</b></h5>
                <h6>Adding and Updating List of Responders is not allowed anymore.</h6>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dutyListTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th colspan="7">{{$d->event_name}} ({{($d->event_date) ? mb_strtoupper(date('M. d, Y', strtotime($d->event_date))) : ''}})</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Team</th>
                            <th>Gender</th>
                            <th>BLS Trained</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($current_list as $ind => $c)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>
                                <div><b>{{$c->employee->getName()}}</b></div>
                                @if(!is_null($c->standin_id))
                                <div>(Stand-in by: {{$c->standin->getName()}})</div>
                                @endif</td>
                            <td class="text-center">{{$c->employee->duty_team}}</td>
                            <td class="text-center">
                                @php
                                if($c->employee->gender == 'M') {
                                    $style = 'background-color: aqua';
                                }
                                else {
                                    $style = 'background-color: pink';
                                }
                                @endphp
                                <span class="badge p-2" style="{{$style}}">
                                    {{$c->employee->gender}}
                                </span>
                            </td>
                            <td class="text-center">{{$c->employee->is_blstrained}}</td>
                            <td>{{$c->remarks}}</td>
                            <td class="text-center">
                                @if($d->status == 'OPEN')
                                <form action="{{route('duty_removeemployee', [$d->id, $c->id])}}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">X</button>
                                </form>
                                @else
                                <button type="button" class="btn btn-danger" disabled>X</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{route('duty_update', $d->id)}}" method="POST">
    @csrf
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
                    <div class="form-group">
                        <label for="event_name"><b class="text-danger">*</b>Event Name</label>
                        <input type="text" class="form-control" name="event_name" id="event_name" value="{{old('event_name', $d->event_name)}}" style="text-transform: uppercase" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}" style="text-transform: uppercase">
                    </div>
                    <div class="form-group">
                        <label for="event_date">Date of Event (Leave blank if TBA)</label>
                        <input type="date" class="form-control" name="event_date" id="event_date" value="{{old('event_date', $d->event_date)}}">
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="status"><b class="text-danger">*</b>Set Status</label>
                      <select class="form-control" name="status" id="status" required>
                        @if($d->status == 'OPEN')
                        <option value="OPEN" {{(old('status', $d->status) == 'OPEN') ? 'selected' : ''}}>Open (List of Responders can still be updated)</option>
                        <option value="PENDING" {{(old('status', $d->status) == 'PENDING') ? 'selected' : ''}}>Pending (Event is Ongoing and list of Responders are final)</option>
                        <option value="CLOSED" {{(old('status', $d->status) == 'CLOSED') ? 'selected' : ''}}>Closed (Event is over)</option>
                        @elseif($d->status == 'PENDING')
                        <option value="PENDING" {{(old('status', $d->status) == 'PENDING') ? 'selected' : ''}}>Pending (Event is Ongoing and list of Responders are final)</option>
                        @elseif($d->status == 'CLOSED')
                        <option value="CLOSED" {{(old('status', $d->status) == 'CLOSED') ? 'selected' : ''}}>Closed (Event is over)</option>
                        @endif
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('duty_storeemployee', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="employee_id"><b class="text-danger">*</b>Select Employee to Add</label>
                      <select class="form-control" name="employee_id" id="employee_id" required>
                        <option value="" {{(is_null(old('employee_id'))) ? 'disabled' : ''}} selected>Choose...</option>
                        <optgroup label="Team A">
                            @foreach($teama_list as $p)
                            @if(!$p->ifAlreadyInEvent($d->id))
                            <option value="{{$p->id}}">{{$p->getName()}}</option>
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Team B">
                            @foreach($teamb_list as $p)
                            @if(!$p->ifAlreadyInEvent($d->id))
                            <option value="{{$p->id}}">{{$p->getName()}}</option>
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Team C">
                            @foreach($teamc_list as $p)
                            @if(!$p->ifAlreadyInEvent($d->id))
                            <option value="{{$p->id}}">{{$p->getName()}}</option>
                            @endif
                            @endforeach
                        </optgroup>
                        <optgroup label="Team D">
                            @foreach($teamd_list as $p)
                            @if(!$p->ifAlreadyInEvent($d->id))
                            <option value="{{$p->id}}">{{$p->getName()}}</option>
                            @endif
                            @endforeach
                        </optgroup>
                      </select>
                    </div>
                    <hr>
                    <div class="form-check mb-3">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="standin_checkbox" id="standin_checkbox" value="1">Other Employee will stand-in for the responder?</label>
                    </div>
                    <div id="standin_div" class="d-none">
                        <div class="form-group">
                          <label for="standin_id">Select Employee to Stand-in</label>
                          <select class="form-control" name="standin_id" id="standin_id">
                            <option value="" {{(is_null(old('standin_id'))) ? 'disabled' : ''}} selected>Choose...</option>
                            @foreach($standin_list as $s)
                            <option value="{{$s->id}}">{{$s->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                    </div>
                    @if(request()->input('override'))
                    <small><b>Override Mode Enabled.</b> Kasama sa listahan lahat kahit ang mga nakapag-duty na sa current cycle. Upang i-reset sa default view, <a href="{{route('duty_view', $d->id)}}" class="text-danger"><b>Pindutin ito</b></a></small>
                    @else
                    <small>Ang mga nasa listahan ay ang mga responders na <b>hindi pa na-duty sa current cycle</b>. Kung may nais ulit dumuty kahit tapos na, <a href="{{route('duty_view', $d->id)}}?override=1" class="text-success"><b>Pindutin ito</b></a></small>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#employee_id, #standin_id').select2({
        dropdownParent: $("#addEmployee"),
        theme: "bootstrap",
    });

    $('#dutyListTbl').dataTable({
        iDisplayLength: -1,
        dom: 'Bfrit',
        buttons: [
            {
                extend: 'excel',
                title: '',
            },
            'copy',
        ],
    });

    $('#standin_checkbox').on('change', function () {
        if ($(this).is(':checked')) {
            // do something when checked
            $('#standin_div').removeClass('d-none');
            $('#standin_id').prop('required', true);
        } else {
            // do something when unchecked
            $('#standin_div').addClass('d-none');
            $('#standin_id').prop('required', false);
        }
    });
</script>

@endsection