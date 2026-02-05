@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Search Results</b></div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}">
                        <div>{{session('msg')}}</div>
                    </div>
                @endif
                @if($list->isEmpty())
                    <div class="alert alert-info" role="alert">
                        No similar records found. You may proceed to create a new patient record.
                    </div>
                @else
                <div class="alert alert-primary" role="alert">
                    Search result returned similar records. If the patient already exists, please click the name from the list below.
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>
                                <div>Name</div>
                                <div>Age / Sex</div>
                            </th>
                            <th>Birthdate</th>
                            <th>Address</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td>
                                <div><a href="{{route('syndromic_viewPatient', $d->id)}}">{{ $d->getName() }}</a></div>
                                <div>{{$d->getAge()}} / {{$d->gender}}</div>
                            </td>
                            <td>{{ date('m/d/Y', strtotime($d->bdate)) }}</td>
                            <td>{{ $d->getFullAddress() }}</td>
                            <td class="text-center">{{ date('m/d/Y h:i A', strtotime($d->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPatientModal">
                    @if($list->isEmpty())
                    Add new patient
                    @else
                    The patient is not on the list, let me create a new record
                    @endif
                </button>
            </div>
        </div>
    </div>

    <form action="{{route('syndromic_newPatient')}}" method="GET">

        <div class="modal fade" id="newPatientModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Patient</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            Complete the required fields to add a new patient.
                        </div>
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Surname/Last Name/Apelyido</label>
                            <input type="text" class="form-control" name="lname" id="lname" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" autocomplete="off" value="{{request()->lname}}" {{request()->lname ? 'readonly' : ''}} required>
                        </div>
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" autocomplete="off" value="{{request()->fname}}" {{request()->fname ? 'readonly' : ''}} required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mname">Middle Name <i>(If Any)</i></label>
                                    <input type="text" class="form-control" name="mname" id="mname" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" autocomplete="off" value="{{request()->mname}}" {{request()->mname ? 'readonly' : ''}}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suffix">Suffix/Name Extension <i>(If Any)</i></label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" autocomplete="off" value="{{request()->suffix}}" {{request()->suffix ? 'readonly' : ''}}>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" value="{{request()->bdate}}" {{request()->bdate ? 'readonly' : ''}}>
                        </div>
                        @if(isset(request()->from_etcl))
                        <input type="hidden" name="from_etcl" value="{{ request()->from_etcl }}">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection