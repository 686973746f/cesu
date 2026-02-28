@extends('layouts.app')

@section('content')
@if($mode == 'EDIT' && auth()->user()->isMasterAdminEtcl())
<div class="container">
    <div class="text-right">
        <form action="{{route('etcl_maternal_delete', $d->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mb-3"><i class="fa fa-trash" aria-hidden="true"></i> Delete Record</button>
        </form>
    </div>
</div>
@endif

    @if($mode == 'EDIT')
    <form action="{{route('etcl_maternal_update', $d->id)}}" method="POST">
    @else
    <form action="{{route('etcl_maternal_store', $patient->id)}}" method="POST">
    @endif
    @csrf
    <input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="container">
        <div class="card">
            <div class="card-header">
                @if($mode == 'EDIT')
                <b>Edit Maternal Care (ID: {{ $d->id }})</b>
                @else
                <b>New Maternal Care</b>
                @endif
            </div>
            <div class="card-body">
                @if(session('msg'))
                  <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                  </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_date"><b class="text-danger">*</b>Date of Registration</label>
                            <input type="date" class="form-control" name="registration_date" id="registration_date" value="{{old('registration_date', $d->registration_date)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->registration_date)->subYears(10)->format('Y-01-01') : date('Y-01-01', strtotime('-10 Years')) }}" max="{{date('Y-m-d')}}" {{($mode == 'EDIT') ? (auth()->user()->isAdminEtcl() ? '' : 'disabled') : 'required'}}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Family Serial No.</label>
                          <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' : $patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for=""><b class="text-danger">*</b>Full Name / Age</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->getName() : $patient->getName() }} ({{ ($mode == 'EDIT') ? $d->patient->getAge() : $patient->getAge() }} {{Str::plural('year', ($mode == 'EDIT') ? $d->patient->getAge() : $patient->getAge())}} old)" readonly>
                            <div class="input-group-append">
                              <a class="btn btn-outline-primary" href="{{ route('syndromic_viewPatient', [($mode == 'EDIT') ? $d->patient->id : $patient->id]) }}">View Patient Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Complete Address</label>
                          <textarea class="form-control" rows="3" disabled>{{ ($mode == 'EDIT') ? $d->patient->getFullAddress() : $patient->getFullAddress() }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Last Menstrual Period (LMP)</label>
                            <input type="date" class="form-control" name="lmp" id="lmp" value="{{ old('lmp', $d->lmp) }}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for=""><b class="text-danger">*</b>Height (cm)</label>
                                  <input type="number" class="form-control" name="height" id="height" step="0.1" value="{{old('height', $d->height)}}" min="1" max="900" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Weight (kg)</label>
                                    <input type="number" class="form-control" name="weight" id="weight" step="0.1" value="{{old('weight', $d->weight)}}" min="1" max="900" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Gravida</label>
                                    <input type="number" class="form-control" name="gravida" id="gravida" value="{{old('gravida', $d->gravida)}}" required>
                                    <small class="text-muted">Total number of times a woman has been pregnant, regardless of the outcome.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Parity</label>
                                  <input type="number" class="form-control" name="parity" id="parity" value="{{old('parity', $d->parity)}}" required>
                                  <small class="text-muted">Number of pregnancies carried to a viable gestational age.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edc"><b class="text-danger">*</b>Expected Date of Delivery (EDD)</label>
                            <input type="date" id="edc" name="edc" class="form-control" value="{{ old('edc', $d->edc) }}" max="{{date('Y-m-d', strtotime('+1 Year'))}}" required>
                        </div>

                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>High Risk Pregnancy</label>
                          <select class="form-control" name="highrisk" id="highrisk" required>
                            <option value="" disabled {{old('highrisk', $d->highrisk) ? '' : 'selected'}}>Choose...</option>
                            <option value="Y" {{old('highrisk', $d->highrisk) == 'Y' ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{old('highrisk', $d->highrisk) == 'N' ? 'selected' : ''}}>No</option>
                          </select>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header"><b>Date of Prenatal Check-up (8ANC)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="visit1_est">Visit 1 (Estimated)</label>
                                  <input type="date" class="form-control" name="visit1_est" id="visit1_est" value="{{old('visit1_est', $d->visit1_est)}}">
                                </div>
                                <div class="form-group">
                                    <label for="visit1" class="font-weight-bold">Visit 1 (Actual)</label>
                                    <input type="date" class="form-control" name="visit1" id="visit1" value="{{old('visit1', $d->visit1)}}" max="{{date('Y-m-d')}}">
                                    <small class="text-muted">8-13 weeks</small>
                                </div>
                                <div id="visit1_div" class="d-none">
                                    <div class="form-group" id="visit1_bp_div">
                                        <label for="visit1_bp">Visit 1 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit1_bp" id="visit1_bp" value="{{old('visit1_bp', $d->visit1_bp)}}">
                                    </div>
                                    <div class="form-group">
                                      <label for="visit1_type">Visit 1 Type</label>
                                      <select class="form-control" name="visit1_type" id="visit1_type">
                                        <option value="" disabled {{old('visit1_type', $d->visit1_type) ? '' : 'selected'}}>Choose...</option>
                                        <option value="YOUR BHS" {{old('visit1_type', $d->visit1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                        <option value="PUBLIC" {{old('visit1_type', $d->visit1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                        <option value="PRIVATE" {{old('visit1_type', $d->visit1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                        <option value="OTHER RHU/BHS" {{old('visit1_type', $d->visit1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit2_est">Visit 2 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit2_est" id="visit2_est" value="{{old('visit2_est', $d->visit2_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit2" class="font-weight-bold">Visit 2 (Actual)</label>
                                      <input type="date" class="form-control" name="visit2" id="visit2" value="{{old('visit2', $d->visit2)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">14-20 weeks</small>
                                  </div>
                                  <div id="visit2_div" class="d-none">
                                    <div class="form-group" id="visit2_bp_div">
                                        <label for="visit2_bp">Visit 2 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit2_bp" id="visit2_bp" value="{{old('visit2_bp', $d->visit2_bp)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="visit2_type">Visit 2 Type</label>
                                        <select class="form-control" name="visit2_type" id="visit2_type">
                                        <option value="" disabled {{old('visit2_type', $d->visit2_type) ? '' : 'selected'}}>Choose...</option>
                                        <option value="YOUR BHS" {{old('visit2_type', $d->visit2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                        <option value="PUBLIC" {{old('visit2_type', $d->visit2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                        <option value="PRIVATE" {{old('visit2_type', $d->visit2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                        <option value="OTHER RHU/BHS" {{old('visit2_type', $d->visit2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit3_est">Visit 3 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit3_est" id="visit3_est" value="{{old('visit3_est', $d->visit3_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit3" class="font-weight-bold">Visit 3 (Actual)</label>
                                      <input type="date" class="form-control" name="visit3" id="visit3" value="{{old('visit3', $d->visit3)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">21-27 weeks</small>
                                  </div>
                                  <div id="visit3_div" class="d-none">
                                    <div class="form-group" id="visit3_bp_div">
                                        <label for="visit3_bp">Visit 3 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit3_bp" id="visit3_bp" value="{{old('visit3_bp', $d->visit3_bp)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="visit3_type">Visit 3 Type</label>
                                        <select class="form-control" name="visit3_type" id="visit3_type">
                                        <option value="" disabled {{old('visit3_type', $d->visit3_type) ? '' : 'selected'}}>Choose...</option>
                                        <option value="YOUR BHS" {{old('visit3_type', $d->visit3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                        <option value="PUBLIC" {{old('visit3_type', $d->visit3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                        <option value="PRIVATE" {{old('visit3_type', $d->visit3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                        <option value="OTHER RHU/BHS" {{old('visit3_type', $d->visit3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit4_est">Visit 4 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit4_est" id="visit4_est" value="{{old('visit4_est', $d->visit4_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit4" class="font-weight-bold">Visit 4 (Actual)</label>
                                      <input type="date" class="form-control" name="visit4" id="visit4" value="{{old('visit4', $d->visit4)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">28-30 weeks</small>
                                  </div>
                                  <div id="visit4_div" class="d-none">
                                    <div class="form-group" id="visit4_bp_div">
                                        <label for="visit4_bp">Visit 4 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit4_bp" id="visit4_bp" value="{{old('visit4_bp', $d->visit4_bp)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="visit4_type">Visit 4 Type</label>
                                        <select class="form-control" name="visit4_type" id="visit4_type">
                                        <option value="" disabled {{old('visit4_type', $d->visit4_type) ? '' : 'selected'}}>Choose...</option>
                                        <option value="YOUR BHS" {{old('visit4_type', $d->visit4_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                        <option value="PUBLIC" {{old('visit4_type', $d->visit4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                        <option value="PRIVATE" {{old('visit4_type', $d->visit4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                        <option value="OTHER RHU/BHS" {{old('visit4_type', $d->visit4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit5_est">Visit 5 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit5_est" id="visit5_est" value="{{old('visit5_est', $d->visit5_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit5" class="font-weight-bold">Visit 5 (Actual)</label>
                                      <input type="date" class="form-control" name="visit5" id="visit5" value="{{old('visit5', $d->visit5)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">31-34 weeks</small>
                                  </div>
                                  <div id="visit5_div" class="d-none">
                                    <div class="form-group" id="visit5_bp_div">
                                        <label for="visit5_bp">Visit 5 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit5_bp" id="visit5_bp" value="{{old('visit5_bp', $d->visit5_bp)}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="visit5_type">Visit 5 Type</label>
                                        <select class="form-control" name="visit5_type" id="visit5_type">
                                        <option value="" disabled {{old('visit5_type', $d->visit5_type) ? '' : 'selected'}}>Choose...</option>
                                        <option value="YOUR BHS" {{old('visit5_type', $d->visit5_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                        <option value="PUBLIC" {{old('visit5_type', $d->visit5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                        <option value="PRIVATE" {{old('visit5_type', $d->visit5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                        <option value="OTHER RHU/BHS" {{old('visit5_type', $d->visit5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit6_est">Visit 6 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit6_est" id="visit6_est" value="{{old('visit6_est', $d->visit6_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit6" class="font-weight-bold">Visit 6 (Actual)</label>
                                      <input type="date" class="form-control" name="visit6" id="visit6" value="{{old('visit6', $d->visit6)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">35 weeks</small>
                                  </div>
                                  <div id="visit6_div" class="d-none">
                                    <div class="form-group" id="visit6_bp_div">
                                        <label for="visit6_bp">Visit 6 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit6_bp" id="visit6_bp" value="{{old('visit6_bp', $d->visit6_bp)}}">
                                      </div>
                                      <div class="form-group">
                                        <label for="visit6_type">Visit 6 Type</label>
                                        <select class="form-control" name="visit6_type" id="visit6_type">
                                          <option value="" disabled {{old('visit6_type', $d->visit6_type) ? '' : 'selected'}}>Choose...</option>
                                          <option value="YOUR BHS" {{old('visit6_type', $d->visit6_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                          <option value="PUBLIC" {{old('visit6_type', $d->visit6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                          <option value="PRIVATE" {{old('visit6_type', $d->visit6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                          <option value="OTHER RHU/BHS" {{old('visit6_type', $d->visit6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                      </div>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit7_est">Visit 7 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit7_est" id="visit7_est" value="{{old('visit7_est', $d->visit7_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit7" class="font-weight-bold">Visit 7 (Actual)</label>
                                      <input type="date" class="form-control" name="visit7" id="visit7" value="{{old('visit7', $d->visit7)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">36 weeks</small>
                                  </div>
                                    <div id="visit7_div" class="d-none">
                                        <div class="form-group" id="visit7_bp_div">
                                            <label for="visit7_bp">Visit 7 Blood Pressure</label>
                                            <input type="text" class="form-control bp-field" name="visit7_bp" id="visit7_bp" value="{{old('visit7_bp', $d->visit7_bp)}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="visit7_type">Visit 7 Type</label>
                                            <select class="form-control" name="visit7_type" id="visit7_type">
                                            <option value="" disabled {{old('visit7_type', $d->visit7_type) ? '' : 'selected'}}>Choose...</option>
                                            <option value="YOUR BHS" {{old('visit7_type', $d->visit7_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                            <option value="PUBLIC" {{old('visit7_type', $d->visit7_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                            <option value="PRIVATE" {{old('visit7_type', $d->visit7_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                            <option value="OTHER RHU/BHS" {{old('visit7_type', $d->visit7_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="visit8_est">Visit 8 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit8_est" id="visit8_est" value="{{old('visit8_est', $d->visit8_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="visit8" class="font-weight-bold">Visit 8 (Actual)</label>
                                      <input type="date" class="form-control" name="visit8" id="visit8" value="{{old('visit8', $d->visit8)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">37-40 weeks</small>
                                  </div>
                                  <div id="visit8_div" class="d-none">
                                    <div class="form-group" id="visit8_bp_div">
                                        <label for="visit8_bp">Visit 8 Blood Pressure</label>
                                        <input type="text" class="form-control bp-field" name="visit8_bp" id="visit8_bp" value="{{old('visit8_bp', $d->visit8_bp)}}">
                                      </div>
                                      <div class="form-group">
                                        <label for="visit8_type">Visit 8 Type</label>
                                        <select class="form-control" name="visit8_type" id="visit8_type">
                                          <option value="" disabled {{old('visit8_type', $d->visit8_type) ? '' : 'selected'}}>Choose...</option>
                                          <option value="YOUR BHS" {{old('visit8_type', $d->visit8_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                          <option value="PUBLIC" {{old('visit8_type', $d->visit8_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                          <option value="PRIVATE" {{old('visit8_type', $d->visit8_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                          <option value="OTHER RHU/BHS" {{old('visit8_type', $d->visit8_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                        </select>
                                      </div>
                                  </div> 
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="with_dangersign"><b class="text-danger">*</b>With Danger Signs</label>
                                    <select class="form-control" name="with_dangersign" id="with_dangersign" required>
                                      <option value="" disabled {{old('with_dangersign', $d->with_dangersign) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('with_dangersign', $d->with_dangersign) === '1' ? 'selected' : ''}}>Yes</option>
                                      <option value="0" {{old('with_dangersign', $d->with_dangersign) === '0' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="dangersign_specify_div">
                                    <label for="with_dangersign_specify"><b class="text-danger">*</b>Specify Danger Signs (Select at least 1)</label>
                                    <select class="form-control choices-multi" name="with_dangersign_specify[]" id="with_dangersign_specify" multiple>
                                        <option value="A" {{old('with_dangersign_specify', $d->with_dangersign_specify) && in_array('A', explode(',', old('with_dangersign_specify', $d->with_dangersign_specify))) ? 'selected' : ''}}>New-onset severe headache</option>
                                        <option value="B" {{old('with_dangersign_specify', $d->with_dangersign_specify) && in_array('B', explode(',', old('with_dangersign_specify', $d->with_dangersign_specify))) ? 'selected' : ''}}>Visual disturbances</option>
                                        <option value="C" {{old('with_dangersign_specify', $d->with_dangersign_specify) && in_array('C', explode(',', old('with_dangersign_specify', $d->with_dangersign_specify))) ? 'selected' : ''}}>Severe epigastric pain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="dangersign_referred_div">
                                <div class="form-group">
                                    <label for="dangersign_referred"><b class="text-danger">*</b>Identified with High BP/Danger Signs and referred?</label>
                                    <select class="form-control" name="dangersign_referred" id="dangersign_referred">
                                      <option value="" disabled {{old('dangersign_referred', $d->dangersign_referred) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('dangersign_referred', $d->dangersign_referred) === '1' ? 'selected' : ''}}>Yes</option>
                                      <option value="0" {{old('dangersign_referred', $d->dangersign_referred) === '0' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="dangersign_datereferred_div">
                                    <label for="dangersign_datereferred"><b class="text-danger">*</b>Date Referred</label>
                                    <input type="date" class="form-control" name="dangersign_datereferred" id="dangersign_datereferred" value="{{old('dangersign_datereferred', $d->dangersign_datereferred)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="trans_remarks">Remarks</label>
                            <select class="form-control" name="trans_remarks" id="trans_remarks">
                              <option value="" {{old('trans_remarks', $d->trans_remarks) ? '' : 'selected'}}>N/A</option>
                              <option value="A" {{old('trans_remarks', $d->trans_remarks) == 'A' ? 'selected' : ''}}>Resident</option>
                              <option value="B" {{old('trans_remarks', $d->trans_remarks) == 'B' ? 'selected' : ''}}>Trans In</option>
                              <option value="C" {{old('trans_remarks', $d->trans_remarks) == 'C' ? 'selected' : ''}}>Trans Out before receiving 8ANC</option>
                            </select>
                        </div>
                        <div id="transout_div" class="d-none">
                            <div class="form-group">
                              <label for="transout_date"><b class="text-danger">*</b>Trans Out Date</label>
                              <input type="date" class="form-control" name="transout_date" id="transout_date" value="{{old('transout_date', $d->transout_date)}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header"><b>Tetanus Diptheria (Td)</b></div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="td1" class="font-weight-bold"><b class="text-danger d-none" id="td1_asterisk">*</b>Td1</label>
                                    <input type="date" class="form-control" name="td1" id="td1" value="{{old('td1', $d->td1)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="td1_type">Td1 Type</label>
                                    <select class="form-control" name="td1_type" id="td1_type">
                                      <option value="" disabled {{old('td1_type', $d->td1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('td1_type', $d->td1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('td1_type', $d->td1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td1_type', $d->td1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td1_type', $d->td1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="td2" class="font-weight-bold"><b class="text-danger d-none" id="td2_asterisk">*</b>Td2</label>
                                    <input type="date" class="form-control" name="td2" id="td2" value="{{old('td2', $d->td2)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="td2_type">Td2 Type</label>
                                    <select class="form-control" name="td2_type" id="td2_type">
                                      <option value="" disabled {{old('td2_type', $d->td2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('td2_type', $d->td2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('td2_type', $d->td2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td2_type', $d->td2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td2_type', $d->td2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="td3" class="font-weight-bold">Td3</label>
                                    <input type="date" class="form-control" name="td3" id="td3" value="{{old('td3', $d->td3)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="td3_type">Td3 Type</label>
                                    <select class="form-control" name="td3_type" id="td3_type">
                                      <option value="" disabled {{old('td3_type', $d->td3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('td3_type', $d->td3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('td3_type', $d->td3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td3_type', $d->td3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td3_type', $d->td3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="td4" class="font-weight-bold">Td4</label>
                                    <input type="date" class="form-control" name="td4" id="td4" value="{{old('td4', $d->td4)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="td4_type">Td4 Type</label>
                                    <select class="form-control" name="td4_type" id="td4_type">
                                      <option value="" disabled {{old('td4_type', $d->td4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('td4_type', $d->td4_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('td4_type', $d->td4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td4_type', $d->td4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td4_type', $d->td4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="td5" class="font-weight-bold">Td5</label>
                                    <input type="date" class="form-control" name="td5" id="td5" value="{{old('td5', $d->td5)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="td5_type">Td5 Type</label>
                                    <select class="form-control" name="td5_type" id="td5_type">
                                      <option value="" disabled {{old('td5_type', $d->td5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('td5_type', $d->td5_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('td5_type', $d->td5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td5_type', $d->td5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td5_type', $d->td5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="deworming_date" class="font-weight-bold">Deworming Date</label>
                    <input type="date" class="form-control" name="deworming_date" id="deworming_date" value="{{old('deworming_date', $d->deworming_date)}}" max="{{date('Y-m-d')}}">
                </div>

                <div class="card mt-3">
                    <div class="card-header"><b>Iron with Folic Acid (IFA) Supplementation</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa1_date" class="font-weight-bold">1st Visit (1st tri)</label>
                                    <input type="date" class="form-control" name="ifa1_date" id="ifa1_date" value="{{old('ifa1_date', $d->ifa1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa1_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa1_dosage" id="ifa1_dosage" value="{{old('ifa1_dosage', $d->ifa1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa1_type">Type</label>
                                    <select class="form-control" name="ifa1_type" id="ifa1_type">
                                      <option value="" disabled {{old('ifa1_type', $d->ifa1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa1_type', $d->ifa1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa1_type', $d->ifa1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa1_type', $d->ifa1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa1_type', $d->ifa1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa2_date" class="font-weight-bold">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="ifa2_date" id="ifa2_date" value="{{old('ifa2_date', $d->ifa2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa2_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa2_dosage" id="ifa2_dosage" value="{{old('ifa2_dosage', $d->ifa2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa2_type">Type</label>
                                    <select class="form-control" name="ifa2_type" id="ifa2_type">
                                      <option value="" disabled {{old('ifa2_type', $d->ifa2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa2_type', $d->ifa2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa2_type', $d->ifa2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa2_type', $d->ifa2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa2_type', $d->ifa2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa3_date" class="font-weight-bold">3rd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="ifa3_date" id="ifa3_date" value="{{old('ifa3_date', $d->ifa3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa3_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa3_dosage" id="ifa3_dosage" value="{{old('ifa3_dosage', $d->ifa3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa3_type">Type</label>
                                    <select class="form-control" name="ifa3_type" id="ifa3_type">
                                      <option value="" disabled {{old('ifa3_type', $d->ifa3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa3_type', $d->ifa3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa3_type', $d->ifa3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa3_type', $d->ifa3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa3_type', $d->ifa3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa4_date" class="font-weight-bold">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa4_date" id="ifa4_date" value="{{old('ifa4_date', $d->ifa4_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa4_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa4_dosage" id="ifa4_dosage" value="{{old('ifa4_dosage', $d->ifa4_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa4_type">Type</label>
                                    <select class="form-control" name="ifa4_type" id="ifa4_type">
                                      <option value="" disabled {{old('ifa4_type', $d->ifa4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa4_type', $d->ifa4_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa4_type', $d->ifa4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa4_type', $d->ifa4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa4_type', $d->ifa4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa5_date" class="font-weight-bold">5th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa5_date" id="ifa5_date" value="{{old('ifa5_date', $d->ifa5_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa5_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa5_dosage" id="ifa5_dosage" value="{{old('ifa5_dosage', $d->ifa5_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa5_type">Type</label>
                                    <select class="form-control" name="ifa5_type" id="ifa5_type">
                                      <option value="" disabled {{old('ifa5_type', $d->ifa5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa5_type', $d->ifa5_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa5_type', $d->ifa5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa5_type', $d->ifa5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa5_type', $d->ifa5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ifa6_date" class="font-weight-bold">6th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa6_date" id="ifa6_date" value="{{old('ifa6_date', $d->ifa6_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa6_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa6_dosage" id="ifa6_dosage" value="{{old('ifa6_dosage', $d->ifa6_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="ifa6_type">Type</label>
                                    <select class="form-control" name="ifa6_type" id="ifa6_type">
                                      <option value="" disabled {{old('ifa6_type', $d->ifa6_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('ifa6_type', $d->ifa6_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('ifa6_type', $d->ifa6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa6_type', $d->ifa6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa6_type', $d->ifa6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header"><b>Multiple Micronutrient Supplementation (MMS)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms1_date" class="font-weight-bold">1st Visit (1st tri)</label>
                                    <input type="date" class="form-control" name="mms1_date" id="mms1_date" value="{{old('mms1_date', $d->mms1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms1_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms1_dosage" id="mms1_dosage" value="{{old('mms1_dosage', $d->mms1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms1_type">Type</label>
                                    <select class="form-control" name="mms1_type" id="mms1_type">
                                      <option value="" disabled {{old('mms1_type', $d->mms1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('mms1_type', $d->mms1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms1_type', $d->mms1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms1_type', $d->mms1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms1_type', $d->mms1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms2_date" class="font-weight-bold">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="mms2_date" id="mms2_date" value="{{old('mms2_date', $d->mms2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms2_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms2_dosage" id="mms2_dosage" value="{{old('mms2_dosage', $d->mms2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms2_type">Type</label>
                                    <select class="form-control" name="mms2_type" id="mms2_type">
                                      <option value="" disabled {{old('mms2_type', $d->mms2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('mms2_type', $d->mms2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms2_type', $d->mms2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms2_type', $d->mms2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms2_type', $d->mms2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms3_date" class="font-weight-bold">3rd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="mms3_date" id="mms3_date" value="{{old('mms3_date', $d->mms3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms3_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms3_dosage" id="mms3_dosage" value="{{old('mms3_dosage', $d->mms3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms3_type">Type</label>
                                    <select class="form-control" name="mms3_type" id="mms3_type">
                                      <option value="" disabled {{old('mms3_type', $d->mms3_type) ? '' : 'selected'}}>Choose...</option>\
                                      <option value="YOUR BHS" {{old('mms3_type', $d->mms3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms3_type', $d->mms3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms3_type', $d->mms3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms3_type', $d->mms3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms4_date" class="font-weight-bold">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms4_date" id="mms4_date" value="{{old('mms4_date', $d->mms4_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms4_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms4_dosage" id="mms4_dosage" value="{{old('mms4_dosage', $d->mms4_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms4_type">Type</label>
                                    <select class="form-control" name="mms4_type" id="mms4_type">
                                      <option value="" disabled {{old('mms4_type', $d->mms4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('mms4_type', $d->mms4_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms4_type', $d->mms4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms4_type', $d->mms4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms4_type', $d->mms4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms5_date" class="font-weight-bold">5th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms5_date" id="mms5_date" value="{{old('mms5_date', $d->mms5_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms5_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms5_dosage" id="mms5_dosage" value="{{old('mms5_dosage', $d->mms5_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms5_type">Type</label>
                                    <select class="form-control" name="mms5_type" id="mms5_type">
                                      <option value="" disabled {{old('mms5_type', $d->mms5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('mms5_type', $d->mms5_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms5_type', $d->mms5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms5_type', $d->mms5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms5_type', $d->mms5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mms6_date" class="font-weight-bold">6th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms6_date" id="mms6_date" value="{{old('mms6_date', $d->mms6_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms6_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms6_dosage" id="mms6_dosage" value="{{old('mms6_dosage', $d->mms6_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="mms6_type">Type</label>
                                    <select class="form-control" name="mms6_type" id="mms6_type">
                                      <option value="" disabled {{old('mms6_type', $d->mms6_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('mms6_type', $d->mms6_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('mms6_type', $d->mms6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms6_type', $d->mms6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms6_type', $d->mms6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 d-none" id="highrisk_div">
                    <div class="card-header">
                        <b>
                        <div>FOR HIGH RISK PREGNANT</div>
                        <div>Calcium Carbonate (CC) Supplementation</div>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="calcium1_date">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="calcium1_date" id="calcium1_date" value="{{old('calcium1_date', $d->calcium1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium1_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium1_dosage" id="calcium1_dosage" value="{{old('calcium1_dosage', $d->calcium1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium1_type">Type</label>
                                    <select class="form-control" name="calcium1_type" id="calcium1_type">
                                      <option value="" disabled {{old('calcium1_type', $d->calcium1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('calcium1_type', $d->calcium1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('calcium1_type', $d->calcium1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium1_type', $d->calcium1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium1_type', $d->calcium1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="calcium2_date">3rd Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="calcium2_date" id="calcium2_date" value="{{old('calcium2_date', $d->calcium2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium2_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium2_dosage" id="calcium2_dosage" value="{{old('calcium2_dosage', $d->calcium2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium2_type">Type</label>
                                    <select class="form-control" name="calcium2_type" id="calcium2_type">
                                      <option value="" disabled {{old('calcium2_type', $d->calcium2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('calcium2_type', $d->calcium2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('calcium2_type', $d->calcium2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium2_type', $d->calcium2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium2_type', $d->calcium2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="calcium3_date">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="calcium3_date" id="calcium3_date" value="{{old('calcium3_date', $d->calcium3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium3_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium3_dosage" id="calcium3_dosage" value="{{old('calcium3_dosage', $d->calcium3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="calcium3_type">Type</label>
                                    <select class="form-control" name="calcium3_type" id="calcium3_type">
                                      <option value="" disabled {{old('calcium3_type', $d->calcium3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="YOUR BHS" {{old('calcium3_type', $d->calcium3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                                      <option value="PUBLIC" {{old('calcium3_type', $d->calcium3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium3_type', $d->calcium3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium3_type', $d->calcium3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header"><b>Laboratory Screenings</b></div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">CBC/Hgb&Hct Date Screened</label>
                                    <input type="date" class="form-control" name="cbc_date" id="cbc_date" value="{{old('cbc_date', $d->cbc_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="cbc_result" id="cbc_result">
                                      <option value="" disabled {{old('cbc_result', $d->cbc_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('cbc_result', $d->cbc_result) === '1' ? 'selected' : ''}}>With Anemia</option>
                                      <option value="0" {{old('cbc_result', $d->cbc_result) === '0' ? 'selected' : ''}}>W/out Anemia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Gestational Diabetes Mellitus Date Screened</label>
                                    <input type="date" class="form-control" name="diabetes_date" id="diabetes_date" value="{{old('diabetes_date', $d->diabetes_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="diabetes_result" id="diabetes_result">
                                      <option value="" disabled {{old('diabetes_result', $d->diabetes_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('diabetes_result', $d->diabetes_result) === '1' ? 'selected' : ''}}>Positive</option>
                                      <option value="0" {{old('diabetes_result', $d->diabetes_result) === '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Hepatitis B Date Screened</label>
                                    <input type="date" class="form-control" name="hb_date" id="hb_date" value="{{old('hb_date', $d->hb_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="hb_result" id="hb_result">
                                      <option value="" disabled {{old('hb_result', $d->hb_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('hb_result', $d->hb_result) === '1' ? 'selected' : ''}}>Reactive</option>
                                      <option value="0" {{old('hb_result', $d->hb_result) === '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">HIV Date Screened</label>
                                    <input type="date" class="form-control" name="hiv_date" id="hiv_date" value="{{old('hiv_date', $d->hiv_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="hiv_result" id="hiv_result">
                                      <option value="" disabled {{old('hiv_result', $d->hiv_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('hiv_result', $d->hiv_result) === '1' ? 'selected' : ''}}>Reactive</option>
                                      <option value="0" {{old('hiv_result', $d->hiv_result) === '0' ? 'selected' : ''}}>Non-reactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="syphilis_date">Syphilis Date Screened</label>
                                    <input type="date" class="form-control" name="syphilis_date" id="syphilis_date" value="{{old('syphilis_date', $d->syphilis_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="syphilis_result">Result</label>
                                    <select class="form-control" name="syphilis_result" id="syphilis_result">
                                      <option value="" disabled {{old('syphilis_result', $d->syphilis_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('syphilis_result', $d->syphilis_result) === '1' ? 'selected' : ''}}>Positive</option>
                                      <option value="0" {{old('syphilis_result', $d->syphilis_result) === '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                                <div id="syph_confirmatory_div" class="d-none">
                                    <div class="form-group">
                                        <label for="syp_conf_date">Date of Confirmatory Test</label>
                                        <input type="date" class="form-control" name="syp_conf_date" id="syp_conf_date" value="{{old('syp_conf_date', $d->syp_conf_date)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="syp_conf_result">Confirmatory Test Result</label>
                                        <select class="form-control" name="syp_conf_result" id="syp_conf_result">
                                          <option value="" disabled {{old('syp_conf_result', $d->syp_conf_result) ? '' : 'selected'}}>Choose...</option>
                                          <option value="1" {{old('syp_conf_result', $d->syp_conf_result) === '1' ? 'selected' : ''}}>Positive</option>
                                          <option value="0" {{old('syp_conf_result', $d->syp_conf_result) === '0' ? 'selected' : ''}}>Negative</option>
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="syph_conf_pos_div">
                                        <label for="syp_conf_treat">Treatment: Given at least 1 dose of benzathine penicilin 2.4 m U at least 30 days prior to delivery</label>
                                        <select class="form-control" name="syp_conf_treat" id="syp_conf_treat">
                                          <option value="" disabled {{old('syp_conf_treat', $d->syp_conf_treat) ? '' : 'selected'}}>Choose...</option>
                                          <option value="1" {{old('syp_conf_treat', $d->syp_conf_treat) === '1' ? 'selected' : ''}}>Yes</option>
                                          <option value="0" {{old('syp_conf_treat', $d->syp_conf_treat) === '0' ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3 d-none" id="outcome_div">
                    <label for="outcome">Outcome</label>
                    <select class="form-control" name="outcome" id="outcome">
                      <option value="" {{old('outcome', $d->outcome) ? '' : 'selected'}}>N/A</option>
                      <option value="FT" {{old('outcome', $d->outcome) == 'FT' ? 'selected' : ''}}>Full Term</option>
                      <option value="PT" {{old('outcome', $d->outcome) == 'PT' ? 'selected' : ''}}>Pre-term</option>
                      <option value="FD" {{old('outcome', $d->outcome) == 'FD' ? 'selected' : ''}}>Fetal Death</option>
                      <option value="AB" {{old('outcome', $d->outcome) == 'AB' ? 'selected' : ''}}>Abortion/Miscarriage</option>
                    </select>
                </div>

                <div class="row mt-3 d-none" id="delivery_div">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Date and Time of Delivery</label>
                            <input type="datetime-local" class="form-control" name="delivery_date" id="delivery_date" value="{{old('delivery_date', $d->delivery_date)}}">
                        </div>
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Delivery Type</label>
                            <select class="form-control" name="delivery_type" id="delivery_type">
                              <option value="" disabled {{old('delivery_type', $d->delivery_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="CS" {{old('delivery_type', $d->delivery_type) == 'CS' ? 'selected' : ''}}>Caesarean Section</option>
                              <option value="VD" {{old('delivery_type', $d->delivery_type) == 'VD' ? 'selected' : ''}}>Vaginal Delivery</option>
                              <option value="CVCD" {{old('delivery_type', $d->delivery_type) == 'CVCD' ? 'selected' : ''}}>Combined Vaginal-Cesarean Delivery</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="number_livebirths"><b class="text-danger">*</b>Number of Livebirths</label>
                          <input type="text" class="form-control" name="number_livebirths" id="number_livebirths" value="{{old('number_livebirths', $d->number_livebirths)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Facility Type</label>
                            <select class="form-control" name="facility_type" id="facility_type">
                              <option value="" disabled {{old('facility_type', $d->facility_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="PUBLIC" {{old('facility_type', $d->facility_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('facility_type', $d->facility_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="NON/HEALTH FACILITY" {{old('facility_type', $d->facility_type) == 'NON/HEALTH FACILITY' ? 'selected' : ''}}>Non-Health Facility</option>
                            </select>
                        </div>
                        <div id="nonhealth_div">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Non-Health Facility Type</label>
                                <select class="form-control" name="nonhealth_type" id="nonhealth_type">
                                  <option value="" disabled {{old('nonhealth_type', $d->nonhealth_type) ? '' : 'selected'}}>Choose...</option>
                                  <option value="1" {{old('nonhealth_type', $d->nonhealth_type) === '1' ? 'selected' : ''}}>Home</option>
                                  <option value="2" {{old('nonhealth_type', $d->nonhealth_type) === '2' ? 'selected' : ''}}>Others (including emergency transport)</option>
                                </select>
                            </div>
                        </div>
                        <div id="publicprivate_div">
                            <div class="form-group">
                                <label for="place_of_delivery"><b class="text-danger">*</b>Specify BHS, RHU/UHU, government hospitals, public infirmaries, DOH-licensed ambulance</label>
                                <input type="text" class="form-control" name="place_of_delivery" id="place_of_delivery" value="{{old('place_of_delivery', $d->place_of_delivery)}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="bcemoncapable" id="bcemoncapable" value="Y" {{old('bcemoncapable', $d->bcemoncapable) == 'Y' ? 'checked' : ''}}>
                                BEmONC/CEmONC capable facility
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="attendant"><b class="text-danger">*</b>Birth Attendant</label>
                            <select class="form-control" name="attendant" id="attendant">
                              <option value="" disabled {{old('attendant', $d->attendant) ? '' : 'selected'}}>Choose...</option>
                              <option value="MD" {{old('attendant', $d->attendant) == 'MD' ? 'selected' : ''}}>Doctor</option>
                              <option value="RN" {{old('attendant', $d->attendant) == 'RN' ? 'selected' : ''}}>Nurse</option>
                              <option value="MW" {{old('attendant', $d->attendant) == 'MW' ? 'selected' : ''}}>Midwife</option>
                              <option value="O" {{old('attendant', $d->attendant) == 'O' ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherattendant_div">
                            <label for="attendant_others"><b class="text-danger">*</b>Please specify</label>
                            <input type="text" class="form-control" name="attendant_others" id="attendant_others" value="{{old('attendant_others', $d->attendant_others)}}" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                          <label for="birth_weight"><b class="text-danger" id="birth_weight_ast">*</b>Birth Weight (grams)</label>
                          <input type="number" class="form-control" name="birth_weight" id="birth_weight" step="0.01" value="{{old('birth_weight', $d->birth_weight)}}">
                        </div>
                    </div>
                </div>

                <div class="card mt-3 postnatal_div" id="pnc_div">
                    <div class="card-header"><b>Date of Postnatal Care</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pnc1">Contact 1 (within 24 hours after delivery)</label>
                                    <input type="date" class="form-control" name="pnc1" id="pnc1" value="{{old('pnc1', $d->pnc1)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group d-none" id="pnc1_bp_div">
                                    <label for="pnc1_bp">Contact 1 Blood Pressure</label>
                                    <input type="text" class="form-control bp-field" name="pnc1_bp" id="pnc1_bp" value="{{old('pnc1_bp', $d->pnc1_bp)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pnc2">Contact 2 (on day 3)</label>
                                    <input type="date" class="form-control" name="pnc2" id="pnc2" value="{{old('pnc2', $d->pnc2)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group d-none" id="pnc2_bp_div">
                                    <label for="pnc2_bp">Contact 2 Blood Pressure</label>
                                    <input type="text" class="form-control bp-field" name="pnc2_bp" id="pnc2_bp" value="{{old('pnc2_bp', $d->pnc2_bp)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pnc3">Contact 3 (between 7-14 days)</label>
                                    <input type="date" class="form-control" name="pnc3" id="pnc3" value="{{old('pnc3', $d->pnc3)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group d-none" id="pnc3_bp_div">
                                    <label for="pnc3_bp">Contact 3 Blood Pressure</label>
                                    <input type="text" class="form-control bp-field" name="pnc3_bp" id="pnc3_bp" value="{{old('pnc3_bp', $d->pnc3_bp)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pnc4">Contact 4 (6 weeks after birth)</label>
                                    <input type="date" class="form-control" name="pnc4" id="pnc4" value="{{old('pnc4', $d->pnc4)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group d-none" id="pnc4_bp_div">
                                    <label for="pnc4_bp">Contact 4 Blood Pressure</label>
                                    <input type="text" class="form-control bp-field" name="pnc4_bp" id="pnc4_bp" value="{{old('pnc4_bp', $d->pnc4_bp)}}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pnc_with_dangersign"><b class="text-danger">*</b>With Danger Signs</label>
                                    <select class="form-control" name="pnc_with_dangersign" id="pnc_with_dangersign">
                                      <option value="" disabled {{old('pnc_with_dangersign', $d->pnc_with_dangersign) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('pnc_with_dangersign', $d->pnc_with_dangersign) === '1' ? 'selected' : ''}}>Yes</option>
                                      <option value="0" {{old('pnc_with_dangersign', $d->pnc_with_dangersign) === '0' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="pnc_dangersign_specify_div">
                                    <label for="pnc_dangersign_specify"><b class="text-danger">*</b>Specify Danger Signs (Select at least 1)</label>
                                    <select class="form-control choices-multi" name="pnc_dangersign_specify[]" id="pnc_dangersign_specify" multiple>
                                        <option value="A" {{old('pnc_dangersign_specify', $d->pnc_dangersign_specify) && in_array('A', explode(',', old('pnc_dangersign_specify', $d->pnc_dangersign_specify))) ? 'selected' : ''}}>New-onset severe headache</option>
                                        <option value="B" {{old('pnc_dangersign_specify', $d->pnc_dangersign_specify) && in_array('B', explode(',', old('pnc_dangersign_specify', $d->pnc_dangersign_specify))) ? 'selected' : ''}}>Visual disturbances</option>
                                        <option value="C" {{old('pnc_dangersign_specify', $d->pnc_dangersign_specify) && in_array('C', explode(',', old('pnc_dangersign_specify', $d->pnc_dangersign_specify))) ? 'selected' : ''}}>Severe epigastric pain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="pnc_dangersign_referred_div">
                                    <label for="pnc_dangersign_referred"><b class="text-danger">*</b>Identified with High BP/Danger Signs and referred?</label>
                                    <select class="form-control" name="pnc_dangersign_referred" id="pnc_dangersign_referred">
                                      <option value="" disabled {{old('pnc_dangersign_referred', $d->pnc_dangersign_referred) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('pnc_dangersign_referred', $d->pnc_dangersign_referred) === '1' ? 'selected' : ''}}>Yes</option>
                                      <option value="0" {{old('pnc_dangersign_referred', $d->pnc_dangersign_referred) === '2' ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group" id="pnc_dangersign_referred_yes_div">
                                    <label for="pnc_dangersign_datereferred"><b class="text-danger">*</b>Date Referred</label>
                                    <input type="date" class="form-control" name="pnc_dangersign_datereferred" id="pnc_dangersign_datereferred" value="{{old('pnc_dangersign_datereferred', $d->pnc_dangersign_datereferred)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 mb-3 postnatal_div">
                    <div class="card-header">
                        <div><b>Postpartum Supplementation</b></div>
                        <div><b>Iron with Folic Acid Supplementation</b></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pp_td1">1st Visit</label>
                                    <input type="date" class="form-control" name="pp_td1" id="pp_td1" value="{{old('pp_td1', $d->pp_td1)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="pp_td1_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td1_dosage" id="pp_td1_dosage" value="{{old('pp_td1_dosage', $d->pp_td1_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pp_td2">2nd Visit</label>
                                    <input type="date" class="form-control" name="pp_td2" id="pp_td2" value="{{old('pp_td2', $d->pp_td2)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="pp_td2_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td2_dosage" id="pp_td2_dosage" value="{{old('pp_td2_dosage', $d->pp_td2_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pp_td3">3rd Visit</label>
                                    <input type="date" class="form-control" name="pp_td3" id="pp_td3" value="{{old('pp_td3', $d->pp_td3)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="pp_td3_dosage">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td3_dosage" id="pp_td3_dosage" value="{{old('pp_td3_dosage', $d->pp_td3_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vita">Date Completed Vit. A Supplementation</label>
                                    <input type="date" class="form-control" name="vita" id="vita" value="{{old('vita', $d->vita)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group postnatal_div mt-3">
                  <label for="pp_remarks"><b class="text-danger">*</b>Remarks</label>
                  <select class="form-control" name="pp_remarks" id="pp_remarks">
                    <option value="" {{old('pp_remarks', $d->pp_remarks) ? '' : 'selected'}}>N/A</option>
                    <option value="A" {{old('pp_remarks', $d->pp_remarks) == 'A' ? 'selected' : ''}}>Trans In</option>
                    <option value="B" {{old('pp_remarks', $d->pp_remarks) == 'B' ? 'selected' : ''}}>Trans Out before completing 4PNC</option>
                    <option value="C" {{old('pp_remarks', $d->pp_remarks) == 'C' ? 'selected' : ''}}>Trans In after completing 4PNC</option>
                  </select>
                </div>
                <div id="pp_transout_div" class="d-none">
                    <div class="form-group">
                        <label for="pp_transout_date"><b class="text-danger">*</b>Trans Out Date</label>
                        <input type="date" class="form-control" name="pp_transout_date" id="pp_transout_date" value="{{old('pp_transout_date', $d->pp_transout_date)}}" max="{{date('Y-m-d')}}">
                    </div>
                </div>

                <div class="form-group mt-3">
                  <label for="system_remarks">Notes/Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks', $d->system_remarks)}}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" id="submitBtn" class="btn btn-success btn-block" {{($d->is_locked == 'Y') ? 'disabled' : ''}}>
                    @if($mode == 'EDIT')
                    Update (CTRL + S)
                    @else
                    Save (CTRL + S)
                    @endif
                </button>
            </div>
        </div>
    </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elements = document.querySelectorAll('.choices-multi');

            elements.forEach(function(element) {
                new Choices(element, {
                    removeItemButton: true,
                    searchEnabled: false,
                    itemSelectText: '',
                });
            });
        });

        $('#registration_date').change(function (e) { 
            e.preventDefault();
            var regDate = $('#registration_date').val(); // format: YYYY-MM-DD

            if (regDate) {
                var year = new Date(regDate).getFullYear();
                
                if (year >= 2026) {
                    /*
                    $('#visit1_bp_div').removeClass('d-none');
                    $('#visit2_bp_div').removeClass('d-none');
                    $('#visit3_bp_div').removeClass('d-none');
                    $('#visit4_bp_div').removeClass('d-none');
                    $('#visit5_bp_div').removeClass('d-none');
                    $('#visit6_bp_div').removeClass('d-none');
                    $('#visit7_bp_div').removeClass('d-none');
                    $('#visit8_bp_div').removeClass('d-none');
                    */
                }
            }
        });

        $(document).ready(function () {
            $('form').on('submit', function () {
                $('#submitBtn')
                    .prop('disabled', true)
                    .text('Please wait... Do not refresh or close the page.');
            });
        });

        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#submitBtn').trigger('click');
                $('#submitBtn').prop('disabled', true);
                setTimeout(function() {
                    $('#submitBtn').prop('disabled', false);
                }, 2000);
                return false;
            }
        });

        $(document).on('input', '.bp-field', function () {
            let value = $(this).val();

            // Allow only digits and one slash
            value = value.replace(/[^0-9\/]/g, '');

            // Prevent multiple slashes
            let parts = value.split('/');
            if (parts.length > 2) {
                value = parts[0] + '/' + parts[1];
            }

            $(this).val(value);
        });

        $(document).on('blur', '.bp-field', function () {

            let value = $(this).val();

            // Error if no slash found
            if (!value.includes('/')) {
                alert('BP Must have a slash "/" separating systolic and diastolic values. Use 120/80');
                $(this).val('');
                return;
            }

            let parts = value.split('/');

            // Error if not exactly 2 values
            if (parts.length !== 2 || parts[0] === '' || parts[1] === '') {
                alert('Invalid Blood Pressure format. Use 120/80');
                $(this).val('');
                return;
            }

            let sys = parseInt(parts[0]);
            let dia = parseInt(parts[1]);

            // Error if outside valid range
            if (isNaN(sys) || isNaN(dia) || sys < 70 || sys > 250 || dia < 40 || dia > 150) {
                alert('Invalid Blood Pressure value.');
                $(this).val('');
            }
        });

        /*
        $(document).ready(function () {
            const dates = ['#visit1', '#visit2', '#visit3', '#visit4', '#visit5', '#visit6', '#visit7', '#visit8'];

            function updateRequired() {
                // Find the highest index that has a value
                let lastFilledIndex = -1;

                dates.forEach((selector, index) => {
                    if ($(selector).val()) {
                        lastFilledIndex = index;
                    }
                });

                // Reset required attributes
                dates.forEach(selector => {
                    $(selector).prop('required', false);
                });

                // Make all previous dates required
                for (let i = 0; i < lastFilledIndex; i++) {
                    $(dates[i]).prop('required', true);
                }
            }

            // Run on change of any date field
            $('input[type="date"]').on('change', updateRequired);
        });
        */

        $('#gravida').change(function() {
            $('#td1_asterisk').addClass('d-none');
            $('#td2_asterisk').addClass('d-none');

            $('#td1').prop('required', false);
            $('#td2').prop('required', false);

            /*
            if($(this).val() >= 2) {
                $('#td1').prop('required', true);
                $('#td2').prop('required', true);

                $('#td1_asterisk').removeClass('d-none');
                $('#td2_asterisk').removeClass('d-none');
            }
            */
        }).trigger('change');

        $('#visit1').on('change', function () {
            $('#visit1_div').toggleClass('d-none', !$(this).val());
            $('#visit1_type').prop('required', !!$(this).val());
            $('#visit1_bp').prop('required', !!$(this).val());
            $('#visit2').prop('min', $(this).val());
        }).trigger('change');

        $('#visit2').on('change', function () {
            $('#visit2_div').toggleClass('d-none', !$(this).val());
            $('#visit2_type').prop('required', !!$(this).val());
            $('#visit2_bp').prop('required', !!$(this).val());
            $('#visit3').prop('min', $(this).val());
        }).trigger('change');

        $('#visit3').on('change', function () {
            $('#visit3_div').toggleClass('d-none', !$(this).val());
            $('#visit3_type').prop('required', !!$(this).val());
            $('#visit3_bp').prop('required', !!$(this).val());
            $('#visit4').prop('min', $(this).val());
        }).trigger('change');

        $('#visit4').on('change', function () {
            $('#visit4_div').toggleClass('d-none', !$(this).val());
            $('#visit4_type').prop('required', !!$(this).val());
            $('#visit4_bp').prop('required', !!$(this).val());
            $('#visit5').prop('min', $(this).val());
        }).trigger('change');

        $('#visit5').on('change', function () {
            $('#visit5_div').toggleClass('d-none', !$(this).val());
            $('#visit5_type').prop('required', !!$(this).val());
            $('#visit5_bp').prop('required', !!$(this).val());
            $('#visit6').prop('min', $(this).val());
        }).trigger('change');

        $('#visit6').on('change', function () {
            $('#visit6_div').toggleClass('d-none', !$(this).val());
            $('#visit6_type').prop('required', !!$(this).val());
            $('#visit6_bp').prop('required', !!$(this).val());
            $('#visit7').prop('min', $(this).val());
        }).trigger('change');

        $('#visit7').on('change', function () {
            $('#visit7_div').toggleClass('d-none', !$(this).val());
            $('#visit7_type').prop('required', !!$(this).val());
            $('#visit7_bp').prop('required', !!$(this).val());
            $('#visit8').prop('min', $(this).val());
        }).trigger('change');

        $('#visit8').on('change', function () {
            $('#visit8_div').toggleClass('d-none', !$(this).val());
            $('#visit8_type').prop('required', !!$(this).val());
            $('#visit8_bp').prop('required', !!$(this).val());
        }).trigger('change');

        $('#with_dangersign').change(function (e) { 
            e.preventDefault();
            $('#dangersign_specify_div').toggleClass('d-none', $(this).val() !== '1');
            $('#with_dangersign_specify').prop('required', $(this).val() === '1');

            $('#dangersign_referred_div').toggleClass('d-none', $(this).val() !== '1');
            $('#dangersign_referred').prop('required', $(this).val() === '1');
        }).trigger('change');

        $('#dangersign_referred').change(function (e) { 
            e.preventDefault();
            $('#dangersign_datereferred_div').toggleClass('d-none', $(this).val() !== '1');
            $('#dangersign_datereferred').prop('required', $(this).val() === '1');
        }).trigger('change');

        $('#pnc_with_dangersign').change(function (e) { 
            e.preventDefault();

            if($('#pnc_div').data('status') === 'active') {
                $('#pnc_dangersign_specify_div').toggleClass('d-none', $(this).val() !== '1');
                $('#pnc_dangersign_specify').prop('required', $(this).val() === '1');

                $('#pnc_dangersign_referred_div').toggleClass('d-none', $(this).val() !== '1');
                $('#pnc_dangersign_referred').prop('required', $(this).val() === '1');
            }
        }).trigger('change');

        $('#pnc_dangersign_referred').change(function (e) { 
            e.preventDefault();

            if($('#pnc_div').data('status') === 'active') {
                $('#pnc_dangersign_referred_yes_div').toggleClass('d-none', $(this).val() !== '1');
                $('#pnc_dangersign_datereferred').prop('required', $(this).val() === '1');
            }
        }).trigger('change');

        $('#pnc1').on('change', function () {
            $('#pnc1_bp').prop('required', false);

            if ($('#pnc_div').data('status') === 'active') {
                $('#pnc1_bp_div').toggleClass('d-none', !$(this).val());
                $('#pnc1_bp').prop('required', !!$(this).val());
            }
        }).trigger('change');

        $('#pnc2').on('change', function () {
            $('#pnc2_bp').prop('required', false);

            if ($('#pnc_div').data('status') === 'active') {
                $('#pnc2_bp_div').toggleClass('d-none', !$(this).val());
                $('#pnc2_bp').prop('required', !!$(this).val());
            }
        }).trigger('change');

        $('#pnc3').on('change', function () {
            $('#pnc3_bp').prop('required', false);

            if ($('#pnc_div').data('status') === 'active') {
                $('#pnc3_bp_div').toggleClass('d-none', !$(this).val());
                $('#pnc3_bp').prop('required', !!$(this).val());
            }
        }).trigger('change');

        $('#pnc4').on('change', function () {
            $('#pnc4_bp').prop('required', false);

            if ($('#pnc_div').data('status') === 'active') {
                $('#pnc4_bp_div').toggleClass('d-none', !$(this).val());
                $('#pnc4_bp').prop('required', !!$(this).val());
            }
        }).trigger('change');

        $('#syphilis_result').change(function (e) { 
            e.preventDefault();
            
            $('#syph_confirmatory_div').toggleClass('d-none', $(this).val() !== '1');
        }).trigger('change');

        $('#syp_conf_date').change(function (e) { 
            e.preventDefault();
            $('#syp_conf_result').prop('required', !!$(this).val());
        });

        $('#syp_conf_result').change(function (e) { 
            e.preventDefault();
            
            $('#syph_conf_pos_div').toggleClass('d-none', $(this).val() !== '1');
            $('#syp_conf_treat').prop('required', $(this).val() === '1');
        }).trigger('change');

        $('#td1').on('change', function () {
            $('#td1_type').prop('required', !!$(this).val());
            $('#td2').prop('min', $(this).val());
        }).trigger('change');

        $('#td2').on('change', function () {
            $('#td2_type').prop('required', !!$(this).val());
            $('#td3').prop('min', $(this).val());
        }).trigger('change');

        $('#td3').on('change', function () {
            $('#td3_type').prop('required', !!$(this).val());
            $('#td4').prop('min', $(this).val());
        }).trigger('change');

        $('#td4').on('change', function () {
            $('#td4_type').prop('required', !!$(this).val());
            $('#td5').prop('min', $(this).val());
        }).trigger('change');

        $('#td5').on('change', function () {
            $('#td5_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#ifa1_date').on('change', function () {
            $('#ifa1_dosage').prop('required', !!$(this).val());
            $('#ifa1_type').prop('required', !!$(this).val());

            $('#ifa2_date').prop('min', $(this).val());
        }).trigger('change');

        $('#ifa2_date').on('change', function () {
            $('#ifa2_dosage').prop('required', !!$(this).val());
            $('#ifa2_type').prop('required', !!$(this).val());

            $('#ifa3_date').prop('min', $(this).val());
        }).trigger('change');

        $('#ifa3_date').on('change', function () {
            $('#ifa3_dosage').prop('required', !!$(this).val());
            $('#ifa3_type').prop('required', !!$(this).val());

            $('#ifa4_date').prop('min', $(this).val());
        }).trigger('change');

        $('#ifa4_date').on('change', function () {
            $('#ifa4_dosage').prop('required', !!$(this).val());
            $('#ifa4_type').prop('required', !!$(this).val());

            $('#ifa5_date').prop('min', $(this).val());
        }).trigger('change');

        $('#ifa5_date').on('change', function () {
            $('#ifa5_dosage').prop('required', !!$(this).val());
            $('#ifa5_type').prop('required', !!$(this).val());

            $('#ifa6_date').prop('min', $(this).val());
        }).trigger('change');

        $('#ifa6_date').on('change', function () {
            $('#ifa6_dosage').prop('required', !!$(this).val());
            $('#ifa6_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#mms1_date').on('change', function () {
            $('#mms1_dosage').prop('required', !!$(this).val());
            $('#mms1_type').prop('required', !!$(this).val());

            $('#mms2_date').prop('min', $(this).val());
        }).trigger('change');

        $('#mms2_date').on('change', function () {
            $('#mms2_dosage').prop('required', !!$(this).val());
            $('#mms2_type').prop('required', !!$(this).val());

            $('#mms3_date').prop('min', $(this).val());
        }).trigger('change');

        $('#mms3_date').on('change', function () {
            $('#mms3_dosage').prop('required', !!$(this).val());
            $('#mms3_type').prop('required', !!$(this).val());

            $('#mms4_date').prop('min', $(this).val());
        }).trigger('change');

        $('#mms4_date').on('change', function () {
            $('#mms4_dosage').prop('required', !!$(this).val());
            $('#mms4_type').prop('required', !!$(this).val());

            $('#mms5_date').prop('min', $(this).val());
        }).trigger('change');

        $('#mms5_date').on('change', function () {
            $('#mms5_dosage').prop('required', !!$(this).val());
            $('#mms5_type').prop('required', !!$(this).val());

            $('#mms6_date').prop('min', $(this).val());
        }).trigger('change');

        $('#mms6_date').on('change', function () {
            $('#mms6_dosage').prop('required', !!$(this).val());
            $('#mms6_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#calcium1_date').on('change', function () {
            if($('#highrisk').val() == 'Y') {
                $('#calcium1_dosage').prop('required', !!$(this).val());
                $('#calcium1_type').prop('required', !!$(this).val());

                $('#calcium2_date').prop('min', $(this).val());
            }
            else {
                $('#calcium1_dosage').prop('required', false);
                $('#calcium1_type').prop('required', false);
            }
        }).trigger('change');

        $('#calcium2_date').on('change', function () {
            if($('#highrisk').val() == 'Y') {
                $('#calcium2_dosage').prop('required', !!$(this).val());
                $('#calcium2_type').prop('required', !!$(this).val());

                $('#calcium3_date').prop('min', $(this).val());
            }
            else {
                $('#calcium2_dosage').prop('required', false);
                $('#calcium2_type').prop('required', false);
            }
        }).trigger('change');

        $('#calcium3_date').on('change', function () {
            if($('#highrisk').val() == 'Y') {
                $('#calcium3_dosage').prop('required', !!$(this).val());
                $('#calcium3_type').prop('required', !!$(this).val());
            }
            else {
                $('#calcium3_dosage').prop('required', false);
                $('#calcium3_type').prop('required', false);
            }
        }).trigger('change');

        $('#syphilis_date').on('change', function () {
            $('#syphilis_result').prop('required', !!$(this).val());
        }).trigger('change');

        $('#hiv_date').on('change', function () {
            $('#hiv_result').prop('required', !!$(this).val());
        }).trigger('change');

        $('#hb_date').on('change', function () {
            $('#hb_result').prop('required', !!$(this).val());
        }).trigger('change');

        $('#cbc_date').on('change', function () {
            $('#cbc_result').prop('required', !!$(this).val());
        }).trigger('change');

        $('#diabetes_date').on('change', function () {
            $('#diabetes_result').prop('required', !!$(this).val());
        }).trigger('change');

        $('#highrisk').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#highrisk_div').removeClass('d-none');
            }
            else {
                $('#highrisk_div').addClass('d-none');
            }
        }).trigger('change');

        $('#outcome').change(function (e) { 
            e.preventDefault();
            $('#delivery_div').addClass('d-none');
            $('#delivery_date').prop('required', false);
            $('#delivery_type').prop('required', false);
            $('#facility_type').prop('required', false);
            $('#attendant').prop('required', false);
            $('#birth_weight').prop('required', false);
            $('#number_livebirths').prop('required', false);

            $('.postnatal_div').hide();
            $('#pnc_div').data('status', 'inactive');

            if($(this).val() == 'FT' || $(this).val() == 'PT' || $(this).val() == 'FD' || $(this).val() == 'AB') {
                $('#delivery_div').removeClass('d-none');
                $('#delivery_date').prop('required', true);
                $('#delivery_type').prop('required', true);
                $('#facility_type').prop('required', true);
                $('#attendant').prop('required', true);
                $('#number_livebirths').prop('required', true);

                $('.postnatal_div').show();
                $('#pnc_div').data('status', 'active');
            }

            if($(this).val() == 'AB' || $(this).val() == 'FD') {
                $('#birth_weight').prop('required', false);
                $('#birth_weight_ast').addClass('d-none');
            }
            else if($(this).val() == 'FT' || $(this).val() == 'PT') {
                $('#birth_weight').prop('required', true);
                $('#birth_weight_ast').removeClass('d-none');
            }
        }).trigger('change');

        $('#facility_type').change(function (e) { 
            e.preventDefault();
            $('#nonhealth_div').hide();
            $('#publicprivate_div').hide();
            $('#nonhealth_type').prop('required', false);
            $('#place_of_delivery').prop('required', false);

            if($(this).val() == 'NON/HEALTH FACILITY') {
                $('#nonhealth_div').show();
                $('#nonhealth_type').prop('required', true);
            }
            else if($(this).val() == 'PUBLIC' || $(this).val() == 'PRIVATE') {
                $('#publicprivate_div').show();
                $('#place_of_delivery').prop('required', true);
            }
        }).trigger('change');

        $('#attendant').change(function (e) { 
            e.preventDefault();
            $('#otherattendant_div').hide();
            $('#attendant_others').prop('required', false);

            if($(this).val() == 'O') {
                $('#otherattendant_div').show();
                $('#attendant_others').prop('required', true);
            }
        }).trigger('change');

        $('#trans_remarks').change(function (e) { 
            e.preventDefault();
            $('#transout_div').addClass('d-none');
            $('#transout_date').prop('required', false);
            $('#outcome_div').addClass('d-none');
            $('#outcome').prop('required', false);

            if($(this).val() == 'B') {
                $('#transout_div').removeClass('d-none');
                $('#transout_date').prop('required', true);

                $('#outcome_div').addClass('d-none');
                $('#outcome').prop('required', false);
                $('#outcome').val('');
                $('#outcome').trigger('change');
            }
            else if($(this).val() == 'A' || $(this).val() == '') {
                $('#outcome_div').removeClass('d-none');
            }
        }).trigger('change');

        $('#pp_remarks').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'B') {
                $('#pp_transout_div').removeClass('d-none');
                $('#pp_transout_date').prop('required', true);
            }
            else {
                $('#pp_transout_div').addClass('d-none');
                $('#pp_transout_date').prop('required', false);
            }
        }).trigger('change');

        $('#lmp').on('change', function () {
            let lmp = new Date($(this).val());
            if (isNaN(lmp.getTime())) {
                $('#edd').val('');
                return;
            }
            let edd = new Date(lmp);
            edd.setDate(edd.getDate() + 280); // Add 280 days
            let month = '' + (edd.getMonth() + 1);
            let day = '' + edd.getDate();
            let year = edd.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            $('#edc').val([year, month, day].join('-'));
        });
    </script>
@endsection