@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('abtc_selfreport_store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>CESU General Trias: Rabies Control Program - Online Self-Reporting Tool</b></div>
                <div class="card-body">
                    <div class="alert alert-primary" role="alert">
                        <h6 class="text-danger"><b>Note:</b></h6>
                        <h6>- All fields marked with an asterisks (<b class="text-danger">*</b>) are required fields that must be filled-out.</h6>
                        <h6>- By filling-out, you must provide your genuine details and you agree to the <b>RA 11332</b> and <b>Data Privacy Act of 2012</b>.</h6>
                    </div>
                    <div class="card">
                        <div class="card-header"><b>REFERRAL DETAILS</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="referred_from"><b class="text-danger">*</b>Name of Referring Veterenary Clinic/Facility</label>
                                        <input type="text" class="form-control" name="referred_from" id="referred_from" value="{{old('referred_from')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date Visited</label>
                                        <input type="date" class="form-control" name="referred_date" id="referred_date" value="{{old('referred_date', date('Y-m-d'))}}" required>
                                    </div>
                                </div>                    
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header"><b>PERSONAL DETAILS</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname"><b class="text-danger">*</b>Last Name/Surname</label>
                                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" placeholder="DELA CRUZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" placeholder="JUAN" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" placeholder="SANCHEZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                          <select class="form-control" name="gender" id="gender" required>
                                              <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                              <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                              <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header"><b>CURRENT ADDRESS</b></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                                              <select class="form-control" name="address_region_code" id="address_region_code" required>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                                <select class="form-control" name="address_province_code" id="address_province_code" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                                <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_houseno" class="form-label"><b class="text-danger">*</b>House No./Lot/Building</label>
                                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header"><b>EXPOSURE DETAILS</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bite_date" class="form-label"><strong class="text-danger">*</strong>Date of Exposure/Bite Date</label>
                                        <input type="date" class="form-control" name="bite_date" id="bite_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('bite_date')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="case_location" class="form-label"><b class="text-danger">*</b>Barangay/City (Where biting occured)</label>
                                        <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="if_animal_vaccinated" class="form-label"><strong class="text-danger">*</strong>Is the animal already vaccinated within the year?</label>
                                        <select class="form-control" name="if_animal_vaccinated" id="if_animal_vaccinated" required>
                                            <option value="" disabled {{is_null(old('if_animal_vaccinated')) ? 'selected' : ''}}>Choose...</option>
                                            <option value="N" {{(old('if_animal_vaccinated') == 'N') ? 'selected' : ''}}>No</option>
                                            <option value="Y" {{(old('if_animal_vaccinated') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="U" {{(old('if_animal_vaccinated') == 'U') ? 'selected' : ''}}>Unknown</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Type of Animal</label>
                                        <select class="form-control" name="animal_type" id="animal_type" required>
                                            <option value="" disabled {{is_null(old('animal_type')) ? 'selected' : ''}}>Choose...</option>
                                            <option value="PD" {{(old('animal_type') == 'PD') ? 'selected' : ''}}>Pet Dog (PD)/Alagang Aso</option>
                                            <option value="PC" {{(old('animal_type') == 'PC') ? 'selected' : ''}}>Pet Cat (PC)/Alagang Pusa</option>
                                            <option value="SD" {{(old('animal_type') == 'SD') ? 'selected' : ''}}>Stray Dog (SD)/Galang Aso</option>
                                            <option value="SC" {{(old('animal_type') == 'SC') ? 'selected' : ''}}>Stray Cat (SC)/Galang Pusa</option>
                                            <option value="O" {{(old('animal_type') == 'O') ? 'selected' : ''}}>Others/Iba pa</option>
                                        </select>
                                    </div>
                                    <div id="ifanimaltype_othersdiv" class="d-none">
                                        <div class="form-group">
                                            <label for="animal_type_others" class="form-label"><strong class="text-danger">*</strong>Others, Please state Animal</label>
                                            <input type="text" class="form-control" name="animal_type_others" id="animal_type_others" value="{{old('animal_type_others')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bite_type" class="form-label"><strong class="text-danger">*</strong>Type of Exposure/Uri ng Sugat</label>
                                        <select class="form-control" name="bite_type" id="bite_type" required>
                                            <option value="" disabled {{is_null(old('bite_type')) ? 'selected' : ''}}>Choose...</option>
                                            <option value="B" {{(old('bite_type') == 'B') ? 'selected' : ''}}>Bite/Kagat</option>
                                            <option value="NB" {{(old('bite_type') == 'NB') ? 'selected' : ''}}>Scratch/Kalmot</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="body_site" class="form-label"><b class="text-danger">*</b>Site (Body Parts)</label>
                                        <input type="text" class="form-control" name="body_site" id="body_site" value="{{old('body_site')}}" style="text-transform: uppercase;" placeholder="ex. daliri, binti, kamay" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_booster" class="form-label"><strong class="text-danger">*</strong>Have you completed a Rabies Vaccine Before?/Nakapagpabakuna na ba ng kontra-Rabies dati?</label>
                                        <select class="form-control" name="is_booster" id="is_booster" required>
                                            <option value="" disabled {{is_null(old('is_booster')) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('is_booster') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('is_booster') == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remarks" class="form-label"><b class="text-danger">*</b>Remarks/Iba pang detalye</label>
                                        <textarea class="form-control" name="remarks" id="remarks" rows="3" style="text-transform: uppercase;" required>{{old('remarks')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
            theme: 'bootstrap',
        });

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
        
        $('#animal_type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'O') {
                $('#ifanimaltype_othersdiv').removeClass('d-none');
                $('#animal_type_others').prop('required', true);
            }
            else {
                $('#ifanimaltype_othersdiv').addClass('d-none');
                $('#animal_type_others').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection