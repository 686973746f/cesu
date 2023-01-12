@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Vaccine Brand List</div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addvb"><i class="fa-solid fa-circle-plus me-2"></i>Add</button>
                </div>
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
                        <th>ID</th>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Enabled</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $d)
                    <tr>
                        <td class="text-center">{{$d->id}}</td>
                        <td>{{$d->brand_name}}</td>
                        <td class="text-center">{{$d->generic_name}}</td>
                        <td class="text-center">{{($d->enabled == 1) ? 'Y' : 'N'}}</td>
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

<form action="{{route('vaccinebrand_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addvb" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Anti-Rabies Vaccine Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="brand_name" id="brand_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="generic_name" class="form-label">Generic Name</label>
                        <select class="form-control" name="generic_name" id="generic_name" required>
                            <option value="" disabled {{(is_null(old('generic_name'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="PCEC" {{(old('generic_name') == 'PCEC') ? 'selected' : ''}}>PURIFIED CHICK EMBRYO CELL VACCINE (PCEC)</option>
                            <option value="PVRV" {{(old('generic_name') == 'PVRV') ? 'selected' : ''}}>PURIFIED VERO CELL RABIES VACCINE (PVRV)</option>
                            <option value="OTHERS" {{(old('generic_name') == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection