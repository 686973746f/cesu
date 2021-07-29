@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">
                        Patient List
                    </div>
                    <div>
                        <a href="{{route('records.create')}}" class="btn btn-success"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>Add Patient</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-{{session('statustype')}}" role="alert">
                        {{session('status')}}
                        @if(session('type') == 'createRecord')
                        <hr>
                        Click <a href="/forms/{{session('newid')}}/new">HERE</a> to proceed on creating CIF for the newly added patient.
                        @endif
                    </div>
                    <hr>
                @endif
                <form action="{{route('records.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search">
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_id">
                        <thead>
                            <tr class="text-center thead-light">
                                <th style="vertical-align: middle">Name</th>
                                <th style="vertical-align: middle">Birthdate</th>
                                <th style="vertical-align: middle">Age/Gender</th>
                                <th style="vertical-align: middle">Civil Status</th>
                                <th style="vertical-align: middle">Mobile</th>
                                <th style="vertical-align: middle">Philhealth</th>
                                <th style="vertical-align: middle">Occupation</th>
                                <th style="vertical-align: middle">Address</th>
                                <th style="vertical-align: middle">Encoded/Edited By</th>
                                <th style="vertical-align: middle">Date Encoded/Edited</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td style="vertical-align: middle">
                                        <a href="records/{{$record->id}}/edit" class="btn btn-link text-left">{{$record->lname.", ".$record->fname." ".$record->mname}}</a>
                                    </td>
                                    <td style="vertical-align: middle" class="text-center">{{date("m/d/Y", strtotime($record->bdate))}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->getAge()}} / {{substr($record->gender,0,1)}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->cs}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->mobile}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->philhealth)) ? $record->philhealth : "N/A"}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->occupation)) ? $record->occupation : "N/A"}}</td>
                                    <td style="vertical-align: middle"><small>{{$record->getAddress()}}</small></td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->user->name}} {{(!is_null($record->updated_by)) ? ' / '.$record->getEditedBy() : ''}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->updated_by)) ? date('m/d/Y h:i A', strtotime($record->updated_at)) : date('m/d/Y h:i A', strtotime($record->created_at))}}</td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="pagination justify-content-center mt-3">
                        {{$records->appends(request()->input())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                responsive: true,
                "order": [[0, "asc"]],
                "dom": "rt"
            });
        });
    </script>
@endsection