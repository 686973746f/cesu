@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Duties</b> (Sorted by Newest to Oldest)</div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newDuty">New Duty</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="text-center thead-light">
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $l)
                    <tr>
                        <td class="text-center">{{$l->id}}</td>
                        <td><a href="{{route('duty_view', $l->id)}}">{{$l->event_name}}</a></td>
                        <td class="text-center"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
@endsection