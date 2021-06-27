@extends('layouts.app')

@section('content')
    <form action="" method="POST">
        <div class="container">
            <div class="card">
                <div class="card-header">Schedule for Swab Form</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pType"><span class="text-danger font-weight-bold">*</span>Type of Client</label>
                                <select class="form-control" name="pType" id="pType" required>
                                    <option value="" disabled selected>Choose...</option>
                                    <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>COVID-19 Case (Suspect, Probable, or Confirmed)</option>
                                    <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                    <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>For RT-PCR Testing (Not a Case of Close Contact)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="isForHospitalization"><span class="text-danger font-weight-bold">*</span>For Hospitalization</label>
                              <select class="form-control" name="isForHospitalization" id="isForHospitalization" required>
                                  <option value="" disabled selected>Choose...</option>
                                    <option value="1" {{(old('isForHospitalization') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isForHospitalization') == 0) ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date of Interview</label>
                        <input type="date" name="interviewDate" id="interviewDate" class="form-control" value="{{old('interviewDate', date('Y-m-d'))}}" max="{{date('Y-m-d')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="philhealth">Philhealth No. <small><i>(Leave blank if N/A)</i></small></label>
                        <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" minlength="12" maxlength="14">
                        <small class="form-text text-muted">Note: If your input has no dashes, the system will automatically do that for you.</small>
                        @error('philhealth')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" max="50" autofocus required>
                                @error('lname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
                                <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" max="50" required>
                                @error('fname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" max="50">
                                @error('mname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                @error('bdate')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected>Choose</option>
                                    <option value="MALE" @if(old('gender') == 'MALE') {{'selected'}} @endif>Male</option>
                                    <option value="FEMALE" @if(old('gender') == 'FEMALE') {{'selected'}} @endif>Female</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                <select class="form-control" id="cs" name="cs" required>
                                    <option value="" disabled selected>Choose</option>
                                    <option value="SINGLE" @if(old('cs') == 'SINGLE') {{'selected'}} @endif>Single</option>
                                    <option value="MARRIED" @if(old('cs') == 'MARRIED') {{'selected'}} @endif>Married</option>
                                    <option value="WIDOWED" @if(old('cs') == 'WIDOWED') {{'selected'}} @endif>Widowed</option>
                                    <option value="N/A" @if(old('cs') == 'N/A') {{'selected'}} @endif>N/A</option>
                                </select>
                                @error('cs')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nationality"><span class="text-danger font-weight-bold">*</span>Nationality</label>
                                <select class="form-control" id="nationality" name="nationality" required>
                                    <option value="Filipino" @if(old('nationality') == 'Filipino' || empty(old('nationality'))) {{'selected'}} @endif>Filipino</option>
                                    <option value="Foreign" @if(old('nationality') == 'Foreign') {{'selected'}} @endif>Foreign</option>
                                </select>
                                @error('nationality')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="occupation">Occupation</label>
                              <input type="text" class="form-control" name="occupation" id="occupation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="occupation">Name of Workplace</label>
                                <input type="text" class="form-control" name="occupation" id="occupation">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="">Nature of Work</label>
                              <select class="form-control" name="" id="">
                                <option></option>
                                <option></option>
                                <option></option>
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection