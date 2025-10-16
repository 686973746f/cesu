@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('sbs_store', $s->qr)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>General Trias City CESU - School Based Disease Surveillance: New Case</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for=""><b class="text-danger">*</b>Name of School</label>
                              <input type="text" class="form-control" value="{{$s->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for=""><b class="text-danger">*</b>Date Reported</label>
                              <input type="date" class="form-control" name="date_reported" id="date_reported" value="{{old('date_reported', date('Y-m-d'))}}" min="{{date('Y-m-d', strtotime('-6 Months'))}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                <select class="form-control" name="sex" id="sex" required>
                                <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number"><b class="text-danger">*</b>Contact Number of Patient/Guardian</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="patient_type"><span class="text-danger font-weight-bold">*</span>Patient Type</label>
                                <select class="form-control" name="patient_type" id="patient_type" required>
                                    <option value="" disabled {{(is_null(old('patient_type'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="STUDENT" {{(old('patient_type') == 'STUDENT') ? 'selected' : ''}}>Student</option>
                                    <option value="TEACHER" {{(old('patient_type') == 'TEACHER') ? 'selected' : ''}}>Teacher</option>
                                    <option value="STAFF" {{(old('patient_type') == 'STAFF') ? 'selected' : ''}}>Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="if_student_div" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grade_level"><span class="text-danger font-weight-bold">*</span>Grade Level</label>
                                    <select class="form-control" name="grade_level" id="grade_level">
                                        <option value="" disabled {{(is_null(old('grade_level'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="G1" {{(old('grade_level') == 'G1') ? 'selected' : ''}}>Grade 1</option>
                                        <option value="G2" {{(old('grade_level') == 'G2') ? 'selected' : ''}}>Grade 2</option>
                                        <option value="G3" {{(old('grade_level') == 'G3') ? 'selected' : ''}}>Grade 3</option>
                                        <option value="G4" {{(old('grade_level') == 'G4') ? 'selected' : ''}}>Grade 4</option>
                                        <option value="G5" {{(old('grade_level') == 'G5') ? 'selected' : ''}}>Grade 5</option>
                                        <option value="G6" {{(old('grade_level') == 'G6') ? 'selected' : ''}}>Grade 6</option>
                                        <option value="G7" {{(old('grade_level') == 'G7') ? 'selected' : ''}}>Grade 7</option>
                                        <option value="G8" {{(old('grade_level') == 'G8') ? 'selected' : ''}}>Grade 8</option>
                                        <option value="G9" {{(old('grade_level') == 'G9') ? 'selected' : ''}}>Grade 9</option>
                                        <option value="G10" {{(old('grade_level') == 'G10') ? 'selected' : ''}}>Grade 10</option>
                                        <option value="JHS" {{(old('grade_level') == 'JHS') ? 'selected' : ''}}>Junior High School</option>
                                        <option value="SHS" {{(old('grade_level') == 'SHS') ? 'selected' : ''}}>Senior High School</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="section"><b class="text-danger">*</b>Section</label>
                                  <input type="text" class="form-control" name="section" id="section" value="{{old('section')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="if_teacherstaff_div" class="d-none">
                        <hr>
                        <div class="form-group">
                            <label for="staff_designation"><b class="text-danger">*</b>Position/Designation</label>
                            <input type="text" class="form-control" name="staff_designation" id="staff_designation" value="{{old('staff_designation')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_pwd"><span class="text-danger font-weight-bold">*</span>Person with Disability?</label>
                                <select class="form-control" name="is_pwd" id="is_pwd" required>
                                    <option value="" disabled {{(is_null(old('is_pwd'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pwd') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pwd') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd_condition"><b class="text-danger">*</b>If PWD, Specify Condition</label>
                                <input type="text" class="form-control" name="pwd_condition" id="pwd_condition" value="{{old('pwd_condition')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_region_code"><b class="text-danger">*</b>Region</label>
                                <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                                @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_province_code"><b class="text-danger">*</b>Province</label>
                                <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_muncity_code"><b class="text-danger">*</b>City/Municipality</label>
                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                                <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street_purok" class="form-label"><b class="text-danger">*</b>House/Lot No. and Street/Purok/Subdivision</label>
                        <input type="text" class="form-control" id="street_purok" name="street_purok" style="text-transform: uppercase;" value="{{old('street_purok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="onset_illness_date"><b class="text-danger">*</b>Date Onset of Illness (Kailan nagsimula ang sintomas)</label>
                              <input type="date" class="form-control" name="onset_illness_date" id="onset_illness_date" value="{{old('onset_illness_date')}}" min="{{date('Y-m-d', strtotime('-6 Months'))}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="signs_and_symptoms"><b class="text-danger">*</b>Signs and Symptoms (Select all that apply)</label>
                              <select class="form-control" name="signs_and_symptoms[]" id="signs_and_symptoms" required multiple>
                                <option value="FEVER" {{ (collect(old('signs_and_symptoms'))->contains('FEVER')) ? 'selected' : '' }}>Fever</option>
                                <option value="COUGH" {{ (collect(old('signs_and_symptoms'))->contains('FEVER')) ? 'selected' : '' }}>Fever</option>
                                <option value="COLDS" {{ (collect(old('signs_and_symptoms'))->contains('FEVER')) ? 'selected' : '' }}>Fever</option>
                                <option value="RASH" {{ (collect(old('signs_and_symptoms'))->contains('FEVER')) ? 'selected' : '' }}>Fever</option>
                                <option value="OTHERS" {{ (collect(old('signs_and_symptoms'))->contains('OTHERS')) ? 'selected' : '' }}>Others</option>
                              </select>
                            </div>
                            <div id="other_symptom_div" class="d-none">
                                <div class="form-group">
                                    <label for="signs_and_symptoms_others"><b class="text-danger">*</b>Specify other symptoms</label>
                                    <input type="text" class="form-control" name="signs_and_symptoms_others" id="signs_and_symptoms_others" value="{{old('signs_and_symptoms_others')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reported_by"><b class="text-danger">*</b>Reported By</label>
                                <input type="text" class="form-control" name="reported_by" id="reported_by" value="{{old('reported_by')}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reported_by_position"><b class="text-danger">*</b>Position/Designation of Reporter</label>
                                <input type="text" class="form-control" name="reported_by_position" id="reported_by_position" value="{{old('reported_by_position')}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reported_by_contactno"><b class="text-danger">*</b>Contact Number of Reporter</label>
                                <input type="text" class="form-control" id="reported_by_contactno" name="reported_by_contactno" value="{{old('reported_by_contactno')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code, #signs_and_symptoms').select2({
            theme: 'bootstrap',
        });

        //Default Values for Gentri
        var regionDefault = 1;
        var provinceDefault = 18;
        var cityDefault = 388;

        $('#address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', true);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_province_code').empty();
                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_province_code').empty();
                        $('#address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_province_code').empty();
            }
        }).trigger('change');

        $('#address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_muncity_code').empty();
                        $('#address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_muncity_code').empty();
            }
        });

        $('#address_muncity_code').change(function (e) { 
            e.preventDefault();

            var cityId = $(this).val();
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', false);

                $('#address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_brgy_code').empty();
                        $('#address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_brgy_code').empty();
            }
        });

        if ($('#address_region_code').val()) {
            $('#address_region_code').trigger('change'); // Automatically load provinces on page load
        }

        if (provinceDefault) {
            setTimeout(function() {
                $('#address_province_code').val(provinceDefault).trigger('change');
            }, 500); // Slight delay to ensure province is loaded
        }
        if (cityDefault) {
            setTimeout(function() {
                $('#address_muncity_code').val(cityDefault).trigger('change');
            }, 1000); // Slight delay to ensure city is loaded
        }

        $('#patient_type').change(function (e) { 
            e.preventDefault();

            $('#if_student_div').addClass('d-none');
            $('#grade_level').prop('required', false);
            $('#section').prop('required', false);
            $('#if_teacherstaff_div').addClass('d-none');
            $('#staff_designation').prop('required', false);

            if($(this).val() == 'STUDENT') {
                $('#if_student_div').removeClass('d-none');
                $('#grade_level').prop('required', true);
                $('#section').prop('required', true);
            }
            else if($(this).val() == 'TEACHER' || $(this).val() == 'STAFF') {
                $('#if_teacherstaff_div').removeClass('d-none');
                $('#staff_designation').prop('required', true);
            }
        }).trigger('change');

        $('#is_pwd').change(function (e) { 
            e.preventDefault();

            $('#pwd_condition').prop('disabled', true);
            $('#pwd_condition').prop('required', false);
            
            if($(this).val() == 'Y') {
                $('#pwd_condition').prop('disabled', false);
                $('#pwd_condition').prop('required', true);
            }
        }).trigger('change');

        $('#signs_and_symptoms').change(function (e) { 
            e.preventDefault();
            var selectedValues = $('#signs_and_symptoms').val();

            if (selectedValues && selectedValues.includes('OTHERS')) {
                $('#other_symptom_div').removeClass('d-none');
                $('#signs_and_symptoms_others').prop('required', true);
            } else {
                $('#other_symptom_div').addClass('d-none');
                $('#signs_and_symptoms_others').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection