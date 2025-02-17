@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('fhsis_livebirth_encode_store')}}" method="post">
            @csrf
            <input type="hidden" class="form-control" name="year" id="year" value="{{request()->input('year')}}" required>
            <input type="hidden" class="form-control" name="month" id="month" value="{{request()->input('month')}}" required>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>Encode Livebirths (LCR)</b></div>
                        <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeMonth">Change Encoding Period</button></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-primary" role="alert">
                        <b>Note: </b>Kapag late report (Year {{(request()->input('year') - 1)}} pababa pinanganak), hindi na kailangan ie-encode.
                        @if($recent)
                        <hr>
                        Palatandaan ng huling encode for this month: <b>{{$recent->registryno}}</b>
                        @endif
                    </div>
                    <div class="card">
                        <div class="card-header"><b>Newborn Details</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="registryno"><b class="text-danger">*</b>Registry No.</label>
                                <input type="text" class="form-control" name="registryno" id="registryno" value="{{old('registryno', request()->input('year').'-')}}" minlength="6" maxlength="11" required>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix">Name Extension <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="sex"><b class="text-danger">*</b>Sex of Newborn</label>
                                        <select class="form-control" name="sex" id="sex" required>
                                          <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                          <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                          <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="input_day"><b class="text-danger">*</b>Birth Day (DD)</label>
                                                <input type="number" class="form-control" name="input_day" id="input_day" min="1" value="{{old('input_day')}}" max="31" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="input_month"><b class="text-danger">*</b>Birth Month (MM)</label>
                                                <input type="number" class="form-control" name="input_month" id="input_month" value="{{old('input_month')}}" min="1" max="12" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="input_year"><b class="text-danger">*</b>Birth Year (YYYY)</label>
                                                <input type="number" class="form-control" name="input_year" id="input_year" value="{{old('input_year', request()->input('year'))}}" min="{{request()->input('year')}}" max="{{date('Y')}}" tabindex="-1" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <!--
                                        <div class="form-group">
                                            <label for="dob"><b class="text-danger">*</b>Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" id="dob" max="{{date('Y-m-d')}}" value="{{old('dob')}}" required>
                                        </div>
                                    -->
                                </div>
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
                    <div class="card mb-3 mt-3">
                        <div class="card-header"><b>Details and Address of PARENT (Preferrably MOTHER)</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="parent_lname"><b class="text-danger">*</b>Parent Last Name</label>
                                        <input type="text" class="form-control" name="parent_lname" id="parent_lname" value="{{old('parent_lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="parent_fname"><b class="text-danger">*</b>Parent First Name</label>
                                        <input type="text" class="form-control" name="parent_fname" id="parent_fname" value="{{old('parent_fname')}}" minlength="2" maxlength="50" placeholder="ex: MARCI" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="parent_mname">Parent Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="parent_mname" id="parent_mname" value="{{old('parent_mname')}}" minlength="2" maxlength="50" placeholder="ex: TANGGOL" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="parent_suffix">Parent Name Extension <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="parent_suffix" id="parent_suffix" value="{{old('parent_suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Region</label>
                                        <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Province</label>
                                        <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Municipality/City</label>
                                        <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Barangay</label>
                                        <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="street_purok" class="form-label"><b class="text-danger">*</b>Street/Purok/Subdivision</label>
                                <input type="text" class="form-control" id="street_purok" name="street_purok" style="text-transform: uppercase;" value="{{old('street_purok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="hospital_lyingin">Name of Hospital/Lying In Clinic</label>
                      <input type="text" class="form-control" name="hospital_lyingin" id="hospital_lyingin" value="{{old('hospital_lyingin')}}" style="text-transform: uppercase;">
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                              <label for=""><b class="text-danger">*</b>Age of the Mother</label>
                              <input type="number" class="form-control" name="mother_age" id="mother_age" min="10" max="60" value="{{old('mother_age')}}" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="mode_delivery"><b class="text-danger">*</b>Mode of Delivery</label>
                                <select class="form-control" name="mode_delivery" id="mode_delivery" required>
                                  <option value="N/A" {{(old('mode_delivery') == 'N/A') ? 'selected' : ''}}>N/A</option>
                                  <option value="NORMAL" {{(old('mode_delivery') == 'NORMAL') ? 'selected' : ''}}>Normal</option>
                                  <option value="CS" {{(old('mode_delivery') == 'CS') ? 'selected' : ''}}>CS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="multiple_delivery"><b class="text-danger">*</b>Multiple Deliveries?</label>
                                <select class="form-control" name="multiple_delivery" id="multiple_delivery" required>
                                    <option value="" disabled {{(is_null(old('multiple_delivery'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="NO" {{(old('multiple_delivery') == 'NO') ? 'selected' : ''}}>No</option>
                                    <option value="TWINS" {{(old('multiple_delivery') == 'TWINS') ? 'selected' : ''}}>Yes, Twins</option>
                                    <option value="TRIPLETS" {{(old('multiple_delivery') == 'TRIPLETS') ? 'selected' : ''}}>Yes, Triplets</option>
                                    <option value="QUADRUPLETS" {{(old('multiple_delivery') == 'QUADRUPLETS') ? 'selected' : ''}}>Yes, Quadruplets</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="datelcr"><b class="text-danger">*</b>Date Registered at the Office of the Civil Registrar</label>
                      <input type="date" class="form-control" name="datelcr" id="datelcr" min="{{Carbon\Carbon::create(request()->input('year'), 1, 1, 0, 0, 0)->format('Y-m-d')}}" max="{{Carbon\Carbon::create(request()->input('year'), 12, 31, 0, 0, 0)->format('Y-m-d')}}" value="{{old('datelcr')}}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
                </div>
            </div>
        </form>
    </div>

    <form action="{{route('fhsis_livebirth_encode')}}" method="GET">
        <div class="modal fade" id="changeMonth" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Encode Livebirths</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="year"><b class="text-danger">*</b>Year</label>
                          <input type="number" class="form-control" name="year" id="year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{date('Y')}}" required>
                        </div>
                        <div class="form-group">
                          <label for="month"><b class="text-danger">*</b>Month</label>
                          <select class="form-control" name="month" id="month" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="1" >January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Start</button>
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
          }, 2000);
          return false;
      }
  });

    $(document).ready(function () {
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