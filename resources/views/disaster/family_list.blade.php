@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3"><a href="{{route('gtsecure_index')}}" class="btn btn-secondary">Go Back</a></div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Evacuation Centers - Family Masterlist</b></div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newFamilyHead">
                        New Family Head
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mainTbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Head ID</th>
                                <th>Head of the Family</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Street/Purok</th>
                                <th>Barangay</th>
                                <th>City/Municipality</th>
                                <th>No. of Family Member/s</th>
                                <th>Created at/by</th>
                                <th>Updated at/by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $l)
                            <tr>
                                <td class="text-center">{{$l->id}}</td>
                                <td><a href="{{route('disaster_viewfamilyhead', $l->id)}}">{{$l->getName()}}</a></td>
                                <td class="text-center">{{$l->getAge()}}</td>
                                <td class="text-center">{{$l->sex}}</td>
                                <td class="text-center">{{$l->street_purok}}</td>
                                <td class="text-center">{{$l->brgy->name}}</td>
                                <td class="text-center">{{$l->brgy->city->name}}</td>
                                <td class="text-center">{{$l->getNumberOfMembers()}}</td>
                                <td class="text-center">
                                    <div>{{date('m/d/Y h:i A')}}</div>
                                    <div>by {{$l->user->name}}</div>
                                </td>
                                <td class="text-center">
                                    {{$l->getUpdatedBy()}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('disaster_storefamilyhead')}}" method="POST">
        @csrf
        <div class="modal fade" id="newFamilyHead" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Add Family Head</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <b class="text-danger">Note:</b> All fields marked with an asterisk (<b class="text-danger">*</b>) are required to be filled-out properly.
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                    <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="birthplace">Birthplace</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace" value="{{old('birthplace')}}" style="text-transform: uppercase">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                    <select class="form-control" name="sex" id="sex" required>
                                    <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                    <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                    <select class="form-control" name="cs" id="cs" required>
                                    <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SINGLE" {{(old('cs') == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs') == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Primary Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                    <small><b class="text-info">Note:</b> Kung wala talagang contact number, pwedeng ilagay ang contact number ng kapitbahay ng evacuees.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_number2">Alternate Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2')}}" pattern="[0-9]{11}" placeholder="09*********">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" value="{{old('religion')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="monthlyfamily_income">Monthly Family Net Income</label>
                                    <input type="number" class="form-control" id="monthlyfamily_income" name="monthlyfamily_income" value="{{old('monthlyfamily_income')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_presented"><b class="text-danger">*</b>ID Card Presented</label>
                                    <input type="text" class="form-control" id="id_presented" name="id_presented" value="{{old('id_presented')}}" style="text-transform: uppercase;" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_number"><b class="text-danger">*</b>ID Card Number</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" value="{{old('id_number')}}" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="house_ownership"><span class="text-danger font-weight-bold">*</span>House Ownership</label>
                                    <select class="form-control" name="house_ownership" id="house_ownership" required>
                                        <option value="" disabled {{(is_null(old('house_ownership'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="OWNER" {{(old('house_ownership') == 'OWNER') ? 'selected' : ''}}>Owner/May ari ng bahay</option>
                                        <option value="RENTER" {{(old('house_ownership') == 'RENTER') ? 'selected' : ''}}>Renter/Nakiki-renta lang</option>
                                        <option value="SHARER" {{(old('house_ownership') == 'SHARER') ? 'selected' : ''}}>Sharer</option>
                                        <option value="INFORMAL SETTLER" {{(old('house_ownership') == 'INFORMAL SETTLER') ? 'selected' : ''}}>Informal Settler</option>
                                        <option value="N/A" {{(old('house_ownership') == 'N/A') ? 'selected' : ''}}>Not Applicable (N/A)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mothermaiden_name">Mother's Maiden Name/Pangalan ng Nanay nung Dalaga pa</label>
                                    <input type="text" class="form-control" id="mothermaiden_name" name="mothermaiden_name" value="{{old('mothermaiden_name')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_soloparent"><span class="text-danger font-weight-bold">*</span>Is Solo Parent?</label>
                                    <select class="form-control" name="is_soloparent" id="is_soloparent" required>
                                        <option value="" {{(is_null(old('is_soloparent'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_soloparent') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_soloparent') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps Beneficiary?</label>
                                    <select class="form-control" name="is_4ps" id="is_4ps" required>
                                        <option value="" {{(is_null(old('is_4ps'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_4ps') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_4ps') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigenous People?</label>
                                    <select class="form-control" name="is_indg" id="is_indg" required>
                                        <option value="" {{(is_null(old('is_indg'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_indg') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_indg') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header"><b>Permanent Address</b></div>
                            <div class="card-body">
                                <div class="row" id="address_div">
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
                                            <input type="text" class="form-control" id="street_purok" name="street_purok" value="{{old('street_purok')}}" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dswd_serialno">DSWD Serial No.</label>
                                    <input type="text" class="form-control" name="dswd_serialno" id="dswd_serialno" value="{{old('dswd_serialno')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cswd_serialno">CSWD Serial No.</label>
                                    <input type="text" class="form-control" name="cswd_serialno" id="cswd_serialno" value="{{old('cswd_serialno')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#mainTbl').dataTable({
            order: [[1, 'asc']],
        });

        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code, #family_patient_id').select2({
            theme: 'bootstrap',
            dropdownParent: $('#address_div'),
        });

        //Default Values for Gentri
        var regionDefault = 1;
        var provinceDefault = 18;
        var cityDefault = 388;

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
    </script>
@endsection