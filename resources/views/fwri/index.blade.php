@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('fwri_store', $code)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>CESU Gen. Trias - Fireworks Related Injury (FWRI) Online Reporting Tool</b></div>
                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>METADATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="">Facility Name</label>
                                      <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="{{old('hospital_name', mb_strtoupper($hospital_name))}}" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Date Reported</label>
                                        <input type="datetime-local" class="form-control" name="report_date" id="report_date" value="{{old('report_date', date('Y-m-d\TH:i'))}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reported_by">Name of Reporter</label>
                                        <input type="text" class="form-control" name="reported_by" id="reported_by" value="{{old('reported_by')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>PATIENT DATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
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
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********">
                                    </div>
                                </div>
                            </div>
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
                            <hr>
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
                                        <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center"><b>INCIDENT DATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Injury</label>
                                        <input type="datetime-local" class="form-control" name="injury_date" id="injury_date" value="{{old('injury_date')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Consultation</label>
                                        <input type="datetime-local" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="place_of_occurrence"><span class="text-danger font-weight-bold">*</span>Place of Occurrence</label>
                                        <select class="form-control" name="place_of_occurrence" id="place_of_occurrence" required>
                                            <option value="" disabled {{(is_null(old('place_of_occurrence'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="HOME" {{(old('place_of_occurrence') == 'HOME') ? 'selected' : ''}}>HOME</option>
                                            <option value="STREET" {{(old('place_of_occurrence') == 'STREET') ? 'selected' : ''}}>STREET</option>
                                            <option value="DESIGNATED AREA" {{(old('place_of_occurrence') == 'DESIGNATED AREA') ? 'selected' : ''}}>DESIGNATED AREAS</option>
                                            <option value="OTHERS" {{(old('place_of_occurrence') == 'OTHERS') ? 'selected' : ''}}>OTHERS, SPECIFY</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
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
    </script>
@endsection