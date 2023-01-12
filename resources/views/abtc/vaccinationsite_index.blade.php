@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Vaccination Sites</div>
                <div><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addvs"><i class="fa-solid fa-circle-plus me-2"></i>Add</button></div>
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
                        <td>{{$d->site_name}}</td>
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

<form action="{{route('vaccinationsite_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addvs" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vaccination Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                      <label for="site_name" class="form-label">Site Name</label>
                      <input type="text" class="form-control" name="site_name" id="site_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection