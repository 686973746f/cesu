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
                    <div>
                        @if($mode == 'EDIT')
                        <a href="{{route('attendancesheet_create', $d->id)}}?month={{date('m')}}&year={{date('Y')}}" class="btn btn-primary">Create DTR</a>
                        @endif
                    </div>
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
                            <input type="text" class="form-control" name="profession_suffix" id="profession_suffix" value="{{old('profession_suffix', $d->profession_suffix)}}" minlength="2" maxlength="30" placeholder="RN/MD/RMT/RM/MPH, etc.">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prc_license_no">PRC License Number (If Applicable)</label>
                            <input type="text" class="form-control" name="prc_license_no" id="prc_license_no" value="{{old('prc_license_no', $d->prc_license_no)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tin_no">TIN Number</label>
                            <input type="text" class="form-control" name="tin_no" id="tin_no" value="{{old('tin_no', $d->tin_no)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="philhealth_pan">Philhealth Accreditation Number (PAN)</label>
                            <input type="text" class="form-control" name="philhealth_pan" id="philhealth_pan" value="{{old('philhealth_pan', $d->philhealth_pan)}}" pattern="[0-9]{12}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="weight_kg">Weight (in KG)</label>
                          <input type="number" class="form-control" name="weight_kg" id="weight_kg" min="30" max="900" step=".1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="height_cm">Height (in CM)</label>
                            <input type="number" class="form-control" name="height_cm" id="height_cm" min="70" max="500" step=".1">
                          </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="shirt_size">T-Shirt Size</label>
                            <select class="form-control" name="shirt_size" id="shirt_size">
                              <option value="" {{(is_null(old('shirt_size', $d->shirt_size))) ? 'selected' : ''}}>N/A</option>
                              <option value="S" {{(old('shirt_size', $d->shirt_size) == 'S') ? 'selected' : ''}}>Small</option>
                              <option value="M" {{(old('shirt_size', $d->shirt_size) == 'M') ? 'selected' : ''}}>Medium</option>
                              <option value="L" {{(old('shirt_size', $d->shirt_size) == 'L') ? 'selected' : ''}}>Large</option>
                              <option value="XL" {{(old('shirt_size', $d->shirt_size) == 'XL') ? 'selected' : ''}}>XL</option>
                              <option value="XXL" {{(old('shirt_size', $d->shirt_size) == 'XXL') ? 'selected' : ''}}>XXL</option>
                              <option value="XXXL" {{(old('shirt_size', $d->shirt_size) == 'XXXL') ? 'selected' : ''}}>XXXL</option>
                              <option value="4XL" {{(old('shirt_size', $d->shirt_size) == '4XL') ? 'selected' : ''}}>4XL</option>
                              <option value="5XL" {{(old('shirt_size', $d->shirt_size) == '5XL') ? 'selected' : ''}}>5XL</option>
                            </select>
                          </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_blstrained"><b class="text-danger">*</b>Is BLS Trained?</label>
                            <select class="form-control" name="is_blstrained" id="is_blstrained" required>
                              <option value="N" {{(old('is_blstrained', $d->is_blstrained) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('is_blstrained', $d->is_blstrained) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bls_typeofrescuer"><b class="text-danger">*</b>Type of Rescuer</label>
                            <select class="form-control" name="bls_typeofrescuer" id="bls_typeofrescuer" required>
                              <option value="" disabled {{(is_null(old('bls_typeofrescuer', $d->bls_typeofrescuer))) ? 'selected' : ''}}>Choose...</option>
                              <option value="LR" {{(old('bls_typeofrescuer', $d->bls_typeofrescuer) == 'LR') ? 'selected' : ''}}>Lay Rescuer (LR)</option>
                              <option value="HCP" {{(old('bls_typeofrescuer', $d->bls_typeofrescuer) == 'HCP') ? 'selected' : ''}}>Health Care Provider (HCP)</option>
                            </select>
                        </div>
                        <div id="ifBlsTrainedDiv" class="d-none">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_herotrained"><b class="text-danger">*</b>Is HERO Trained?</label>
                            <select class="form-control" name="is_herotrained" id="is_herotrained" required>
                              <option value="N" {{(old('is_herotrained', $d->is_herotrained) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('is_herotrained', $d->is_herotrained) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_washntrained"><b class="text-danger">*</b>Is WASH-N Trained?</label>
                            <select class="form-control" name="is_washntrained" id="is_washntrained" required>
                              <option value="N" {{(old('is_washntrained', $d->is_washntrained) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('is_washntrained', $d->is_washntrained) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_nutriemergtrained"><b class="text-danger">*</b>Is Nutrition in Emergencies Trained?</label>
                            <select class="form-control" name="is_nutriemergtrained" id="is_nutriemergtrained" required>
                              <option value="N" {{(old('is_nutriemergtrained', $d->is_nutriemergtrained) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('is_nutriemergtrained', $d->is_nutriemergtrained) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="duty_canbedeployed"><b class="text-danger">*</b>Deployable in Duties?</label>
                            <select class="form-control" name="duty_canbedeployed" id="duty_canbedeployed" required>
                              <option value="" disabled {{(is_null(old('duty_canbedeployed', $d->duty_canbedeployed))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('duty_canbedeployed', $d->duty_canbedeployed) == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('duty_canbedeployed', $d->duty_canbedeployed) == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="duty_canbedeployedagain"><b class="text-danger">*</b>Can be Repeatedly Deployed? (For Drivers, Team Leaders)</label>
                            <select class="form-control" name="duty_canbedeployedagain" id="duty_canbedeployedagain" required>
                              <option value="N" {{(old('duty_canbedeployedagain', $d->duty_canbedeployedagain) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('duty_canbedeployedagain', $d->duty_canbedeployedagain) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="abtc_vaccinator_branch">ABTC Vaccination Branch</label>
                            <select class="form-control" name="abtc_vaccinator_branch" id="abtc_vaccinator_branch">
                              <option value="" {{(is_null(old('abtc_vaccinator_branch', $d->abtc_vaccinator_branch))) ? 'selected' : ''}}>N/A</option>
                              @foreach($atbc_branch_list as $a)
                              <option value="{{$a->id}}" {{(old('abtc_vaccinator_branch', $d->abtc_vaccinator_branch) == $a->id) ? 'selected' : ''}}>{{$a->site_name}}</option>
                              @endforeach
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
    </form>

    @if($mode == 'EDIT')
    <div class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Employment Status</b></div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#updateEmployeeStatus">Update Employment Status</button></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Effectivity Date</th>
                        <th>Status</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Office</th>
                        <th>Sub-Office</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($employmentupdate_list as $eu)
                    <tr>
                        <td>{{ date('m/d/Y', strtotime($eu->effective_date)) }}</td>
                        <td>{{ $eu->update_type }}</td>
                        <td>{{ $eu->job_position }}</td>
                        <td>{{ $eu->job_type }}</td>
                        <td>{{ $eu->office }}</td>
                        <td>{{ $eu->sub_office ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    
    <form action="{{route('employees_update_employmentstatus', $d->id)}}" method="POST">
        @csrf
        <input type="hidden" name="request_uuid" value="{{ Str::uuid() }}">
        <div class="modal fade" id="updateEmployeeStatus" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Employment Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="update_type">Select Type</label>
                          <select class="form-control" name="update_type" id="update_type" required>
                            <option value="" disabled {{(is_null(old('update_type'))) ? 'selected' : ''}}>Choose...</option>
                            @if($d->employeestatus->where('update_type', 'INITIAL')->isEmpty())
                            <option value="INITIAL">Initial</option>
                            @endif
                            <option value="CHANGE">Change</option>
                            <option value="PROMOTION">Promotion</option>
                            <option value="RESIGNED">Resigned</option>
                            <option value="RETIRED">Retired</option>
                            <option value="END OF CONTRACT">End of Contract</option>
                            <option value="TERMINATED">Terminated</option>
                          </select>
                        </div>
                        <div id="emp_part2" class="d-none">
                            <div class="form-group">
                                <label for="effective_date"><b class="text-danger">*</b>Date of Effectivity</label>
                                <input type="date" class="form-control" name="effective_date" id="effective_date" value="{{old('effective_date')}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group" id="resigned_div">
                              <label for="up_resigned_remarks">Resigned Remarks</label>
                              <textarea class="form-control" name="up_resigned_remarks" id="up_resigned_remarks" rows="3">{{old('up_resigned_remarks')}}</textarea>
                            </div>
                            <div class="form-group" id="terminated_div">
                                <label for="up_terminated_remarks">Terminated Remarks</label>
                                <textarea class="form-control" name="up_terminated_remarks" id="up_terminated_remarks" rows="3">{{old('up_terminated_remarks')}}</textarea>
                            </div>
                            <div id="changeorpromote_div" class="d-none">
                                <div class="form-group">
                                    <label for="up_job_type"><b class="text-danger">*</b>Employee Type</label>
                                    <select class="form-control" name="up_job_type" id="up_job_type">
                                      <option value="" disabled {{(is_null(old('up_job_type'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="JOB ORDER" {{(old('up_job_type') == 'JOB ORDER') ? 'selected' : ''}}>Job Order (J.O)</option>
                                      <option value="CASUAL" {{(old('up_job_type') == 'CASUAL') ? 'selected' : ''}}>Casual</option>
                                      <option value="REGULAR" {{(old('up_job_type') == 'REGULAR') ? 'selected' : ''}}>Regular</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="up_job_position"><b class="text-danger">*</b>Position</label>
                                    <input type="text" class="form-control" name="up_job_position" id="up_job_position" value="{{old('up_job_position')}}" style="text-transform: uppercase;" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="up_office"><b class="text-danger">*</b>Office</label>
                                    <select class="form-control" name="up_office" id="up_office">
                                      <option value="" disabled {{(is_null(old('up_office'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="CHO MAIN" {{(old('up_office') == 'CHO MAIN') ? 'selected' : ''}}>CHO Main</option>
                                      <option value="MANGGAHAN HEALTH CENTER" {{(old('up_office') == 'MANGGAHAN HEALTH CENTER') ? 'selected' : ''}}>Manggahan Health Center</option>
                                      <option value="SAN FRANCISCO SUPER HEALTH CENTER" {{(old('up_office') == 'SAN FRANCISCO SUPER HEALTH CENTER') ? 'selected' : ''}}>General Trias Super Health Center (San Francisco)</option>
                                      <option value="GENERAL TRIAS MEDICARE HOSPITAL" {{(old('up_office') == 'GENERAL TRIAS MEDICARE HOSPITAL') ? 'selected' : ''}}>General Trias Medicare Hospital</option>
                                      <option value="DOH-HRH" {{(old('up_office') == 'DOH-HRH') ? 'selected' : ''}}>DOH-HRH</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="up_sub_office">Sub-Office</label>
                                    <input type="text" class="form-control" name="up_sub_office" id="up_sub_office" value="{{old('up_sub_office')}}" style="text-transform: uppercase;" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    @endif

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

        $('#emp_part2').addClass('d-none');
        $('#effective_date').prop('required', false);

        $('#update_type').change(function (e) { 
            e.preventDefault();
            $('#emp_part2').removeClass('d-none');
            $('#effective_date').prop('required', true);

            $('#resigned_div').addClass('d-none');
            $('#terminated_div').addClass('d-none');
            
            $('#changeorpromote_div').addClass('d-none');
            $('#up_job_type').prop('required', false);
            $('#up_job_position').prop('required', false);
            $('#up_office').prop('required', false);

            if($(this).val() == 'INITIAL' || $(this).val() == 'CHANGE' || $(this).val() == 'PROMOTION') {
                $('#changeorpromote_div').removeClass('d-none');
                $('#up_job_type').prop('required', true);
                $('#up_job_position').prop('required', true);
                $('#up_office').prop('required', true);
            }
            else if($(this).val() == 'RESIGNED') {
                $('#resigned_div').removeClass('d-none');
            }
            else if($(this).val() == 'TERMINATED') {
                $('#terminated_div').removeClass('d-none');
            }
        }).trigger('change');

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
            }
            else {
                $('#ifBlsTrainedDiv').addClass('d-none');
            }
        }).trigger('change');
    </script>
@endsection