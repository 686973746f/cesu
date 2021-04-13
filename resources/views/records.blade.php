@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        List of Patients
                    </div>
                    <div>
                        <a href="{{route('records.create')}}" class="btn btn-success">Add Patient</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-{{session('statustype')}}" role="alert">
                        {{session('status')}}
                    </div>
                    <hr>
                @endif
                <!--
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <form action="{{route('records.index')}}" method="GET" autocomplete="off">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search..." name="q" value="{{request()->input('q')}}">
                                    <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search mr-2"></i>Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                -->
                <table class="table table-bordered" id="table_id">
                    <thead>
                        <tr class="text-center">
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Civil Status</th>
                            <th>Mobile</th>
                            <th>Philhealth</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                        <tr>
                            <td style="vertical-align: middle">{{$record->lname.", ".$record->fname." ".$record->mname}}</td>
                            <td style="vertical-align: middle" class="text-center">{{$record->gender}}</td>
                            <td style="vertical-align: middle" class="text-center">{{date("m/d/Y", strtotime($record->bdate))}}</td>
                            <td style="vertical-align: middle" class="text-center">{{$record->cs}}</td>
                            <td style="vertical-align: middle" class="text-center">{{$record->mobile}}</td>
                            <td style="vertical-align: middle" class="text-center">{{(!is_null($record->philhealth)) ? $record->philhealth : "N/A"}}</td>
                            <td style="vertical-align: middle">{{$record->user->name}}</td>
                            <td style="vertical-align: middle" class="text-center">{{date('m/d/Y H:i:s', strtotime($record->created_at))}}</td>
                            <td style="vertical-align: middle" class="text-center"><a href="records/{{$record->id}}/edit" class="btn btn-primary">Edit</a></td>
                        </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
        });
    </script>
@endsection