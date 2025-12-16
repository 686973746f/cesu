@extends('layouts.app')

@section('content')
    <form action="{{ route('injury_add_store', $f->sys_code1) }}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>New Injury</b></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="patient_type"><b class="text-danger">*</b>Type of Patient</label>
                        <select class="form-control" name="patient_type" id="sex" required>
                            <option value="" disabled {{(is_null(old('patient_type'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="ER" {{(old('patient_type') == 'ER') ? 'selected' : ''}}>Male</option>
                            <option value="OPD" {{(old('patient_type') == 'OPD') ? 'selected' : ''}}>Female</option>
                            <option value="IN-PATIENT" {{(old('patient_type') == 'IN-PATIENT') ? 'selected' : ''}}>Female</option>
                            <option value="BHS" {{(old('patient_type') == 'F') ? 'selected' : ''}}>Female</option>
                            <option value="RHU" {{(old('patient_type') == 'F') ? 'selected' : ''}}>Female</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                <input type="text" class="form-control" name="lname" id="lname" value="{{request()->input('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><b class="text-danger">*</b>First Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" value="{{request()->input('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{request()->input('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{request()->input('suffix')}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="bdate_available"><b class="text-danger">*</b>Birthdate Available?</label>
                            <select class="form-control" name="bdate_available" id="bdate_available" required>
                                <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                        <div id="bdate_yes" class="d-none">
                            <div class="form-group">
                                <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                <input type="date" class="form-control" name="bdate" id="bdate" value="{{request()->input('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required readonly tabindex="-1">
                            </div>
                        </div>
                        <div id="bdate_no" class="d-none">
                            <div class="form-group">
                                <label for="age_years"><b class="text-danger">*</b>Age (In Years)</label>
                                <input type="number" class="form-control" name="age_years" id="age_years">
                            </div>
                        </div>

                        <div class="form-group">
                          <label for="philhealth">Philhealth</label>
                          <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
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
                                <select class="form-control" name="brgy_id" id="address_brgy_code" required disabled>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="same_address"><b class="text-danger">*</b>Permanent Address same as Temporary Address?</label>
                        <select class="form-control" name="same_address" id="same_address" required>
                            <option value="" disabled {{(is_null(old('same_address'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('same_address') == 'Y') ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{(old('same_address') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>

                    <div id="temp_div" class="d-none">

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
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
			}, 1600);
			return false;
		}
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
        }, 1500); // Slight delay to ensure province is loaded
    }
    if (cityDefault) {
        setTimeout(function() {
            $('#address_muncity_code').val(cityDefault).trigger('change');
        }, 2500); // Slight delay to ensure city is loaded
    }

    $('#sys_occupationtype').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'WORKING' || $(this).val() == 'STUDENT') {
            $('#hasOccupation').removeClass('d-none');

            $('#sys_businessorschool_name').prop('required', true);
            $('#sys_businessorschool_address').prop('required', true);
        }
        else {
            $('#hasOccupation').addClass('d-none');

            $('#sys_businessorschool_name').prop('required', false);
            $('#sys_businessorschool_address').prop('required', false);
        }

        if($(this).val() == 'WORKING') {
            $('#occupationNameText').text('Name of Workplace');
            $('#occupationAddressText').text('Address of Workplace');
        }
        else if($(this).val() == 'STUDENT') {
            $('#occupationNameText').text('Name of School');
            $('#occupationAddressText').text('Address of School');
        }
    }).trigger('change');

    $('#ip').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#ip_div').removeClass('d-none');
            $('#ipgroup').prop('required', true);
        }
        else {
            $('#ip_div').addClass('d-none');
            $('#ipgroup').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection
