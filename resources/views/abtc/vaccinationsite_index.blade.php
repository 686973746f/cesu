@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Animal Bite Treatment Centers</b></div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addvs">Add Vaccination Site</button></div>
            </div>
            
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <table class="table table-bordered">
                <thead class="bg-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Site Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $d)
                    <tr>
                        <td class="text-center">{{$d->id}}</td>
                        <td><a href="{{route('abtc_vaccinationsite_edit', $d->id)}}">{{$d->site_name}}</a></td>
                        <td class="text-center">{{date('m/d/Y H:i A', strtotime($d->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

<form action="{{route('abtc_vaccinationsite_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addvs" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add Vaccination Site</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="site_name" class="form-label"><b class="text-danger">*</b>Name of ABTC Facility</label>
                      <input type="text" class="form-control" name="site_name" id="site_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection