@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>View OPD to iClinicSys Ticket #{{$d->id}}</b></h5>
        </nav>
        <form action="{{route('opdtask_close', $d->id)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->syndromic_patient->lname)}}" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->syndromic_patient->fname)}}" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $d->syndromic_patient->mname)}}" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $d->syndromic_patient->suffix)}}" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->syndromic_patient->bdate)}}">
                                <small>Age: {{$d->syndromic_patient->getAge()}}</small>
                            </div>
                            <div class="form-group">
                                <label for="philhealth">Philhealth # (Optional)</label>
                                <input type="text" class="form-control" name="philhealth" id="philhealth" value="{{old('philhealth', $d->syndromic_patient->philhealth)}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                  <select class="form-control" name="gender" id="gender" required>
                                      <option value="MALE" {{(old('gender', $d->syndromic_patient->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                      <option value="FEMALE" {{(old('gender', $d->syndromic_patient->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                <select class="form-control" id="cs" name="cs" required>
                                    <option value="SINGLE" {{(old('cs', $d->syndromic_patient->cs) == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs', $d->syndromic_patient->cs) == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                    <option value="WIDOWED" {{(old('cs', $d->syndromic_patient->cs) == 'WIDOWED') ? 'selected' : ''}}>Widowed</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="ifmarried_div">
                                <label for="spouse_name">Spouse Name</label>
                                <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name', $d->syndromic_patient->spouse_name)}}" style="text-transform: uppercase;">
                              </div>
                            <div class="form-group">
                              <label for="">Email Address (Optional)</label>
                              <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->syndromic_patient->email)}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->syndromic_patient->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                            <div class="form-group">
                                <label for="contact_number2">Contact Number 2 (Optional)</label>
                                <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2', $d->syndromic_patient->contact_number2)}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Mark as Done</button>
                </div>
            </div>
            
        </form>
    </div>
@endsection