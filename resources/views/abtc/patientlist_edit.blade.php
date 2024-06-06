@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@if(auth()->user()->isAdmin == 1)
<div class="container">
    <form action="{{route('abtc_patient_destroy', [$d->id])}}" method="POST">
        @csrf
        @method('delete')
        <div class="text-right mb-3">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to DELETE? Click OK to Confirm.')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete Patient Record</button>
        </div>
    </form>
</div>
@endif
<form action="{{route('abtc_patient_update', ['id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><strong><i class="fa-solid fa-user-gear me-2"></i>Edit Patient</strong></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($bcheck)
                <div class="d-grid gap-2">
                    <a href="{{route('abtc_patient_viewbakunarecords', ['id' => $d->id])}}" class="btn btn-primary"><i class="fas fa-syringe mr-2"></i>View Bakuna Records of Patient</a>
                </div>
                <hr>
                @endif
                <div class="alert alert-info" role="alert">
                    Note: All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <td class="bg-light"><b>Created At / By</b></td>
                            <td>{{date('m/d/Y H:i A', strtotime($d->created_at))}} ({{$d->getCreatedBy()}})</td>
                        </tr>
                        @if($d->updated_at != $d->created_at)
                        <tr>
                            <td class="bg-light"><b>Updated At / By</b></td>
                            <td>{{date('m/d/Y H:i A', strtotime($d->updated_at))}} ({{$d->getUpdatedBy()}})</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="lname" class="form-label"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $d->lname)}}" maxlength="50" placeholder="e.g DELA CRUZ" style="text-transform: uppercase;" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="fname" class="form-label"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname' , $d->fname)}}" maxlength="50" placeholder="e.g JUAN" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname' , $d->mname ?: 'N/A')}}" placeholder="e.g SANCHEZ" style="text-transform: uppercase;" maxlength="50" required>
                            <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="suffix"><b class="text-danger">*</b>Suffix</label>
                            <select class="form-select" name="suffix" id="suffix" required>
                                <option value="I" {{(old('suffix', $d->suffix) == 'I') ? 'selected' : ''}}>I</option>
                                <option value="II" {{(old('suffix', $d->suffix) == 'II') ? 'selected' : ''}}>II</option>
                                <option value="III" {{(old('suffix', $d->suffix) == 'III') ? 'selected' : ''}}>III</option>
                                <option value="IV" {{(old('suffix', $d->suffix) == 'IV') ? 'selected' : ''}}>IV</option>
                                <option value="V" {{(old('suffix', $d->suffix) == 'V') ? 'selected' : ''}}>V</option>
                                <option value="VI" {{(old('suffix', $d->suffix) == 'VI') ? 'selected' : ''}}>VI</option>
                                <option value="VII" {{(old('suffix', $d->suffix) == 'VII') ? 'selected' : ''}}>VII</option>
                                <option value="VIII" {{(old('suffix', $d->suffix) == 'VIII') ? 'selected' : ''}}>VIII</option>
                                <option value="JR" {{(old('suffix', $d->suffix) == 'JR') ? 'selected' : ''}}>JR</option>
                                <option value="JR II" {{(old('suffix', $d->suffix) == 'JR II') ? 'selected' : ''}}>JR II</option>
                                <option value="SR" {{(old('suffix', $d->suffix) == 'SR') ? 'selected' : ''}}>SR</option>
                                <option value="N/A" {{(old('suffix', $d->suffix ?: 'N/A') == 'N/A') ? 'selected' : ''}}>N/A (NOT APPLICABLE)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 d-none">
                          <label for="has_bday" class="form-label"><span class="text-danger font-weight-bold">*</span>Has Birthday</label>
                          <select class="form-select" name="has_bday" id="has_bday" required>
                            <option value="Yes" {{(old('has_bday', !is_null($d->bdate)) == 'Yes') ? 'selected' : ''}}>Yes</option>
                          </select>
                        </div>
                        <div class="mb-3 d-none" id="ybday">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $d->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>  
                        </div>
                        <div class="mb-3 d-none" id="nbday">
                            <label for="age" class="form-label"><b class="text-danger">*</b>Age (In Years)</label>
                            <input type="number" class="form-control" name="age" id="age" value="{{old('age', $d->age)}}" min="0" max="150">  
                        </div>
                        <p>Age: {{$d->getAge()}}</p>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="gender" class="form-label"><b class="text-danger">*</b>Gender</label>
                            <select class="form-select" name="gender" id="gender" required>
                                <option value="" disabled {{(is_null(old('gender', $d->gender))) ? 'selected' : ''}}>Choose...</option>
                                <option value="MALE" {{(old('gender', $d->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                <option value="FEMALE" {{(old('gender', $d->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label"><b class="text-danger">*</b>Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="philhealth" class="form-label">Philhealth <small><i>(If Applicable)</i></small></label>
                            <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth', $d->philhealth)}}" pattern="[0-9]{12}">
                        </div>
                    </div>
                </div>
                <hr>
                <div id="address_text" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text', $d->address_province_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text', $d->address_muncity_text)}}"readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                          <label for="address_region_code" class="form-label"><b class="text-danger">*</b>Region</label>
                          <select class="form-select" name="address_region_code" id="address_region_code" required>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_province_code" class="form-label"><b class="text-danger">*</b>Province</label>
                            <select class="form-select" name="address_province_code" id="address_province_code" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_muncity_code" class="form-label"><b class="text-danger">*</b>City/Municipality</label>
                            <select class="form-select" name="address_muncity_code" id="address_muncity_code" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_brgy_text" class="form-label"><b class="text-danger">*</b>Barangay</label>
                            <select class="form-select" name="address_brgy_text" id="address_brgy_text" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_houseno" class="form-label">House No./Lot/Building</label>
                            <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno', $d->address_houseno)}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_street" class="form-label">Street/Subdivision/Purok/Sitio</label>
                            <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street', $d->address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                  <label for="remarks" class="form-label">Remarks <i>(If Applicable)</i></label>
                  <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $d->remarks)}}</textarea>
                </div>
                
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn"><i class="fas fa-save mr-2"></i>Update (CTRL + S)</button>
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

    var rdefault = "{{old('address_region_code', $d->address_region_code)}}";
    var pdefault = "{{old('address_province_code', $d->address_province_code)}}";
    var cdefault = "{{old('address_muncity_code', $d->address_muncity_code)}}";
    var bdefault = "{{old('address_brgy_text', $d->address_brgy_text)}}";

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
                selected: (val.regCode == rdefault) ? true : false, //default is Region IV-A
            }));
        });
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
                        selected: (val.provCode == pdefault) ? true : false, //default for Cavite
                    }));
                }
            });
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
                        selected: (val.citymunCode == cdefault) ? true : false, //default for General Trias
                    })); 
                }
            });
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
                        selected: (val.brgyDesc.toUpperCase() == bdefault) ? true : false,
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_region_text').val('{{$d->address_region_text}}');
    $('#address_province_text').val('{{$d->address_province_text}}');
    $('#address_muncity_text').val('{{$d->address_muncity_text}}');

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
</script>
@endsection