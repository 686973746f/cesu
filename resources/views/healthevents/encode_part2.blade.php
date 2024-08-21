@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('he_store', [$event_code, $facility_code])}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>{{$he->event_name}}</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Facility</label>
                                <input type="text" class="form-control" name="" id="" value="{{mb_strtoupper($f->facility_name)}}" tabindex="-1" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Name of Reporter</label>
                                <input type="text" class="form-control" name="reportedby_name" id="reportedby_name" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="fname"><b class="text-danger">*</b>First Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $mname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $suffix)}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" tabindex="-1" readonly required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                  <select class="form-control" name="gender" id="gender" required>
                                      <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                      <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                  </select>
                            </div>
                            <div class="d-none" id="ifFemaleDiv">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Pregnant?</label>
                                    <select class="form-control" name="is_pregnant" id="is_pregnant">
                                        <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
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
                              <select class="form-control" name="address_region_code" id="address_region_code" required>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                <select class="form-control" name="address_province_code" id="address_province_code" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_houseno" class="form-label"><b class="text-danger">*</b>House No./Lot/Building</label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. S1 B2 L3 PHASE 4 MIRAGE ST." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. SUBDIVISION HOMES" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Date Onset of Illness</label>
                                <input type="date" class="form-control" name="date_onset" id="date_onset" value="{{old('date_onset')}}" min="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Admitted?</label>
                                <select class="form-control" name="admitted" id="admitted" required>
                                    <option value="" disabled {{(is_null(old('admitted'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('admitted') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('admitted') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Date Consulted/Reported</label>
                                <input type="date" class="form-control" name="date_admittedconsulted" id="date_admittedconsulted" value="{{old('date_admittedconsulted')}}" min="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    @if($he->id == 1)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Dizziness</label>
                                <select class="form-control" name="vog_dizziness" id="vog_dizziness" required>
                                    <option value="" disabled {{(is_null(old('vog_dizziness'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('vog_dizziness') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('vog_dizziness') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Difficulty of Breathing</label>
                                <select class="form-control" name="vog_dob" id="vog_dob" required>
                                    <option value="" disabled {{(is_null(old('vog_dob'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('vog_dob') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('vog_dob') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Cough</label>
                                <select class="form-control" name="vog_cough" id="vog_cough" required>
                                    <option value="" disabled {{(is_null(old('vog_cough'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('vog_cough') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('vog_cough') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Eye Irritation</label>
                                <select class="form-control" name="vog_eyeirritation" id="vog_eyeirritation" required>
                                    <option value="" disabled {{(is_null(old('vog_eyeirritation'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('vog_eyeirritation') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('vog_eyeirritation') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Throat Irritation</label>
                                <select class="form-control" name="vog_throatirritation" id="vog_throatirritation" required>
                                    <option value="" disabled {{(is_null(old('vog_throatirritation'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('vog_throatirritation') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('vog_throatirritation') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Others, specify</label>
                                <input type="text" class="form-control" name="vog_others_specify" id="vog_others_specify" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Outcome</label>
                                <select class="form-control" name="outcome" id="outcome" required>
                                    <option value="" disabled {{(is_null(old('outcome'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="A" {{(old('outcome') == 'A') ? 'selected' : ''}}>Alive</option>
                                    <option value="D" {{(old('outcome') == 'D') ? 'selected' : ''}}>Died</option>
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
                </div>
            </div>
            <p class="mt-3 text-center">©2021 - 2024 Developed and Maintained by <u>Christian James Historillo</u></p>
        </form>
    </div>


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

        var patient_age = {{Carbon\Carbon::parse(request()->input('bdate'))->age}};

        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
            theme: 'bootstrap',
        });

        $('#gender').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'FEMALE' && patient_age >= 10) {
                $('#ifFemaleDiv').removeClass('d-none');
                $('#is_pregnant').prop('required', true);
            }
            else {
                $('#ifFemaleDiv').addClass('d-none');
                $('#is_pregnant').prop('required', false);
            }
        }).trigger('change');

        $(document).ready(function () {
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
        });
    </script>
@endsection