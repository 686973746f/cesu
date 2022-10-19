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
                            <p>To check/update the existing patient details, click <a href="{{session('link')}}">HERE</a></p>
                                @if(session('ciflink'))
                                <p class="mb-0">To check/update the existing CIF associated with the patient, click <a href="{{session('ciflink')}}">HERE</a></p>
                                <hr>
                                <div class="alert alert-info" role="alert">
                                    <p>Existing CIF Details:</p>
                                    <p><strong>Birthdate: </strong> {{date('m/d/Y', strtotime(session('cifdetails')->records->bdate))}} • 
                                        <strong>Age/Sex: </strong> {{session('cifdetails')->records->getAge()}} / {{substr(session('cifdetails')->records->gender,0,1)}}</p>
                                    <p><strong>Philhealth: </strong> {{session('cifdetails')->records->getPhilhealth()}} • 
                                    <strong>Mobile: </strong> {{session('cifdetails')->records->mobile}}</p>
                                    <p><strong>Address: </strong> {{session('cifdetails')->records->getAddress()}}</p>
                                    <p><strong>Date Encoded / By:</strong> {{date('m/d/Y h:i A', strtotime(session('cifdetails')->created_at))}} ({{session('cifdetails')->user->name}}) 
                                        @if(!is_null(session('cifdetails')->updated_by)) • <strong>Date Edited / By:</strong> {{date('m/d/Y h:i A', strtotime(session('cifdetails')->updated_at))}} ({{session('cifdetails')->getEditedBy()}})@endif</p>
                                    <p><strong>Morbidity Month / Week:</strong> {{date('m/d/Y (W)', strtotime(session('cifdetails')->morbidityMonth))}} •
                                        <strong>Date Reported:</strong> {{date('m/d/Y', strtotime(session('cifdetails')->dateReported))}}</p>
                                    <p><strong>DRU: </strong> {{session('cifdetails')->drunit}} ({{session('cifdetails')->drregion}} {{session('cifdetails')->drprovince}})</p>
                                    <p><strong>Patient Type:</strong> {{session('cifdetails')->getType()}} • 
                                        <strong>Health Status: </strong> {{session('cifdetails')->healthStatus}} • 
                                        <strong>Classification:</strong> {{session('cifdetails')->caseClassification}}
                                    </p>
                                    <p>
                                        <strong>Quarantine Status:</strong> {{session('cifdetails')->getQuarantineStatus()}} ({{date('m/d/Y', strtotime(session('cifdetails')->dispoDate))}}) • 
                                        <strong>Outcome:</strong> <span class="{{(session('cifdetails')->outcomeCondition == 'Recovered') ? 'font-weight-bold text-success' : ''}}">{{session('cifdetails')->outcomeCondition}} {{(!is_null(session('cifdetails')->getOutcomeDate())) ? '('.session('cifdetails')->getOutcomeDate().')' : ''}}</span>
                                    </p>
                                    @if(session('cifdetails')->ifScheduled())
                                    <hr>
                                    <p><strong>Most Recent Swab Date:</strong> {{session('cifdetails')->getLatestTestDate()}} • 
                                        <strong>Test Type:</strong> {{session('cifdetails')->getLatestTestType()}}
                                    </p>
                                    <p>
                                        @if(!is_null(session('cifdetails')->getLatestTestDateReleased()))
                                        <strong>Date Released: </strong> {{session('cifdetails')->getLatestTestDateReleased()}} • 
                                        @endif
                                        @if(!is_null(session('cifdetails')->getLatestTestLaboratory()))
                                        <strong>Laboratory: </strong> {{session('cifdetails')->getLatestTestLaboratory()}} • 
                                        @endif
                                        <strong>Result:</strong> <span class="{{(session('cifdetails')->getLatestTestResult() == 'POSITIVE' ? 'text-danger font-weight-bold' : '')}}">{{session('cifdetails')->getLatestTestResult()}}</span>
                                    </p>
                                    <p><strong>Attended: </strong>{{session('cifdetails')->getAttendedOnSwab()}}</p>
                                    @else
                                    <hr>
                                    <p><b>No Swab Schedule found on current CIF.</b></p>
                                    @endif
                                </div>
                                @endif
                            @else
                            <hr>
                            The record was created by other Barangay or CESU Staff/Encoder Account, therefore you cannot proceed editing the record. You may coordinate to CESU for sharing the data access for this patient.
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
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search by Name / ID" style="text-transform: uppercase;" required>
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(auth()->user()->isBrgyAccount())
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Showing all results including patients addressed to BRGY. {{auth()->user()->brgy->brgyName}} that was encoded by other users in the system.
                </div>
                @endif
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
                                @if($record->ifAllowedToViewConfidential())
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
                                    <td style="vertical-align: middle" class="text-center">{{($record->form) ? $record->form->pType : ''}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{($record->form) ? $record->form->caseClassification : ''}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{($record->form) ? $record->form->outcomeCondition : ''}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{$record->user->name}}{{(!is_null($record->updated_by) && $record->user_id != $record->updated_by) ? ' / '.$record->getEditedBy() : ''}}</td>
                                    <td style="vertical-align: middle" class="text-center">{{(!is_null($record->updated_by)) ? date('m/d/Y h:i A', strtotime($record->updated_at)) : date('m/d/Y h:i A', strtotime($record->created_at))}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td style="vertical-align: middle">
                                        <a href="records/{{$record->id}}/edit" class="btn btn-link text-left">{{$record->lname.", ".$record->fname." ".$record->mname}} (#{{$record->id}})</a>
                                    </td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                    <td style="vertical-align: middle" class="text-center">*****</td>
                                </tr>
                                @endif
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
                        <h5 class="modal-title">Check Record if Existing before Encoding</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
							<label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
							<input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                            @error('lname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                        <div class="form-group">
							<label for="fname"><span class="text-danger font-weight-bold">*</span>First Name and Suffix (e.g. JR, SR, III, IV, etc.)</label>
							<input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                            @error('fname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                        <div class="form-group">
							<label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
							<input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;">
                            @error('mname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
                        <div class="form-group">
							<label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
							<input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
							@error('bdate')
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