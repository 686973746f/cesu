@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>QES</b></div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#newCase">New Case</button></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Case</th>
                        <th>Status</th>
                        <th>Date Encoded / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($main_list as $d)
                    <tr>
                        <td class="text-center">{{$d->id}}</td>
                        <td><b><a href="{{route('qes_view_main', $d->id)}}">{{$d->name}}</a></b></td>
                        <td class="text-center">{{$d->status}}</td>
                        <td class="text-center">
                            <div>{{date('m/d/Y H:i A', strtotime($d->created_at))}}</div>
                            <div>by {{$d->user->name}}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{route('qes_store_main')}}" method="POST">
    @csrf
    <div class="modal fade" id="newCase" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Case</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="name"><b class="text-danger">*</b>Case Title</label>
                      <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" style="text-transform: uppercase;">
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