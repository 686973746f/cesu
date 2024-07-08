@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<form action="{{route('abtc_patient_store')}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><strong><i class="fa-solid fa-circle-plus me-2"></i>Add Patient</strong></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="alert alert-info" role="alert">
                    Note: All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="lname" class="form-label"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" maxlength="50" placeholder="e.g DELA CRUZ" style="text-transform: uppercase;" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="fname" class="form-label"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" maxlength="50" placeholder="e.g JUAN" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mname" class="form-label"><b class="text-danger">*</b>Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" placeholder="e.g SANCHEZ" style="text-transform: uppercase;" maxlength="50" required>
                            <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="suffix"><b class="text-danger">*</b>Suffix</label>
                            <select class="form-select" name="suffix" id="suffix" required>
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
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 d-none">
                          <label for="has_bday" class="form-label"><span class="text-danger font-weight-bold">*</span>Has Birthday</label>
                          <select class="form-select" name="has_bday" id="has_bday" required>
                            <option value="Yes" {{(old('has_bday') == 'Yes') ? 'selected' : ''}}>Yes</option>
                          </select>
                        </div>
                        <div class="mb-3 d-none" id="ybday">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>  
                        </div>
                        <div class="mb-3 d-none" id="nbday">
                            <label for="age" class="form-label"><b class="text-danger">*</b>Age (In Years)</label>
                            <input type="number" class="form-control" name="age" id="age" value="{{old('age')}}" min="0" max="150">  
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="gender" class="form-label"><span class="text-danger font-weight-bold">*</span>Gender</label>
                            <select class="form-select" name="gender" id="gender" required>
                                <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label"><b class="text-danger">*</b>Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', '09')}}" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="philhealth" class="form-label">Philhealth <i>(Optional)</i></label>
                            <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
                        </div>
                    </div>
                </div>
                <hr>
                <div id="address_text" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text')}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text')}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text')}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                          <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                          <select class="form-select" name="address_region_code" id="address_region_code" required>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                            <select class="form-select" name="address_province_code" id="address_province_code" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                            <select class="form-select" name="address_muncity_code" id="address_muncity_code" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                            <select class="form-select" name="address_brgy_text" id="address_brgy_text" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_houseno" class="form-label">House No./Lot/Building <i>(Optional)</i></label>
                            <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_street" class="form-label">Street/Subdivision/Purok/Sitio <i>(Optional)</i></label>
                            <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-9">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_indg" name="is_indg" value="Y" {{(old('is_indg') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Indigenous People</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_4ps" name="is_4ps" value="Y" {{(old('is_4ps') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">4Ps Member</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_nhts" name="is_nhts" value="Y" {{(old('is_nhts') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">NHTS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_pwd" name="is_pwd" value="Y" {{(old('is_pwd') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">PWD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_singleparent" name="is_singleparent" value="Y" {{(old('is_singleparent') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Single Parent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_others" name="is_others" value="Y" {{(old('is_others') == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Others</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div id="ifCheckboxOthersDiv" class="d-none">
                            <div class="form-group">
                                <label for="is_others_specify"><b class="text-danger">*</b>Specify</label>
                                <input type="text" class="form-control" name="is_others_specify" id="is_others_specify" minlength="1" maxlength="100" value="{{old('is_others_specify')}}" style="text-transform: uppercase;" >
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                  <label for="remarks" class="form-label">Remarks <i>(If Applicable)</i></label>
                  <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks')}}</textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn"><i class="fas fa-save mr-2"></i>Save (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

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

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text, #suffix').select2({
        theme: 'bootstrap',
    });

    //Region Select Initialize
    $.getJSON("{{asset('json/refregion.json')}}", function(data) {
        var sorted = data.sort(function(a, b) {
            if (a.regDesc > b.regDesc) {
                return 1;
            }
            if (a.regDesc < b.regDesc) {
                return -1;
            }

            return 0;
        });

        $.each(sorted, function(key, val) {
            $('#address_region_code').append($('<option>', {
                value: val.regCode,
                text: val.regDesc,
                selected: (val.regCode == '04') ? true : false, //default is Region IV-A
            }));
        });
    }).fail(function(jqxhr, textStatus, error) {
        // Error callback
        var err = textStatus + ", " + error;
        console.log("Failed to load Region JSON: " + err);
        window.location.reload(); // Reload the page
    });

    $('#address_region_code').change(function (e) { 
        e.preventDefault();
        //Empty and Disable
        $('#address_province_code').empty();
        $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', true);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_region_text').val($('#address_region_code option:selected').text());

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });

            $.each(sorted, function(key, val) {
                if($('#address_region_code').val() == val.regCode) {
                    $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (val.provCode == '0421') ? true : false, //default for Cavite
                    }));
                }
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load Region JSON: " + err);
            window.location.reload(); // Reload the page
        });
    }).trigger('change');

    $('#address_province_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_province_text').val($('#address_province_code option:selected').text());

        $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                }
                if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_province_code').val() == val.provCode) {
                    $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                    })); 
                }
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load CityMun JSON: " + err);
            window.location.reload(); // Reload the page
        });
    }).trigger('change');

    $('#address_muncity_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_brgy_text').empty();
        $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', false);

        //Set Values for Hidden Box
        $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

        $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.brgyDesc > b.brgyDesc) {
                return 1;
                }
                if (a.brgyDesc < b.brgyDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_muncity_code').val() == val.citymunCode) {
                    $('#address_brgy_text').append($('<option>', {
                        value: val.brgyDesc.toUpperCase(),
                        text: val.brgyDesc.toUpperCase(),
                    }));
                }
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load Province BRGY: " + err);
            window.location.reload(); // Reload the page
        });
    }).trigger('change');

    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');

    $('#has_bday').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Yes') {
            $('#ybday').removeClass('d-none');
            $('#nbday').addClass('d-none');

            $('#bdate').prop('required', true);
            $('#age').prop('required', false);
        }
        else {
            $('#ybday').addClass('d-none');
            $('#nbday').removeClass('d-none');

            $('#bdate').prop('required', false);
            $('#age').prop('required', true);
        }
    }).trigger('change');

    $('#is_others').change(function (e) { 
        e.preventDefault();
        if($(this).prop('checked')) {
            $('#ifCheckboxOthersDiv').removeClass('d-none');
            $('#is_others_specify').prop('required', true);
        }
        else {
            $('#ifCheckboxOthersDiv').addClass('d-none');
            $('#is_others_specify').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection