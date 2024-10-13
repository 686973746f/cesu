@extends('layouts.app')

@section('content')
    @if($mode == 'EDIT')
    <form action="{{route('employees_update', $d->id)}}" method="POST">
    @else
    <form action="{{route('employees_store')}}" method="POST">
    @endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Employee</b></div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $d->lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $d->fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $d->mname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $d->suffix)}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="profession_suffix">Profession</label>
                            <input type="text" class="form-control" name="profession_suffix" id="profession_suffix" value="{{old('profession_suffix', $d->profession_suffix)}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" placeholder="RN/MD/RMT/RM, etc.">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" disabled {{(is_null(old('gender', $d->gender))) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('gender', $d->gender) == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{(old('gender' , $d->gender) == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bdate">Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $d->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prc_license_no">PRC License Number (If Applicable)</label>
                            <input type="text" class="form-control" name="prc_license_no" id="prc_license_no" value="{{old('prc_license_no', $d->prc_license_no)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tin_no">TIN Number</label>
                            <input type="text" class="form-control" name="tin_no" id="tin_no" value="{{old('tin_no', $d->tin_no)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="type"><b class="text-danger">*</b>Employee Type</label>
                          <select class="form-control" name="type" id="type" required>
                            <option value="" disabled {{(is_null(old('type', $d->type))) ? 'selected' : ''}}>Choose...</option>
                            <option value="JOB ORDER" {{(old('type', $d->type) == 'JOB ORDER') ? 'selected' : ''}}>Job Order (J.O)</option>
                            <option value="CASUAL" {{(old('type', $d->type) == 'CASUAL') ? 'selected' : ''}}>Casual</option>
                            <option value="REGULAR" {{(old('type', $d->type) == 'REGULAR') ? 'selected' : ''}}>Regular</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_hired">Date Hired</label>
                            <input type="date" class="form-control" name="date_hired" id="date_hired" value="{{old('date_hired', $d->date_hired)}}" max="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="job_position"><b class="text-danger">*</b>Position</label>
                            <input type="text" class="form-control" name="job_position" id="job_position" value="{{old('job_position', $d->job_position)}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="office"><b class="text-danger">*</b>Office</label>
                            <select class="form-control" name="office" id="office" required>
                              <option value="" disabled {{(is_null(old('office', $d->office))) ? 'selected' : ''}}>Choose...</option>
                              <option value="CHO MAIN" {{(old('type', $d->office) == 'CHO MAIN') ? 'selected' : ''}}>CHO Main</option>
                              <option value="MANGGAHAN HEALTH CENTER" {{(old('office', $d->type) == 'MANGGAHAN HEALTH CENTER') ? 'selected' : ''}}>Manggahan Health Center</option>
                              <option value="SAN FRANCISCO SUPER HEALTH CENTER" {{(old('type', $d->office) == 'SAN FRANCISCO SUPER HEALTH CENTER') ? 'selected' : ''}}>San Francisco Super Health Center</option>
                              <option value="GENERAL TRIAS MEDICARE HOSPITAL" {{(old('type', $d->office) == 'GENERAL TRIAS MEDICARE HOSPITAL') ? 'selected' : ''}}>General Trias Medicare Hospital</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sub_office">Sub-Office</label>
                            <input type="text" class="form-control" name="sub_office" id="sub_office" value="{{old('sub_office', $d->sub_office)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employment_status"><b class="text-danger">*</b>Employment Status</label>
                            <select class="form-control" name="employment_status" id="employment_status" required>
                              <option value="" disabled {{(is_null(old('employment_status', $d->employment_status))) ? 'selected' : ''}}>Choose...</option>
                              <option value="ACTIVE" {{(old('employment_status', $d->employment_status) == 'ACTIVE') ? 'selected' : ''}}>Active</option>
                              <option value="RESIGNED" {{(old('employment_status', $d->employment_status) == 'RESIGNED') ? 'selected' : ''}}>Resigned</option>
                              <option value="RETIRED" {{(old('employment_status', $d->employment_status) == 'RETIRED') ? 'selected' : ''}}>Retired</option>
                            </select>
                        </div>
                        <div id="ifResignedRetiredDiv" class="d-none">
                            <div class="form-group">
                                <label for="date_resigned"><b class="text-danger">*</b>Date</label>
                                <input type="date" class="form-control" name="date_resigned" id="date_resigned" value="{{old('date_resigned', $d->date_resigned)}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="is_blstrained"><b class="text-danger">*</b>Is BLS Trained?</label>
                            <select class="form-control" name="is_blstrained" id="is_blstrained" required>
                              <option value="" disabled {{(is_null(old('is_blstrained', $d->is_blstrained))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('is_blstrained', $d->is_blstrained) == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('is_blstrained', $d->is_blstrained) == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="ifBlsTrainedDiv" class="d-none">
                            <div class="form-group">
                                <label for="bls_typeofrescuer"><b class="text-danger">*</b>Type of Rescuer</label>
                                <select class="form-control" name="bls_typeofrescuer" id="bls_typeofrescuer">
                                  <option value="" disabled {{(is_null(old('bls_typeofrescuer', $d->bls_typeofrescuer))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="LR" {{(old('bls_typeofrescuer', $d->bls_typeofrescuer) == 'LR') ? 'selected' : ''}}>Lay Rescuer</option>
                                  <option value="HCP" {{(old('bls_typeofrescuer', $d->bls_typeofrescuer) == 'HCP') ? 'selected' : ''}}>Health Care Provider</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recent_bls_date">Recent BLS/SFA Training Date</label>
                                <input type="date" class="form-control" name="recent_bls_date" id="recent_bls_date" value="{{old('recent_bls_date', $d->recent_bls_date)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="bls_id">BLS ID No.</label>
                                <input type="text" class="form-control" name="bls_id" id="bls_id" value="{{old('bls_id', $d->bls_id)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="duty_canbedeployed"><b class="text-danger">*</b>Deployable in Duties?</label>
                            <select class="form-control" name="duty_canbedeployed" id="duty_canbedeployed" required>
                              <option value="" disabled {{(is_null(old('duty_canbedeployed', $d->duty_canbedeployed))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('duty_canbedeployed', $d->duty_canbedeployed) == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('duty_canbedeployed', $d->duty_canbedeployed) == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="duty_team">HERT Duty Team</label>
                            <select class="form-control" name="duty_team" id="duty_team">
                              <option value="" {{(is_null(old('duty_team', $d->duty_team))) ? 'selected' : ''}}>N/A</option>
                              <option value="A" {{(old('duty_team', $d->duty_team) == 'A') ? 'selected' : ''}}>Team A</option>
                              <option value="B" {{(old('duty_team', $d->duty_team) == 'B') ? 'selected' : ''}}>Team B</option>
                              <option value="C" {{(old('duty_team', $d->duty_team) == 'C') ? 'selected' : ''}}>Team C</option>
                              <option value="D" {{(old('duty_team', $d->duty_team) == 'D') ? 'selected' : ''}}>Team D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" value="{{old('remarks', $d->remarks)}}" style="text-transform: uppercase;">
                </div>
                <div class="form-group">
                  <label for="emp_access_list">Employee Access List</label>
                  <select class="form-control" name="emp_access_list[]" id="emp_access_list" multiple>
                    @foreach($emp_access_list as $e)
                    <option value="{{$e}}" {{(in_array($e, explode(',', $d->emp_access_list))) ? 'selected' : ''}}>{{$e}}</option>
                    @endforeach
                  </select>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">{{($mode == 'EDIT') ? 'Update' : 'Save'}} (CTRL + S)</button>
            </div>
        </div>
    </div>
    </form>

    <script>
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

        $('#emp_access_list').select2({
            theme: 'bootstrap',
        });

        $('#employment_status').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'RESIGNED' || $(this).val() == 'RETIRED') {
                $('#ifResignedRetiredDiv').removeClass('d-none');
                $('#date_resigned').prop('required', true);
            }
            else {
                $('#ifResignedRetiredDiv').addClass('d-none');
                $('#date_resigned').prop('required', false);
            }
        }).trigger('change');

        $('#is_blstrained').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#ifBlsTrainedDiv').removeClass('d-none');
                $('#bls_typeofrescuer').prop('required', true);
            }
            else {
                $('#ifBlsTrainedDiv').addClass('d-none');
                $('#bls_typeofrescuer').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection