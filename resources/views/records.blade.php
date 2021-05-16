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
                <form action="{{route('records.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" placeholder="Search">
                                <div class="input-group-append">
                                  <button class="btn btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_id">
                        <thead>
                            <tr class="text-center bg-light">
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Age/Gender</th>
                                <th>Civil Status</th>
                                <th>Mobile</th>
                                <th>Philhealth</th>
                                <th>Occupation</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                @if($record->user->brgy_id == auth()->user()->brgy_id || is_null(auth()->user()->brgy_id))
                                    <tr>
                                        <td style="vertical-align: middle">{{$record->lname.", ".$record->fname." ".$record->mname}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{date("m/d/Y", strtotime($record->bdate))}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{$record->getAge()}} / {{$record->gender}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{$record->cs}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{$record->mobile}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{(!is_null($record->philhealth)) ? $record->philhealth : "N/A"}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{(!is_null($record->occupation)) ? $record->occupation : "N/A"}}</td>
                                        <td style="vertical-align: middle">{{$record->user->name}}</td>
                                        <td style="vertical-align: middle" class="text-center">{{date('m/d/Y h:i A', strtotime($record->created_at))}}</td>
                                        <td style="vertical-align: middle" class="text-center"><a href="records/{{$record->id}}/edit" class="btn btn-primary">Edit</a></td>
                                    </tr>
                                @endif
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="pagination justify-content-center mt-3">
                        {{$records->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                "order": [[0, "asc"]],
                "dom": "rt"
            });
        });
    </script>
@endsection