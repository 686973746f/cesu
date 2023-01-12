@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><strong><i class="fa-solid fa-users me-2"></i>Patient List</strong></div>
                <div><a href="{{route('patient_create')}}" class="btn btn-success"><i class="fa-solid fa-circle-plus me-2"></i>Add Patient</a></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
                @if(session('pid'))
                <hr>
                <p>You may continue creating Anti-Rabies Vaccination for the Patient by Clicking <b><a href="{{route('encode_create_new', ['id' => session('pid')])}}">HERE</a></b></p>
                @endif
            </div>
            @endif
            <form action="{{route('patient_index')}}" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search by Name / ID" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Date Encoded / By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td><a href="{{route('patient_edit', ['id' => $d->id])}}">{{$d->getName()}}</a></td>
                            <td class="text-center">{{$d->getAge()}} / {{$d->sg()}}</td>
                            <td class="text-center">{{(!is_null($d->contact_number)) ? $d->contact_number : 'N/A'}}</td>
                            <td><small>{{$d->getAddress()}}</small></td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}} @if($d->created_by) / <small>{{$d->user->name}}</small>@endif</td>
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
@endsection