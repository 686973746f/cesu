@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('bls_updatemember', $d->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>{{$d->batch->batch_name}}</b></div>
                        <div></div>
                    </div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="cho_employee" id="cho_employee" value="1" {{($d->cho_employee == 'Y') ? 'checked' : ''}}>
                        Is CHO Employee?
                    </label>
                </div>
                <div id="cho_employee_fields" class="d-none">
                    <div class="form-group mt-2">
                    <label for="employee_id"><b class="text-danger">*</b>Link to Employee ID</label>
                    <select class="form-control" name="employee_id" id="employee_id">
                    </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Surname</label>
                            <input type="text" class="form-control" name="lname" id="lname" style="text-transform: uppercase" value="{{old('lname', $d->lname)}}" minlength="2" max="50" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" style="text-transform: uppercase" value="{{old('fname', $d->fname)}}" minlength="2" max="50" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" style="text-transform: uppercase" value="{{old('mname' , $d->mname)}}" minlength="2" max="50">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="suffix">Name Extension (Jr./Sr./III/IV, etc.)</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" style="text-transform: uppercase" value="{{old('suffix', $d->suffix)}}" minlength="2" max="5">
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Date of Birth</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" max="{{date('Y-m-d')}}" value="{{old('bdate', $d->bdate)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="provider_type"><b class="text-danger">*</b>Provider Type</label>
                            <select class="form-control" name="provider_type" id="provider_type" required>
                            <option value="HCP" {{(old('provider_type', $d->provider_type) == 'HCP') ? 'selected' : ''}}>Health Care Provider (HCP)</option>
                            <option value="LR" {{(old('provider_type', $d->provider_type) == 'LR') ? 'selected' : ''}}>Lay Rescuer (LR)</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6">
                        <div id="institution_fields">
                            <div class="form-group mt-2">
                                <label for="institution"><b class="text-danger">*</b>Institution/Agency</label>
                                <select class="form-control" name="institution" id="institution" required>
                                    <option value="" disabled {{(is_null(old('provider_type'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach($list_institutions as $a)
                                    <option value="{{$a}}" {{(old('institution', $d->institution) == $a) ? 'selected' : ''}}>{{$a}}</option>
                                    @endforeach
                                    <option value="UNLISTED" {{(old('institution', $d->institution) == 'UNLISTED') ? 'selected' : ''}}>UNLISTED</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="institution_other_fields">
                                <label for="institution_other"><b class="text-danger">*</b>Please Specify</label>
                                <input type="text" class="form-control" name="institution_other" id="institution_other" style="text-transform: uppercase" value="{{old('institution_other')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_type"><b class="text-danger">*</b>Status of Employment</label>
                            <select class="form-control" name="employee_type" id="employee_type" required>
                            <option value="JO" {{(old('employee_type', $d->employee_type) == 'JO') ? 'selected' : ''}}>Job Order (JO)</option>
                            <option value="CASUAL" {{(old('employee_type', $d->employee_type) == 'CASUAL') ? 'selected' : ''}}>Casual</option>
                            <option value="CWA" {{(old('employee_type', $d->employee_type) == 'CWA') ? 'selected' : ''}}>Contract of Service (CWA)</option>
                            <option value="PERMANENT" {{(old('employee_type', $d->employee_type) == 'PERMANENT') ? 'selected' : ''}}>Permanent</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="position"><b class="text-danger">*</b>Position</label>
                    <input type="text" class="form-control" name="position" id="position" style="text-transform: uppercase" value="{{old('position', $d->position)}}" required>
                </div>
                
                
                <hr>
                <div class="row" id="address_fields">
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
                    <label for="street_purok"><b class="text-danger">*</b>Street/Purok/Sitio/Subdivision</label>
                    <input type="text" class="form-control" name="street_purok" id="street_purok" style="text-transform: uppercase" value="{{old('street_purok', $d->street_purok)}}" required>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email"><b class="text-danger">*</b>Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->email)}}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="codename"><b class="text-danger">*</b>Code Name</label>
                            <input type="text" class="form-control" name="codename" id="codename" style="text-transform: uppercase" value="{{old('codename', $d->codename)}}" required>
                        </div>
                    </div>
                </div>

                @if($d->batch->is_refresher != 'Y')
                <div class="card">
                    <div class="card-header"><b>Standard First Aid (SFA)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="sfa_pretest">Pre-Test</label>
                                  <input type="number" class="form-control" name="sfa_pretest" id="sfa_pretest" min="0" max="25" value="{{old('sfa_pretest', $d->sfa_pretest)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sfa_posttest">Post Test</label>
                                    <input type="number" class="form-control" name="sfa_posttest" id="sfa_posttest" min="0" max="25" value="{{old('sfa_posttest', $d->sfa_posttest)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sfa_remedial">Remedial</label>
                                    <input type="number" class="form-control" name="sfa_remedial" id="sfa_remedial" min="0" max="25" value="{{old('sfa_remedial', $d->sfa_remedial)}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="sfa_ispassed"><b class="text-danger">*</b>SFA - Pass or Fail</label>
                          <select class="form-control" name="sfa_ispassed" id="sfa_ispassed" required>
                            <option value="W" {{(old('sfa_ispassed', $d->sfa_ispassed) == 'W') ? 'selected' : ''}}>Pending</option>
                            <option value="P" {{(old('sfa_ispassed', $d->sfa_ispassed) == 'P') ? 'selected' : ''}}>Passed</option>
                            <option value="F" {{(old('sfa_ispassed', $d->sfa_ispassed) == 'F') ? 'selected' : ''}}>Failed</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="sfa_notes">Remarks</label>
                          <textarea class="form-control" name="sfa_notes" id="sfa_notes" rows="3">{{old('sfa_notes', $d->sfa_notes)}}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card mt-3">
                    <div class="card-header"><b>Basic Life Support (BLS)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="bls_pretest">Pre-Test</label>
                                  <input type="number" class="form-control" name="bls_pretest" id="bls_pretest" min="0" max="25" value="{{old('bls_pretest', $d->bls_pretest)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bls_posttest">Post Test</label>
                                    <input type="number" class="form-control" name="bls_posttest" id="bls_posttest" min="0" max="25" value="{{old('bls_posttest', $d->bls_posttest)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bls_remedial">Remedial</label>
                                    <input type="number" class="form-control" name="bls_remedial" id="bls_remedial" min="0" max="25" value="{{old('bls_remedial', $d->bls_remedial)}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="bls_cognitive_ispassed"><b class="text-danger">*</b>BLS Cognitive - Pass or Fail (30%)</label>
                          <select class="form-control" name="bls_cognitive_ispassed" id="bls_cognitive_ispassed" required>
                            <option value="W" {{(old('bls_cognitive_ispassed', $d->bls_cognitive_ispassed) == 'W') ? 'selected' : ''}}>Pending</option>
                            <option value="P" {{(old('bls_cognitive_ispassed', $d->bls_cognitive_ispassed) == 'P') ? 'selected' : ''}}>Passed</option>
                            <option value="F" {{(old('bls_cognitive_ispassed', $d->bls_cognitive_ispassed) == 'F') ? 'selected' : ''}}>Failed</option>
                          </select>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bls_cpr_adult">CPR ADULT</label>
                                    <input type="number" class="form-control" name="bls_cpr_adult" id="bls_cpr_adult" value="{{old('bls_cpr_adult', $d->bls_cpr_adult)}}">
                                </div>
                                <div class="form-group">
                                    <label for="bls_cpr_infant">CPR INFANT</label>
                                    <input type="number" class="form-control" name="bls_cpr_infant" id="bls_cpr_infant" value="{{old('bls_cpr_infant', $d->bls_cpr_infant)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bls_fbao_adult">FBAO ADULT</label>
                                    <input type="number" class="form-control" name="bls_fbao_adult" id="bls_fbao_adult" value="{{old('bls_fbao_adult', $d->bls_fbao_adult)}}">
                                </div>
                                <div class="form-group">
                                    <label for="bls_fbao_infant">FBAO INFANT</label>
                                    <input type="number" class="form-control" name="bls_fbao_infant" id="bls_fbao_infant" value="{{old('bls_fbao_infant', $d->bls_fbao_infant)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bls_rb_adult">RB ADULT</label>
                                    <input type="number" class="form-control" name="bls_rb_adult" id="bls_rb_adult" value="{{old('bls_rb_adult', $d->bls_rb_adult)}}">
                                </div>
                                <div class="form-group">
                                    <label for="bls_rb_infant">RB INFANT</label>
                                    <input type="number" class="form-control" name="bls_rb_infant" id="bls_rb_infant" value="{{old('bls_rb_infant', $d->bls_rb_infant)}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bls_psychomotor_ispassed"><b class="text-danger">*</b>BLS Psychomotor - Pass or Fail (60%)</label>
                            <select class="form-control" name="bls_psychomotor_ispassed" id="bls_psychomotor_ispassed" required>
                              <option value="W" {{(old('bls_psychomotor_ispassed', $d->bls_cognitive_ispassed) == 'W') ? 'selected' : ''}}>Pending</option>
                              <option value="P" {{(old('bls_psychomotor_ispassed', $d->bls_cognitive_ispassed) == 'P') ? 'selected' : ''}}>Passed</option>
                              <option value="F" {{(old('bls_psychomotor_ispassed', $d->bls_cognitive_ispassed) == 'F') ? 'selected' : ''}}>Failed</option>
                            </select>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bls_affective">Affective (10%)</label>
                                    <input type="number" class="form-control" name="bls_affective" id="bls_affective" value="{{old('bls_affective', $d->bls_affective)}}" min="0" max="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bls_finalremarks"><b class="text-danger">*</b>Final Remarks</label>
                                    <select class="form-control" name="bls_finalremarks" id="bls_finalremarks" required>
                                      <option value="W" {{(old('bls_finalremarks', $d->bls_finalremarks) == 'W') ? 'selected' : ''}}>Pending</option>
                                      <option value="P" {{(old('bls_finalremarks', $d->bls_finalremarks) == 'P') ? 'selected' : ''}}>Passed</option>
                                      <option value="F" {{(old('bls_finalremarks', $d->bls_finalremarks) == 'F') ? 'selected' : ''}}>Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bls_notes">Remarks</label>
                            <textarea class="form-control" name="bls_notes" id="bls_notes" rows="3">{{old('bls_notes', $d->bls_notes)}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bls_id_number">BLS ID Number</label>
                            <input type="text" class="form-control" name="bls_id_number" id="bls_id_number" style="text-transform: uppercase" value="{{old('bls_id_number', $d->bls_id_number)}}">
                        </div>
                        <div class="form-group">
                            <label for="bls_expiration_date">BLS Expiration Date</label>
                            <input type="date" class="form-control" name="bls_expiration_date" id="bls_expiration_date" value="{{old('bls_expiration_date', $d->bls_expiration_date)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sfa_id_number">SFA ID Number</label>
                            <input type="text" class="form-control" name="sfa_id_number" id="sfa_id_number" style="text-transform: uppercase" value="{{old('sfa_id_number', $d->sfa_id_number)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if(!is_null($d->picture))
                        <img src="{{asset('assets/bls/members/'.$d->picture)}}" class="img-fluid mb-3">
                        @endif
                        <div class="form-group">
                            <label for="picture">Upload/Update Picture</label>
                            <input type="file" class="form-control-file" name="picture" id="picture" accept="image/*">
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn">Update (CTRL + S)</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).bind('keydown', function(e) {
		if(e.ctrlKey && (e.which == 83)) {
			e.preventDefault();
			$('#submitbtn').trigger('click');
			$('#submitbtn').prop('disabled', true);
			setTimeout(function() {
				$('#submitbtn').prop('disabled', false);
			}, 2000);
			return false;
		}
	});

    $('#institution').select2({
        theme: 'bootstrap',
        dropdownParent: $('#institution_fields'),
    });

    $('#institution').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'UNLISTED') {
            $('#institution_other_fields').removeClass('d-none');
            $('#institution_other').prop('required', true);
        } else {
            $('#institution_other_fields').addClass('d-none');
            $('#institution_other').prop('required', false);
        }
    }).trigger('change');

    $('#employee_id').select2({
        dropdownParent: $('#cho_employee_fields'),
        theme: "bootstrap",
        placeholder: 'Search by Name / Employee ID ...',
        ajax: {
            url: "{{route('bls_ajax_listemployees')}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code').select2({
        theme: 'bootstrap',
        dropdownParent: $('#address_fields'),
    });

    //Default Values for Gentri
    var regionDefault = {{$d->brgy->city->province->region->id}};
    var provinceDefault = {{$d->brgy->city->province->id}};
    var cityDefault = {{$d->brgy->city->id}};
    var brgyDefault = {{$d->address_brgy_code}};

    $('#address_region_code').change(function (e) { 
        e.preventDefault();

        var regionId = $(this).val();

        if (regionId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', true);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_province_code').empty();
            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/province/' + regionId,
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
    });

    $('#address_province_code').change(function (e) { 
        e.preventDefault();

        var provinceId = $(this).val();

        if (provinceId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/city/' + provinceId,
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

        if (cityId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', false);

            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/brgy/' + cityId,
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
    if (brgyDefault) {
        setTimeout(function() {
            $('#address_brgy_code').val(brgyDefault).trigger('change');
        }, 1500); // Slight delay to ensure city is loaded
    }

    $('#cho_employee').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#cho_employee_fields').removeClass('d-none');
            $('#employee_id').prop('required', true);
        } else {
            $('#cho_employee_fields').addClass('d-none');
            $('#employee_id').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection