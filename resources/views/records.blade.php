@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">
                        Patient List (Total Count: {{number_format($records->total())}})
                    </div>
                    <div>
                        <a href="{{route('records.duplicatechecker')}}" class="btn btn-primary">Duplicate Checker (Coming Soon)</a>
                        <button href="{{route('records.create')}}" class="btn btn-success" data-toggle="modal" data-target="#checkuser"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>Add Patient</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-{{session('statustype')}}" role="alert">
                        {{session('status')}}
                        @if(session('type') == 'recordExisting')
                            @if(session('eligibleToEdit') == true)
                            <hr>
                            <p>To check the existing data, click <a href="{{session('link')}}">HERE</a></p>
                                @if(session('ciflink'))
                                <p class="mb-0">To check the existing CIF associated with the record, click <a href="{{session('ciflink')}}">HERE</a></p>
                                @endif
                            @else
                            <hr>
                            The record was created by other Barangay or CESU Staff/Encoder Account, therefore you cannot proceed editing the record.
                            @endif
                        @endif
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
                                <th style="vertical-align: middle">Name / ID</th>
                                <th style="vertical-align: middle">Birthdate</th>
                                <th style="vertical-align: middle">Age/Gender</th>
                                <th style="vertical-align: middle">Civil Status</th>
                                <th style="vertical-align: middle">Mobile</th>
                                <th style="vertical-align: middle">Philhealth</th>
                                <th style="vertical-align: middle">Occupation</th>
                                <th style="vertical-align: middle">Address</th>
                                <th style="vertical-align: middle">Patient Type</th>
                                <th style="vertical-align: middle">Case Classification</th>
                                <th style="vertical-align: middle">Outcome</th>
                                <th style="vertical-align: middle">Encoded/Edited By</th>
                                <th style="vertical-align: middle">Date Encoded/Edited</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td style="vertical-align: middle">
                                        <a href="records/{{$record->id}}/edit" class="btn btn-link text-left">{{$record->lname.", ".$record->fname." ".$record->mname}} (#{{$record->id}})</a>
                                    </td>
                                    <td style="vertical-align: middle" class="text-center">{{date("m/d/Y", strtotime($record->bdate))}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->getAge()}} / {{substr($record->gender,0,1)}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->cs}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->mobile}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->philhealth)) ? $record->philhealth : "N/A"}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->occupation)) ? $record->occupation : "N/A"}}</td>
                                    <td style="vertical-align: middle"><small>{{$record->getAddress()}}</small></td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->form?->pType}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->form?->caseClassification}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->form?->outcomeCondition}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->user->name}}{{(!is_null($record->updated_by) && $record->user_id != $record->updated_by) ? ' / '.$record->getEditedBy() : ''}}</td>
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

    <form action="{{route('records.check')}}" method="POST">
        @csrf
        <div class="modal fade" id="checkuser" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Check Record</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
							<label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
							<input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" max="50" style="text-transform: uppercase;" required>
                            @error('lname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                        <div class="form-group">
							<label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
							<input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" max="50" style="text-transform: uppercase;" required>
                            @error('fname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                        <div class="form-group">
							<label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
							<input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" max="50" style="text-transform: uppercase;">
                            @error('mname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Check</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        @if($errors->any())
        $('#checkuser').modal('show');
        @endif

        $(document).ready(function () {
            $('#table_id').DataTable({
                responsive: true,
                "order": [[0, "asc"]],
                "dom": "rt"
            });
        });
    </script>
@endsection