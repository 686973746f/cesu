@extends('layouts.app')

@section('content')
    <div class="container">
        @if(auth()->user()->isAdminSyndromic())
        <div class="text-right mb-3">
            <form action="{{route('syndromic_deletePatient', $d->id)}}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('You cannot undo this process. Are you sure you want to delete this Patient?')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete this Patient</button>
            </form>
        </div>
        @endif

        <form action="{{route('syndromic_updatePatient', $d->id)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>Edit Patient Details</b> - Patient ID: {{$d->id}}</div>
                        <div><b>Date Encoded:</b> {{date('m/d/Y h:i A', strtotime($d->created_at))}} by {{$d->user->name}} @if(!is_null($d->updated_by)) | <b>Date Updated:</b> {{date('m/d/Y h:i A', strtotime($d->updated_at))}} by {{$d->getUpdatedBy->name}}@endif</div>
                    </div>
                    @if($has_record)
                    <hr>
                    <a href="{{route('syndromic_viewItrList', $d->id)}}" class="btn btn-block btn-outline-primary">VIEW PREVIOUS CONSULTATION/S</a>
                    @else
                    <a href="{{route('syndromic_newRecord', $d->id)}}" class="btn btn-block btn-outline-success">New ITR</a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    @if(auth()->user()->isSyndromicHospitalLevelAccess())
                    <div class="row">
                        <div class="col-md-6">
                            @if(is_null($d->unique_opdnumber))
                            <div class="form-group">
                                <label for="unique_opdnumber"><b class="text-danger">*</b>Unique OPD Number</label>
                                <input type="number" class="form-control" id="unique_opdnumber" name="unique_opdnumber" value="{{old('unique_opdnumber', $d->unique_opdnumber)}}" required>
                            </div>
                            @else
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Unique OPD Number</label>
                                <input type="number" class="form-control" value="{{$d->unique_opdnumber}}" disabled>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_presented"><b class="text-danger">*</b>ID Presented</label>
                                <input type="text" class="form-control" name="id_presented" id="id_presented" value="{{old('id_presented', $d->id_presented)}}" required>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->lname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->fname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $d->mname)}}" max="50" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $d->suffix)}}" max="50" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->bdate)}}">
                                <small>Age: {{$d->getAge()}}</small>
                            </div>
                            <div class="form-group">
                                <label for="isph_member"><b class="text-danger">*</b>Philhealth Member/Dependent?</label>
                                <select class="form-control" name="isph_member" id="isph_member" required>
                                    <option value="N" {{(old('isph_member') == 'N' || $d->isph_member == 0) ? 'selected' : ''}}>No (NN)</option>
                                    <option value="Y" {{(old('isph_member') == 'Y' || $d->isph_member == 1) ? 'selected' : ''}}>Yes (NH)</option>
                                </select>
                              </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                  <select class="form-control" name="gender" id="gender" required>
                                      <option value="MALE" {{(old('gender', $d->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                      <option value="FEMALE" {{(old('gender', $d->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                  </select>
                            </div>
                            <div class="form-group">
                                <label for="philhealth">Philhealth # (Optional)</label>
                                <input type="text" class="form-control" name="philhealth" id="philhealth" value="{{old('philhealth', $d->philhealth)}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                <select class="form-control" id="cs" name="cs" required>
                                    <option value="SINGLE" {{(old('cs', $d->cs) == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs', $d->cs) == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                    <option value="WIDOWED" {{(old('cs', $d->cs) == 'WIDOWED') ? 'selected' : ''}}>Widowed</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="ifmarried_div">
                                <label for="spouse_name">Spouse Name</label>
                                <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name', $d->spouse_name)}}" style="text-transform: uppercase;">
                              </div>
                            <div class="form-group">
                              <label for="">Email Address (Optional)</label>
                              <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->email)}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                            <div class="form-group">
                                <label for="contact_number2">Contact Number 2 (Optional)</label>
                                <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2', $d->contact_number2)}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="mother_name">Mother's Name</label>
                              <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{old('mother_name', $d->mother_name)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="father_name">Father's Name</label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="{{old('father_name', $d->father_name)}}" style="text-transform: uppercase;">
                              </div>
                        </div>
                    </div>
                    @if($d->getAge() <= 17)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="ifminor_resperson">Patient is minor, input Name of Responsible Person/Guardian/Parent</label>
                              <input type="text" class="form-control" name="ifminor_resperson" id="ifminor_resperson" value="{{old('ifminor_resperson', $d->ifminor_resperson)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="ifminor_resrelation">Relationship</label>
                              <select class="form-control" name="ifminor_resrelation" id="ifminor_resrelation">
                                <option value="" {{(is_null(old('ifminor_resrelation', $d->ifminor_resrelation))) ? 'selected' : ''}}>None</option>
                                <option value="PARENT" {{(old('ifminor_resrelation', $d->ifminor_resrelation) == 'PARENT') ? 'selected' : ''}}>Parent/Magulang</option>
                                <option value="SIBLING" {{(old('ifminor_resrelation', $d->ifminor_resrelation) == 'SIBLING') ? 'selected' : ''}}>Sibling/Kapatid</option>
                                <option value="OTHERS" {{(old('ifminor_resrelation', $d->ifminor_resrelation) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div id="address_text" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text', $d->address_province_text)}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text', $d->address_muncity_text)}}" readonly>
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
                                <label for="address_houseno" class="form-label">House No./Lot/Building</label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno', $d->address_houseno)}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="">
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
                    <div class="form-group">
                        <label for="is_lgustaff"><b class="text-danger">*</b>Is Patient a LGU/Government Employee?</label>
                        <select class="form-control" name="is_lgustaff" id="is_lgustaff" required>
                            <option value="" {{(is_null(old('is_lgustaff', $d->is_lgustaff))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('is_lgustaff', $d->is_lgustaff) == 1) ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{(old('is_lgustaff', $d->is_lgustaff) == 0) ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="if_lgustaff">
                        <label for="lgu_office_name"><b class="text-danger">*</b>Name of LGU/Government Office</label>
                        <input type="text" class="form-control" name="lgu_office_name" id="lgu_office_name" value="{{old('lgu_office_name', $d->lgu_office_name)}}" style="text-transform: uppercase;">
                    </div>
                    @if($d->userHasPermissionToShareAccess())
                    <hr>
                    <div class="form-group">
                        <label for="shared_access_list">Share Patient Access to User/s:</label>
                        <select class="form-control" name="shared_access_list[]" id="shared_access_list" multiple>
                            @foreach($sal as $i)
                            <option value="{{$i->id}}" {{(collect(old('shared_access_list', explode(',', $d->shared_access_list)))->contains($i->id)) ? 'selected' : ''}}>{{mb_strtoupper($i->name)}} - ID: {{$i->id}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Update (CTRL + S)</button>
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

        $('#isph_member').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#philhealth').prop('readonly', false);
            }
            else {
                $('#philhealth').prop('readonly', true);
                $('#philhealth').val('');
            }
        }).trigger('change');

        $('#is_lgustaff').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#if_lgustaff').removeClass('d-none');
                $('#lgu_office_name').prop('required', true);
            }
            else {
                $('#if_lgustaff').addClass('d-none');
                $('#lgu_office_name').prop('required', false);
            }
        }).trigger('change');
        
        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text, #shared_access_list').select2({
            theme: 'bootstrap',
        });

        var rdefault = "{{old('address_region_code', $d->address_region_code)}}";
        var pdefault = "{{old('address_province_code', $d->address_province_code)}}";
        var cdefault = "{{old('address_muncity_code', $d->address_muncity_code)}}";
        var bdefault = "{{old('address_brgy_text', $d->address_brgy_text)}}";

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
                        selected: (val.regCode == rdefault) ? true : false, //default is Region IV-A
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
                                selected: (val.provCode == pdefault) ? true : false, //default for Cavite
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
                                selected: (val.citymunCode == cdefault) ? true : false, //default for General Trias
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
                                selected: (val.brgyDesc.toUpperCase() == bdefault) ? true : false,
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

            $('#address_region_text').val('{{$d->address_region_text}}');
            $('#address_province_text').val('{{$d->address_province_text}}');
            $('#address_muncity_text').val('{{$d->address_muncity_text}}');
        });

        $('#cs').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'MARRIED') {
                $('#ifmarried_div').removeClass('d-none');
                //$('#spouse_name').prop('required', true);
            }
            else {
                $('#ifmarried_div').addClass('d-none');
                //$('#spouse_name').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection