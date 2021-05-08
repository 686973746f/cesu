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
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_id">
                        <thead>
                            <tr class="text-center">
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
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                "order": [[0, "asc"]]
            });
        });
    </script>
@endsection