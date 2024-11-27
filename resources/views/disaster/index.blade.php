@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>GenTrias SECURE Tool (<span class="text-success">S</span>ystem for <span class="text-success">E</span>vacuation <span class="text-success">C</span>enter encoding, <span class="text-success">U</span>tilization, and <span class="text-success">R</span>eporting <span class="text-success">E</span>fficiency)</b></div>
                    <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#newDisaster">New Disaster</button></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Disaster Name</th>
                            <th>City/Municipality</th>
                            <th>Date Start/End</th>
                            <th>Status</th>
                            <th>Created At/By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td class="text-center">{{$l->id}}</td>
                            <td><a href="{{route('gtsecure_disaster_view', $l->id)}}">{{$l->name}}</a></td>
                            <td class="text-center">{{$l->city->name}}</td>
                            <td class="text-center">
                                <div>Started: {{date('M. d, Y', strtotime($l->date_start))}}</div>
                                @if($l->date_end)
                                <div>Started: {{date('M. d, Y', strtotime($l->date_end))}}</div>
                                @endif
                            </td>
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
        </div>
    </div>
    
    <form action="{{route('gtsecure_storeDisaster')}}" method="POST">
        @csrf
        <div class="modal fade" id="newDisaster" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Disaster</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="name"><b class="text-danger">*</b>Name</label>
                          <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" required>
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