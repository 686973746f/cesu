@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Duties</b> (Sorted by Newest to Oldest)</div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addEmployee">Add Employee</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th colspan="6">{{$d->event_name}}</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Sex</th>
                        <th>BLS Trained</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($current_list as $ind => $c)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td>{{$c->employee->getName()}}</td>
                        <td class="text-center">{{$c->employee->duty_team}}</td>
                        <td class="text-center">{{$c->employee->gender}}</td>
                        <td class="text-center">{{$c->employee->is_blstrained}}</td>
                        <td class="text-center"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                      <label for="employee_id"><b>*</b>Select Employee to Add</label>
                      <select class="form-control" name="employee_id" id="employee_id" required>
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
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#employee_id').select2({
        dropdownParent: $("#addEmployee"),
        theme: "bootstrap",
    });
</script>
@endsection