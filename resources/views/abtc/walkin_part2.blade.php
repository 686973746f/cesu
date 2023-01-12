@extends('layouts.app')

@section('content')
<form action="{{route('walkin_part3')}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">Anti-Rabies Vaccination - Walk in Registration ({{session('vaccination_site_name')}})</div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header">Personal information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="lname" class="form-label"><b class="text-danger">*</b>Last Name</label>
                                    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', request()->input('lname'))}}" maxlength="50" placeholder="DELA CRUZ" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fname" class="form-label"><b class="text-danger">*</b>First Name</label>
                                    <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', request()->input('fname'))}}" maxlength="50" placeholder="JUAN" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="mname" class="form-label">Middle Name <i><small>(If Applicable)</small></i></label>
                                    <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', request()->input('mname'))}}" maxlength="50" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Suffix <i><small>(If Applicable)</small></i></label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', request()->input('suffix'))}}" maxlength="3" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                  <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                                  <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', request()->input('bdate'))}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                    <select class="form-select" name="gender" id="gender" required>
                                        <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Lalaki/Male</option>
                                        <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Babae/Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label"><span class="text-danger font-weight-bold">*</span>Contact Number (Mobile)</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', '09')}}" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">Address</div>
                    <div class="card-body">
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
                                    <label for="address_houseno" class="form-label"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                    <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_street" class="form-label"><span class="text-danger font-weight-bold">*</span>Street/Subdivision/Purok/Sitio</label>
                                    <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Case Information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Uri ng Hayop na Kumagat</label>
                                    <select class="form-select" name="animal_type" id="animal_type" required>
                                        <option value="" disabled {{is_null(old('animal_type')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="PD" {{(old('animal_type') == 'PD') ? 'selected' : ''}}>Alagang Aso/Pet Dog (PD)</option>
                                        <option value="SD" {{(old('animal_type') == 'SD') ? 'selected' : ''}}>Galang Aso/Stray Dog (SD)</option>
                                        <option value="C" {{(old('animal_type') == 'C') ? 'selected' : ''}}>Pusa/Cat</option>
                                        <option value="O" {{(old('animal_type') == 'O') ? 'selected' : ''}}>Iba pa/Others</option>
                                    </select>
                                </div>
                                <div id="ifanimaltype_othersdiv" class="d-none">
                                    <div class="mb-3">
                                        <label for="animal_type_others" class="form-label"><strong class="text-danger">*</strong>Pakitukoy kung anong uri ng hayop ang kumagat/kumalmot</label>
                                        <input type="text" class="form-control" name="animal_type_others" id="animal_type_others" value="{{old('animal_type_others')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bite_date" class="form-label"><strong class="text-danger">*</strong>Kailan nakagat/nakalmot?</label>
                                    <input type="date" class="form-control" name="bite_date" id="bite_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('bite_date')}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="case_location" class="form-label"><strong id="case_location_ast" class="text-danger">*</strong>Saang lugar nangyari ang pangangagat/pangangalmot?</label>
                                    <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location')}}" placeholder="ex: Loob ng bahay, Kalsada, Gusali, Gubat, atbp." required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="body_site" class="form-label"><strong id="body_site_ast" class="text-danger">*</strong>Parte ng katawan na nasugatan/nakagat</label>
                                    <input type="text" class="form-control" name="body_site" id="body_site" value="{{old('body_site')}}" placeholder="ex: Kamay, Paa, Hita, atbp." required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="washing_of_bite" class="form-label"><strong class="text-danger">*</strong>Hinugasan ng Sabon ang Sugat?</label>
                                    <select class="form-select" name="washing_of_bite" id="washing_of_bite" required>
                                        <option value="" disabled {{is_null(old('washing_of_bite')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="Y" {{(old('washing_of_bite') == 'Y') ? 'selected' : ''}}>Oo / Yes</option>
                                        <option value="N" {{(old('washing_of_bite') == 'N') ? 'selected' : ''}}>Hindi / No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ifbleeding" class="form-label"><strong class="text-danger">*</strong>Dumugo ba ang sugat?</label>
                                    <select class="form-select" name="ifbleeding" id="ifbleeding" required>
                                        <option value="" disabled {{is_null(old('ifbleeding')) ? 'selected' : ''}}>Pumili...</option>
                                        <option value="Y" {{(old('ifbleeding') == 'Y') ? 'selected' : ''}}>Oo, dumugo</option>
                                        <option value="N" {{(old('ifbleeding') == 'N') ? 'selected' : ''}}>Hindi, gasgas/galos lang</option>
                                    </select>
                                    <small class="text-muted">Note: Titignan ng magbabakuna kung nasaan ang kagat/sugat.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-2"></i>Finish</button>
            </div>
        </div>
    </div>
</form>

<script>
    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
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
        });
    }).trigger('change');

    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');
</script>
@endsection