@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><strong><i class="fa fa-user mr-2" aria-hidden="true"></i>ABTC Patients List (Total: {{number_format($list->total())}})</strong></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPatient"><i class="fas fa-user-plus mr-2"></i>Add Patient</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
                @if(session('pid'))
                <hr>
                <p>You may continue creating Anti-Rabies Vaccination for the Patient by Clicking <b><a href="{{route('abtc_encode_create_new', ['id' => session('pid')])}}">HERE</a></b></p>
                @endif
            </div>
            @endif
            <form action="{{route('abtc_patient_index')}}" method="GET">
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
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
                            <td><a href="{{route('abtc_patient_edit', ['id' => $d->id])}}">{{$d->getName()}}</a></td>
                            <td class="text-center">{{$d->getAge()}} / {{$d->sg()}}</td>
                            <td class="text-center">{{(!is_null($d->contact_number)) ? $d->contact_number : 'N/A'}}</td>
                            <td><small>{{$d->getAddress()}}</small></td>
                            <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}} @if($d->created_by) ({{$d->getCreatedBy()}})@endif</small></td>
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

<form action="{{route('abtc_patient_new_check')}}" method="GET">
    @csrf
    <div class="modal fade" id="addPatient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New ABTC Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname"><b class="text-danger">*</b>Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" maxlength="50" style="text-transform: uppercase;" required>
                                <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="suffix"><b class="text-danger">*</b>Suffix</label>
                              <select class="form-control" name="suffix" id="suffix" required>
                                <option value="" disabled {{is_null(old('suffix')) ? 'selected' : ''}}>Choose...</option>
                                <option value="I" {{(old('suffix') == 'I') ? 'selected' : ''}}>I</option>
                                <option value="II" {{(old('suffix') == 'II') ? 'selected' : ''}}>II</option>
                                <option value="III" {{(old('suffix') == 'III') ? 'selected' : ''}}>III</option>
                                <option value="IV" {{(old('suffix') == 'IV') ? 'selected' : ''}}>IV</option>
                                <option value="V" {{(old('suffix') == 'V') ? 'selected' : ''}}>V</option>
                                <option value="VI" {{(old('suffix') == 'VI') ? 'selected' : ''}}>VI</option>
                                <option value="VII" {{(old('suffix') == 'VII') ? 'selected' : ''}}>VII</option>
                                <option value="VIII" {{(old('suffix') == 'VIII') ? 'selected' : ''}}>VIII</option>
                                <option value="JR" {{(old('suffix') == 'JR') ? 'selected' : ''}}>JR</option>
                                <option value="JR II" {{(old('suffix') == 'JR II') ? 'selected' : ''}}>JR II</option>
                                <option value="SR" {{(old('suffix') == 'SR') ? 'selected' : ''}}>SR</option>
                                <option value="N/A" {{(old('suffix') == 'N/A') ? 'selected' : ''}}>N/A (NOT APPLICABLE)</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('-21 Days'))}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection