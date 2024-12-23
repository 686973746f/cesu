@extends('layouts.app')

@section('content')
    @if($p->exists)
    <form action="{{route('gtsecure_updatepatient', $d->id)}}" method="POST">
    @else
    <form action="{{route('gtsecure_storepatient', $d->id)}}" method="POST">
    @endif
    @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>New Patient</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <b class="text-danger">Note:</b> All fields marked with an asterisk (<b class="text-danger">*</b>) are required to be filled-out properly.
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $p->lname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $p->fname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $p->mname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix <small>(ex. JR, SR, II, III, etc.)</small></label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $p->suffix)}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nickname">Nickname/Alias</label>
                                <input type="text" class="form-control" id="nickname" name="nickname" value="{{old('nickname', $p->nickname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $p->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                <select class="form-control" name="sex" id="sex" required>
                                  <option value="" disabled {{(is_null(old('sex', $p->sex))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="M" {{(old('sex', $p->sex) == 'M') ? 'selected' : ''}}>Male</option>
                                  <option value="F" {{(old('sex', $p->sex) == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                            <div id="femaleDiv" class="d-none">
                                <div class="form-group">
                                    <label for="is_pregnant"><b class="text-danger">*</b>Is Pregnant?</label>
                                    <select class="form-control" name="is_pregnant" id="is_pregnant">
                                      <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="is_lactating"><b class="text-danger">*</b>Is Lactating?</label>
                                    <select class="form-control" name="is_lactating" id="is_lactating">
                                      <option value="" disabled {{(is_null(old('is_lactating'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('is_lactating') == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('is_lactating') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $p->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control" id="religion" name="religion" value="{{old('religion', $p->religion)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="philhealth">Philhealth No.</label>
                                <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{old('email', $p->email)}}">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="street_purok"><b class="text-danger">*</b>House No., Street/Purok/Subdivision</label>
                                <input type="text" class="form-control" id="street_purok" name="street_purok" value="{{old('street_purok', $p->street_purok)}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_headoffamily"><span class="text-danger font-weight-bold">*</span>Is the Head of the Family?</label>
                                <select class="form-control" name="is_headoffamily" id="is_headoffamily" required>
                                    @if(!$p->exists)
                                    <option value="" disabled {{(is_null(old('sex', $p->is_headoffamily))) ? 'selected' : ''}}>Choose...</option>
                                    @endif
                                    <option value="Y" {{(old('sex', $p->is_headoffamily) == 'Y') ? 'selected' : ''}}>Yes</option>
                                  @if($heads_list->count() != 0)
                                    <option value="N" {{(old('sex', $p->is_headoffamily) == 'N') ? 'selected' : ''}}>No</option>
                                  @endif
                                </select>
                                <small class="text-muted">This field is used to determine number of families inside the evacuation center.</small>
                            </div>
                            <div id="isHeadOfFamily" class="d-none">
                                <div class="form-group">
                                  <label for="family_patient_id"><b class="text-danger">*</b>Link to Head of Family</label>
                                  <select class="form-control" name="family_patient_id" id="family_patient_id">
                                    <option value="" disabled {{(is_null(old('family_patient_id', $p->family_patient_id))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach($heads_list as $h)
                                    <option value="{{$h->id}}">{{$h->getName()}} - {{$h->getAge()}}/{{$h->sex}}</option>
                                    @endforeach
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_pwd"><span class="text-danger font-weight-bold">*</span>Is PWD?</label>
                                <select class="form-control" name="is_pwd" id="is_pwd" required>
                                    <option value="" disabled {{(is_null(old('is_pwd', $p->is_pwd))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pwd', $p->is_pwd) == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pwd', $p->is_pwd) == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="is_injured"><span class="text-danger font-weight-bold">*</span>Is Injured?</label>
                                <select class="form-control" name="is_injured" id="is_injured" required>
                                    <option value="" {{(is_null(old('is_injured', $p->is_injured))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_injured', $p->is_injured) == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_injured', $p->is_injured) == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                                <select class="form-control" name="outcome" id="outcome" required>
                                  <option value="" disabled {{(is_null(old('outcome', $p->outcome))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="ALIVE" {{(old('outcome', $p->outcome) == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                  <option value="DIED" {{(old('outcome', $p->outcome) == 'DIED') ? 'selected' : ''}}>Died</option>
                                  <option value="MISSING" {{(old('outcome', $p->outcome) == 'MISSING') ? 'selected' : ''}}>Missing</option>
                                  <option value="RETURNED TO HOME" {{(old('outcome', $p->outcome) == 'RETURNED TO HOME') ? 'selected' : ''}}>Returned to Home</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $p->remarks)}}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitbtn">Save (CTRL + S)</button>
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

    $(document).ready(function () {
        function calculateAge(birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
            }
            return age;
        }

        function toggleIsPregnantDiv() {
            const gender = $("#sex").val();
            const birthdate = $("#bdate").val();
            const age = calculateAge(birthdate);

            if (gender === "F" && age > 10) {
                $("#femaleDiv").removeClass('d-none');
                $('#is_pregnant').prop('required', true);
                $('#is_lactating').prop('required', true);

            } else {
                $("#femaleDiv").addClass('d-none');
                $('#is_pregnant').prop('required', false);
                $('#is_lactating').prop('required', false);
            }
        }

        $("#sex, #bdate").on("change", function () {
            toggleIsPregnantDiv();
        });
    });

    $('#is_headoffamily').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y' || $(this).val() == null) {
            $('#isHeadOfFamily').addClass('d-none');
            $('#family_patient_id').prop('required', false);
        }
        else {
            $('#isHeadOfFamily').removeClass('d-none');
            $('#family_patient_id').prop('required', true);
        }
    }).trigger('change');

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code, #family_patient_id').select2({
        theme: 'bootstrap',
    });

    
    @if($p->exists)
    //Default Values for Gentri
    var regionDefault = {{$p->brgy->city->province->region->id}};
    var provinceDefault = {{$p->brgy->city->province->id}};
    var cityDefault = {{$p->brgy->city->id}};
    var brgyDefault = {{$p->address_brgy_code}};
    @else
    //Default Values for Gentri
    var regionDefault = 1;
    var provinceDefault = 18;
    var cityDefault = 388;
    @endif

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
    @if($p->exists)
    if (brgyDefault) {
        setTimeout(function() {
            $('#address_brgy_code').val(brgyDefault).trigger('change');
        }, 1500); // Slight delay to ensure city is loaded
    }
    @endif
</script>
@endsection