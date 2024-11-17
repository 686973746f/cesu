@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>GenTrias SECURE Tool (System for Evacuation Center Encoding, Utilization, and Reporting Efficiency)</b></div>
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
                            <th>ID</th>
                            <th>Disaster Name</th>
                            <th>Created at/by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td class="text-center">{{$l->id}}</td>
                            <td>{{$l->name}}</td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <a href="{{route('gtsecure_disaster_view', $l->id)}}" class="btn btn-primary">View</a>
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