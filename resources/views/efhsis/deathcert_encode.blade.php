@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('fhsis_deathcert_store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>Encode Death Certificate</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <strong class="text-danger">NOTE:</strong> All fields marked with an asterisk <b class="text-danger">*</b> are required.
                    </div>
                    <div class="form-group">
                      <label for="if_fetaldeath"><b class="text-danger">*</b>Record is Fetal Death? (Fetus)</label>
                      <select class="form-control" name="if_fetaldeath" id="if_fetaldeath" required>
                        <option value="" disabled {{is_null(old('if_fetaldeath')) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('if_fetaldeath') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('if_fetaldeath') == 'N') ? 'selected' : ''}}>No</option>
                      </select>
                    </div>
                    <div id="part2" class="d-none">
                        <div class="card">
                            <div class="card-header"><b><span id="patientCardHeader"></span></b></div>
                            <div class="card-body">
                                <div class="alert alert-info d-none" role="alert" id="fetusAlert">
                                    <strong class="text-danger">NOTE:</strong> <b>Input name of the Fetus, IF GIVEN.</b>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fname"><span class="text-danger font-weight-bold" id="fnameAst"></span>First Name</label>
                                            <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mname"><span class="text-danger font-weight-bold" id="mnameAst"></span>Middle Name</label>
                                            <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;">
                                            <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lname"><span class="text-danger font-weight-bold" id="lnameAst"></span>Last Name</label>
                                            <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                            <select class="form-control" name="gender" id="gender" required>
                                                <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                                <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                                <option value="UNKNOWN" {{(old('gender') == 'UNKNOWN') ? 'selected' : ''}} id="unknownGender" class="d-none">Unknown</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="date_died"><span class="text-danger font-weight-bold" id="dateDiedAst"></span>Date of Death</label>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_year" id="input_year" value="{{old('input_year')}}" min="2023" max="{{date('Y')}}" placeholder="YYYY" tabindex="-1" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_month" id="input_month" value="{{old('input_month')}}" min="1" max="12" placeholder="MM" tabindex="-1" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_day" id="input_day" min="1" value="{{old('input_day')}}" max="31" placeholder="DD" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label for="date_died"><span class="text-danger font-weight-bold" id="dateDiedAst"></span>Date of Death</label>
                                            <input type="date" class="form-control" id="date_died" name="date_died" value="{{request()->input('date_died')}}" min="2000-01-01" max="{{date('Y-m-d')}}">
                                        </div>
                                        -->
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="bdate"><span class="text-danger font-weight-bold">*</span><span id="BirthdateSpan"></span></label>
                                            <input type="date" class="form-control" id="bdate" name="bdate" value="{{request()->input('bdate')}}" min="1900-01-01" max="{{date('Y-m-d')}}" required>
                                        </div>
                                        <!--
                                        <label for="bdate"><span class="text-danger font-weight-bold">*</span><span id="BirthdateSpan"></span></label>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_month2" id="input_month2" value="{{old('input_month2', request()->input('month'))}}" min="1" max="12" placeholder="MM" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_day2" id="input_day2" min="1" value="{{old('input_day2')}}" max="31" placeholder="DD" required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="input_year2" id="input_year2" value="{{old('input_year2', request()->input('year'))}}" max="{{date('Y')}}" placeholder="YYYY" required>
                                                </div>
                                            </div>
                                        </div>
                                        -->
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
                                        <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Residence - Region</label>
                                        <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Residence -  Province</label>
                                            <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Residence - City/Municipality</label>
                                            <select class="form-control" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span> Residence - Barangay</label>
                                            <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_placeofdeath"><b class="text-danger">*</b><span id="placeOfDeathSpan"></span> (Name of Hospital/Clinic/Institution/House No., St.)</label>
                                            <input type="text" class="form-control" name="name_placeofdeath" id="name_placeofdeath" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="pod_insidecity"><b class="text-danger">*</b>Is the place of death inside the City of General Trias?</label>
                                          <select class="form-control" name="pod_insidecity" id="pod_insidecity" required>
                                            <option value="" disabled {{(is_null(old('pod_insidecity'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('pod_insidecity') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('pod_insidecity') == 'N') ? 'selected' : ''}}>No</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="address_text" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" id="pod_address_region_text" name="pod_address_region_text" value="{{old('pod_address_region_text')}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="pod_address_province_text" name="pod_address_province_text" value="{{old('pod_address_province_text')}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="pod_address_muncity_text" name="pod_address_muncity_text" value="{{old('pod_address_muncity_text')}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div id="ifPodInsideCityDiv" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                            <label for="pod_address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span><span id="podDiv_region"></span> - Region</label>
                                            <select class="form-control" name="pod_address_region_code" id="pod_address_region_code" tabindex="-1">
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="pod_address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span><span id="podDiv_province"></span> - Province</label>
                                                <select class="form-control" name="pod_address_province_code" id="pod_address_province_code" tabindex="-1">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="pod_address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span><span id="podDiv_city"></span> - City/Municipality</label>
                                                <select class="form-control" name="pod_address_muncity_code" id="pod_address_muncity_code" tabindex="-1">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="pod_address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span><span id="podDiv_barangay"></span> - Barangay</label>
                                                <select class="form-control" name="pod_address_brgy_text" id="pod_address_brgy_text">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="ifFetalDeathDiv" class="d-none">
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="fetald_typeofdelivery"><span class="text-danger font-weight-bold">*</span>Type of Delivery</label>
                                                <select class="form-control" name="fetald_typeofdelivery" id="fetald_typeofdelivery">
                                                    <option value="" disabled {{(is_null(old('fetald_typeofdelivery'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="SINGLE" {{(old('fetald_typeofdelivery') == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                                    <option value="TWIN" {{(old('fetald_typeofdelivery') == 'TWIN') ? 'selected' : ''}}>Twin</option>
                                                    <option value="TRIPLET, ETC." {{(old('fetald_typeofdelivery') == 'TRIPLET, ETC.') ? 'selected' : ''}}>Triplet, etc.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6" id="ifMultipleDeliveries" class="d-none">
                                            <div class="form-group">
                                                <label for="fetald_ifmultipledeliveries_fetuswas"><span class="text-danger font-weight-bold">*</span>IF Multiple Delivery, Fetus was</label>
                                                <select class="form-control" name="fetald_ifmultipledeliveries_fetuswas" id="fetald_ifmultipledeliveries_fetuswas">
                                                    <option value="" disabled {{(is_null(old('fetald_ifmultipledeliveries_fetuswas'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="FIRST" {{(old('fetald_ifmultipledeliveries_fetuswas') == 'SINGLE') ? 'selected' : ''}}>First</option>
                                                    <option value="SECOND" {{(old('fetald_ifmultipledeliveries_fetuswas') == 'TWIN') ? 'selected' : ''}}>Second</option>
                                                    <option value="OTHERS" {{(old('fetald_ifmultipledeliveries_fetuswas') == 'TRIPLET, ETC.') ? 'selected' : ''}}>Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="fetald_methodofdelivery">Method of Delivery</label>
                                                <select class="form-control" name="fetald_methodofdelivery" id="fetald_methodofdelivery">
                                                    <option value="" disabled {{(is_null(old('fetald_methodofdelivery'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="NORMAL" {{(old('fetald_methodofdelivery') == 'SINGLE') ? 'selected' : ''}}>Normal spontaneous vertex</option>
                                                    <option value="OTHER" {{(old('fetald_methodofdelivery') == 'TWIN') ? 'selected' : ''}}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="fetald_birthorder">Birth Order</label>
                                                <input type="number" class="form-control" id="fetald_birthorder" name="fetald_birthorder" value="{{old('fetald_birthorder')}}" min="1" max="50">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="fetalMotherInfo" class="d-none">
                                    <div class="alert alert-info" role="alert">
                                        <strong class="text-danger">NOTE:</strong> Please input <b>MAIDEN NAME</b> of the Mother.
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fetald_mother_lname"><span class="text-danger font-weight-bold">*</span>Mother Last Name</label>
                                                <input type="text" class="form-control" id="fetald_mother_lname" name="fetald_mother_lname" value="{{old('fetald_mother_lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fetald_mother_fname"><span class="text-danger font-weight-bold">*</span>Mother First Name</label>
                                                <input type="text" class="form-control" id="fetald_mother_fname" name="fetald_mother_fname" value="{{old('fetald_mother_fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fetald_mother_mname">Mother Middle Name</label>
                                                <input type="text" class="form-control" id="fetald_mother_mname" name="fetald_mother_mname" value="{{old('fetald_mother_mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="ifNormalDeath" class="d-none">
                                    <div id="ifMaternalCondition" class="d-none">
                                        <div class="form-group">
                                            <label for="maternal_condition"><span class="text-danger font-weight-bold">*</span>Maternal Condition (If the deceased is female aged 15-49 years old)</label>
                                            <select class="form-control" name="maternal_condition" id="maternal_condition">
                                                <option value="" disabled {{(is_null(old('maternal_condition'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="PREGNANT, NOT IN LABOUR" {{(old('maternal_condition') == 'PREGNANT, NOT IN LABOUR') ? 'selected' : ''}}>Pregnant, not in labour</option>
                                                <option value="PREGNANT, IN LABOUR" {{(old('maternal_condition') == 'PREGNANT, IN LABOUR') ? 'selected' : ''}}>Pregnant, in labour</option>
                                                <option value="LESS THAN 42 DAYS AFTER DELIVERY" {{(old('maternal_condition') == 'LESS THAN 42 DAYS AFTER DELIVERY') ? 'selected' : ''}}>Less than 42 days after delivery</option>
                                                <option value="42 DAYS TO 1 YEAR AFTER DELIVERY" {{(old('maternal_condition') == '42 DAYS TO 1 YEAR AFTER DELIVERY') ? 'selected' : ''}}>42 days to 1 year after delivery</option>
                                                <option value="N/A" {{(old('maternal_condition') == 'N/A') ? 'selected' : ''}}>None of the choices</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="immediate_cause"><b class="text-danger">*</b>Cause of Death (Immediate Cause)</label>
                            <select class="form-control" name="immediate_cause" id="immediate_cause" required>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer d-none" id="cardFooter">
                    <button type="submit" class="btn btn-success btn-block" id="submitbtn">Submit (CTRL + S)</button>
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

        //Select2 Init for Address Bar
        $('#pod_address_region_code, #pod_address_province_code, #pod_address_muncity_code, #pod_address_brgy_text').select2({
            theme: 'bootstrap',
        });

        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
            theme: 'bootstrap',
        });

        var current_year = "{{date('Y')}}";
        var current_month = "{{date('n')}}";
        var current_day = "{{date('d')}}";

        var default_year = "{{request()->input('year')}}";
        var default_month = "{{request()->input('month')}}";

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
                    $('#pod_address_region_code').append($('<option>', {
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

            $('#pod_address_region_code').change(function (e) { 
                e.preventDefault();
                //Empty and Disable
                $('#pod_address_province_code').empty();
                $("#pod_address_province_code").append('<option value="" selected disabled>Choose...</option>');

                $('#pod_address_muncity_code').empty();
                $("#pod_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#pod_address_muncity_code').prop('disabled', true);
                $('#pod_address_brgy_text').prop('disabled', true);

                //Set Values for Hidden Box
                $('#pod_address_region_text').val($('#pod_address_region_code option:selected').text());

                $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.provDesc$ > b.provDesc) {
                        return 1;
                        }
                        if (a.provDesc < b.provDesc) {
                        return -1;
                        }
                        return 0;
                    });

                    $.each(sorted, function(key, val) {
                        if($('#pod_address_region_code').val() == val.regCode) {
                            $('#pod_address_province_code').append($('<option>', {
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

            $('#pod_address_province_code').change(function (e) {
                e.preventDefault();
                //Empty and Disable
                $('#pod_address_muncity_code').empty();
                $("#pod_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#pod_address_muncity_code').prop('disabled', false);
                $('#pod_address_brgy_text').prop('disabled', true);

                //Set Values for Hidden Box
                $('#pod_address_province_text').val($('#pod_address_province_code option:selected').text());

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
                        if($('#pod_address_province_code').val() == val.provCode) {
                            $('#pod_address_muncity_code').append($('<option>', {
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

            $('#pod_address_muncity_code').change(function (e) {
                e.preventDefault();
                //Empty and Disable
                $('#pod_address_brgy_text').empty();
                $("#pod_address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#pod_address_muncity_code').prop('disabled', false);
                $('#pod_address_brgy_text').prop('disabled', false);

                //Set Values for Hidden Box
                $('#pod_address_muncity_text').val($('#pod_address_muncity_code option:selected').text());

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
                        if($('#pod_address_muncity_code').val() == val.citymunCode) {
                            $('#pod_address_brgy_text').append($('<option>', {
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

            $('#pod_address_region_text').val('REGION IV-A (CALABARZON)');
            $('#pod_address_province_text').val('CAVITE');
            $('#pod_address_muncity_text').val('GENERAL TRIAS');

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

        $('#if_fetaldeath').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#part2').removeClass('d-none');
                $('#cardFooter').removeClass('d-none');

                $('#fetusAlert').removeClass('d-none');
                $('#ifFetalDeathDiv').removeClass('d-none');
                $('#fetalMotherInfo').removeClass('d-none');
                $('#ifNormalDeath').addClass('d-none');

                $('#fetald_birthorder').prop('required', true);

                $('#lnameAst').text('');
                $('#fnameAst').text('');
                $('#mnameAst').text('');
                $('#dateDiedAst').text('');

                $('#lname').prop('required', false);
                $('#fname').prop('required', false);
                $('#mname').prop('required', false);
                
                $('#input_year').prop('required', false);
                $('#input_month').prop('required', false);
                $('#input_day').prop('required', false);
                $('#input_year').prop('disabled', true);
                $('#input_month').prop('disabled', true);
                $('#input_day').prop('disabled', true);
                $('#input_year').prop('readonly', true);
                $('#input_month').prop('readonly', true);
                $('#input_day').prop('readonly', true);
                $('#input_year').val('');
                $('#input_month').val('');
                $('#input_day').val('');

                /*
                $('#input_year2').prop('required', true);
                $('#input_month2').prop('required', true);
                $('#input_day2').prop('required', true);
                $('#input_year2').prop('disabled', false);
                $('#input_month2').prop('disabled', false);
                $('#input_day2').prop('disabled', false);
                $('#input_year2').prop('readonly', true);
                $('#input_month2').prop('readonly', true);
                $('#input_day2').prop('readonly', false);
                $('#input_year2').val(default_year);
                $('#input_month2').val(default_month);
                $('#input_day2').val('');
                */
                //$('#date_died').prop('required', false);
                //$('#date_died').prop('disabled', true);

                $('#patientCardHeader').text('Fetus Details');
                $('#unknownGender').removeClass('d-none');
                $('#BirthdateSpan').text('Date of Delivery');
                $('#placeOfDeathSpan').text('Place of Delivery');
                
                $('#podDiv_region').text('Place of Delivery');
                $('#podDiv_province').text('Place of Delivery');
                $('#podDiv_city').text('Place of Delivery');
                $('#podDiv_barangay').text('Place of Delivery');

                $('#fetald_mother_lname').prop('required', true);
                $('#fetald_mother_fname').prop('required', true);
                
                $('#input_day').attr('max', 31);
                $('#input_day2').attr('max', 31);

                if(current_year == default_year) {
                    if(current_month == default_month) {
                        $('#input_day2').attr('max', current_day);
                    }
                }
            }
            else if($(this).val() == 'N') {
                $('#part2').removeClass('d-none');
                $('#cardFooter').removeClass('d-none');

                $('#fetusAlert').addClass('d-none');
                $('#ifFetalDeathDiv').addClass('d-none');
                $('#fetalMotherInfo').addClass('d-none');
                $('#ifNormalDeath').removeClass('d-none');

                $('#fetald_birthorder').prop('required', false);

                $('#lnameAst').text('*');
                $('#fnameAst').text('*');
                $('#mnameAst').text('*');
                $('#dateDiedAst').text('*');
                
                $('#lname').prop('required', true);
                $('#fname').prop('required', true);
                $('#mname').prop('required', true);

                $('#input_year').prop('required', true);
                $('#input_month').prop('required', true);
                $('#input_day').prop('required', true);
                $('#input_year').prop('disabled', false);
                $('#input_month').prop('disabled', false);
                $('#input_day').prop('disabled', false);
                $('#input_year').prop('readonly', true);
                $('#input_month').prop('readonly', true);
                $('#input_day').prop('readonly', false);
                $('#input_year').val(default_year);
                $('#input_month').val(default_month);
                $('#input_day').val('');

                /*
                $('#input_year2').prop('required', true);
                $('#input_month2').prop('required', true);
                $('#input_day2').prop('required', true);
                $('#input_year2').prop('disabled', false);
                $('#input_month2').prop('disabled', false);
                $('#input_day2').prop('disabled', false);
                $('#input_year2').prop('readonly', false);
                $('#input_month2').prop('readonly', false);
                $('#input_day2').prop('readonly', false);
                $('#input_year2').val('');
                $('#input_month2').val('');
                $('#input_day2').val('');
                */

                //$('#date_died').prop('required', true);
                //$('#date_died').prop('disabled', false);

                $('#patientCardHeader').text('Deceased Person Details');
                $('#unknownGender').addClass('d-none');
                $('#BirthdateSpan').text('Date of Birth');
                $('#placeOfDeathSpan').text('Place of Death');

                $('#podDiv_region').text('Place of Death');
                $('#podDiv_province').text('Place of Death');
                $('#podDiv_city').text('Place of Death');
                $('#podDiv_barangay').text('Place of Death');

                $('#fetald_mother_lname').prop('required', false);
                $('#fetald_mother_fname').prop('required', false);

                $('#input_day').attr('max', 31);
                $('#input_day2').attr('max', 31);

                if(current_year == default_year) {
                    if(current_month == default_month) {
                        $('#input_day').attr('max', current_day);
                    }
                }
            }
            else {
                $('#part2').addClass('d-none');

                $('#unknownGender').addClass('d-none');
            }
        });

        function checkBdate() {
            if($('#input_day2').val() != '') {
                //var birthdate = $('#input_year2').val() + '-' + $('#input_month2').val() + '-' + $('#input_day2').val();
                var birthdate = $('#bdate').val();

                var today = new Date();
                var birthDate = new Date(birthdate);

                var years = today.getFullYear() - birthDate.getFullYear();
                var months = today.getMonth() - birthDate.getMonth();
                var days = today.getDate() - birthDate.getDate();

                if (days < 0) {
                    months--;
                    days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                if(years >= 15 && years <= 49) {
                    if($('#gender').val() == 'FEMALE' && $('#if_fetaldeath').val() == 'N') {
                        $('#ifMaternalCondition').removeClass('d-none');
                        $('#maternal_condition').prop('required', true);
                    }
                    else {
                        $('#ifMaternalCondition').addClass('d-none');
                        $('#maternal_condition').prop('required', false);
                    }
                }
                else {
                    $('#ifMaternalCondition').addClass('d-none');
                    $('#maternal_condition').prop('required', false);
                }
            }
        }

        $('#bdate').change(function (e) { 
            e.preventDefault();
            checkBdate();
        });

        /*
        $('#input_day2').change(function (e) { 
            e.preventDefault();
            checkBdate();
        });
        
        $('#input_month2').change(function (e) { 
            e.preventDefault();
            checkBdate();
        });

        $('#input_year2').change(function (e) { 
            e.preventDefault();
            checkBdate();
        });
        */

        $('#immediate_cause').select2({
            theme: "bootstrap",
            placeholder: 'Input ICD10 Code or Description',
            ajax: {
                url: "{{route('syndromic_icd10list')}}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.desc,
                                id: item.desc,
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#fetald_typeofdelivery').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'TWIN' || $(this).val() == 'TRIPLET, ETC.') {
                $('#ifMultipleDeliveries').removeClass('d-none');
                $("#fetald_ifmultipledeliveries_fetuswas").prop('required', true);
            }
            else {
                $('#ifMultipleDeliveries').addClass('d-none');
                $("#fetald_ifmultipledeliveries_fetuswas").prop('required', false);
            }
        });

        /*
        $(document).ready(function() {
            $('#calculate-age').click(function() {
                var birthdate = $('#birthdate').val();
                if (!birthdate) {
                    alert("Please enter a birthdate.");
                    return;
                }
                var today = new Date();
                var birthDate = new Date(birthdate);

                var years = today.getFullYear() - birthDate.getFullYear();
                var months = today.getMonth() - birthDate.getMonth();
                var days = today.getDate() - birthDate.getDate();

                if (days < 0) {
                    months--;
                    days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                $('#age').html("Age: " + years + " years, " + months + " months, " + days + " days.");
            });
        });
        */
    </script>
@endsection