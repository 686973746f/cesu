@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>BLS/SFA Training Batch List</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newBatch">New Batch</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addParticipant">Create Participant Data</button>
                    <a href="{{route('bls_home_masterlist')}}" class="btn btn-primary">Switch to Masterlist View</a>
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
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Batch Number</th>
                            <th>Batch Name</th>
                            <th>Refresher</th>
                            <th>Agency</th>
                            <th>Date of Training</th>
                            <th>Venue</th>
                            <th>Created by/at</th>
                            <th>Updated by/at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">{{$d->id}}</td>
                            <td class="text-center">{{$d->batch_number}}</td>
                            <td><a href="{{route('bls_viewbatch', $d->id)}}"><b>{{$d->batch_name}}</b></a></td>
                            <td class="text-center">{{$d->is_refresher}}</td>
                            <td class="text-center">{{$d->agency}}</td>
                            <td class="text-center">
                                <div>{{Carbon\Carbon::parse($d->training_date_start)->format('F d, Y')}}</div>
                                @if($d->training_date_end)
                                <div>to {{Carbon\Carbon::parse($d->training_date_end)->format('F d, Y')}}</div>
                                @endif
                            </td>
                            <td class="text-center">{{$d->venue}}</td>
                            <td class="text-center">
                                <div>{{Carbon\Carbon::parse($d->created_at)->format('m/d/Y h:i A')}}</div>
                                <div>by {{$d->user->name}}</div>
                            </td>
                            <td class="text-center">
                                @if($d->getUpdatedBy())
                                <div>{{Carbon\Carbon::parse($d->updated_at)->format('m/d/Y h:i A')}}</div>
                                <div>by {{$d->getUpdatedBy->name}}</div>
                                @else
                                <div>N/A</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{route('bls_storebatch')}}" method="POST">
    @csrf
    <div class="modal fade" id="newBatch" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Batch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="batch_number"><b class="text-danger">*</b>Batch Number</label>
                      <input type="text" class="form-control" name="batch_number" id="batch_number" style="text-transform: uppercase" value="{{old('batch_number')}}" pattern="[0-9]" required>
                    </div>
                    <div class="form-group">
                        <label for="batch_name"><b class="text-danger">*</b>Batch Name</label>
                        <input type="text" class="form-control" name="batch_name" id="batch_name" style="text-transform: uppercase" value="{{old('batch_name')}}" required>
                    </div>
                    <div class="form-check mb-3">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="is_refresher" id="is_refresher" value="1">
                        Is Refresher Course?
                      </label>
                    </div>
                    <div class="form-group">
                        <label for="batch_number"><b class="text-danger">*</b>Agency</label>
                        <input type="text" class="form-control" name="agency" id="agency" style="text-transform: uppercase" value="{{old('agency', 'GENERAL TRIAS CITY HEALTH OFFICE')}}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="training_date_start"><b class="text-danger">*</b>Start of Training</label>
                                <input type="date" class="form-control" name="training_date_start" id="training_date_start" value="{{old('training_date_start')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="training_date_end">End of Training</label>
                                <input type="date" class="form-control" name="training_date_end" id="training_date_end" value="{{old('training_date_end')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="venue"><b class="text-danger">*</b>Venue</label>
                        <input type="text" class="form-control" name="venue" id="venue" style="text-transform: uppercase" value="{{old('venue')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="instructors_list"><b class="text-danger">*</b>List of Instructors (Separate by Slash [/])</label>
                        <input type="text" class="form-control" name="instructors_list" id="instructors_list" style="text-transform: uppercase" value="{{old('instructors_list')}}" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="prepared_by"><b class="text-danger">*</b>Prepared By</label>
                        <input type="text" class="form-control" name="prepared_by" id="prepared_by" style="text-transform: uppercase" value="{{old('prepared_by')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('bls_storemember', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="addParticipant" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Participant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="cho_employee" id="cho_employee" value="1">
                        Is CHO Employee?
                      </label>
                    </div>
                    <div id="cho_employee_fields" class="d-none">
                        <div class="form-group mt-2">
                          <label for="employee_id"><b class="text-danger">*</b>Link to Employee ID</label>
                          <select class="form-control" name="employee_id" id="employee_id">
                          </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Surname</label>
                        <input type="text" class="form-control" name="lname" id="lname" style="text-transform: uppercase" value="{{old('lname')}}" minlength="2" max="50" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" style="text-transform: uppercase" value="{{old('fname')}}" minlength="2" max="50" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" style="text-transform: uppercase" value="{{old('mname')}}" minlength="2" max="50">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Name Extension (Jr./Sr./III/IV, etc.)</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" style="text-transform: uppercase" value="{{old('suffix')}}" minlength="2" max="50">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bdate"><b class="text-danger">*</b>Date of Birth</label>
                                <input type="date" class="form-control" name="bdate" id="bdate" max="{{date('Y-m-d')}}" value="{{old('bdate')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="provider_type"><b class="text-danger">*</b>Provider Type</label>
                                <select class="form-control" name="provider_type" id="provider_type" required>
                                  <option value="" disabled {{(is_null(old('provider_type'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="HCP" {{(old('provider_type') == 'HCP') ? 'selected' : ''}}>Health Care Provider (HCP)</option>
                                  <option value="LR" {{(old('provider_type') == 'LR') ? 'selected' : ''}}>Lay Rescuer (LR)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="position"><b class="text-danger">*</b>Position</label>
                        <input type="text" class="form-control" name="position" id="position" style="text-transform: uppercase" value="{{old('position')}}" required>
                    </div>
                    <div id="institution_fields">
                        <div class="form-group mt-2">
                            <label for="institution"><b class="text-danger">*</b>Institution/Agency</label>
                            <select class="form-control" name="institution" id="institution" required>
                                <option value="" disabled {{(is_null(old('provider_type'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach($list_institutions as $a)
                                <option value="{{$a}}" {{(old('institution') == $a) ? 'selected' : ''}}>{{$a}}</option>
                                @endforeach
                                <option value="UNLISTED" {{(old('institution') == 'UNLISTED') ? 'selected' : ''}}>UNLISTED</option>
                            </select>
                          </div>
                        <div class="form-group d-none" id="institution_other_fields">
                            <label for="institution_other"><b class="text-danger">*</b>Please Specify</label>
                            <input type="text" class="form-control" name="institution_other" id="institution_other" style="text-transform: uppercase" value="{{old('institution_other')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employee_type"><b class="text-danger">*</b>Status of Employment</label>
                        <select class="form-control" name="employee_type" id="employee_type" required>
                          <option value="" disabled {{(is_null(old('employee_type'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="JO" {{(old('employee_type') == 'JO') ? 'selected' : ''}}>Job Order (JO)</option>
                          <option value="CASUAL" {{(old('employee_type') == 'CASUAL') ? 'selected' : ''}}>Casual</option>
                          <option value="CWA" {{(old('employee_type') == 'CWA') ? 'selected' : ''}}>Contract of Service (CWA)</option>
                          <option value="PERMANENT" {{(old('employee_type') == 'PERMANENT') ? 'selected' : ''}}>Permanent</option>
                        </select>
                    </div>
                    <hr>
                    <div class="row" id="address_fields">
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
                    </div>
                    <div class="form-group">
                        <label for="street_purok"><b class="text-danger">*</b>Street/Purok/Sitio/Subdivision</label>
                        <input type="text" class="form-control" name="street_purok" id="street_purok" style="text-transform: uppercase" value="{{old('street_purok')}}" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="email"><b class="text-danger">*</b>Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                    </div>
                    <div class="form-group">
                        <label for="codename"><b class="text-danger">*</b>Code Name</label>
                        <input type="text" class="form-control" name="codename" id="codename" style="text-transform: uppercase" value="{{old('codename')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#institution').select2({
        theme: 'bootstrap',
        dropdownParent: $('#institution_fields'),
    });

    $('#institution').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'UNLISTED') {
            $('#institution_other_fields').removeClass('d-none');
            $('#institution_other').prop('required', true);
        } else {
            $('#institution_other_fields').addClass('d-none');
            $('#institution_other').prop('required', false);
        }
    }).trigger('change');

    $('#employee_id').select2({
        dropdownParent: $('#cho_employee_fields'),
        theme: "bootstrap",
        placeholder: 'Search by Name / Employee ID ...',
        ajax: {
            url: "{{route('bls_ajax_listemployees')}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code').select2({
        theme: 'bootstrap',
        dropdownParent: $('#address_fields'),
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

    $('#cho_employee').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#cho_employee_fields').removeClass('d-none');
            $('#employee_id').prop('required', true);
        } else {
            $('#cho_employee_fields').addClass('d-none');
            $('#employee_id').prop('required', false);
        }
    });
</script>
@endsection