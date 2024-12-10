@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Patients</b> (Event Name: {{$d->event_name}})</div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPatient">Add Patient</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Barangay</th>
                        <th>Chief Complaint</th>
                        <th>Created at/by</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $l)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td>
                            <a href="{{route('online_duty_editpatient', [$d->id, $l->id])}}">{{$l->getName()}}</a>
                        </td>
                        <td class="text-center">{{$l->age_years}}</td>
                        <td class="text-center">{{$l->sex}}</td>
                        <td class="text-center">{{($l->address_brgy_code) ? $l->brgy->name : 'N/A'}}</td>
                        <td class="text-center">{{$l->chief_complaint}}</td>
                        <td class="text-center">
                            <div>{{date('M. d, Y h:i A', strtotime($l->created_at))}}</div>
                            @if(!is_null($l->created_by))
                            <div>by {{$l->user->name}}</div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{$store_route}}" method="POST">
    @csrf
    <div class="modal fade" id="newPatient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>New Patient</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="lname"><b class="text-danger">*</b>Last Name</label>
                      <input type="text" class="form-control" name="lname" id="lname" style="text-transform: uppercase" required>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="fname"><b class="text-danger">*</b>First Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Initial</label>
                                <input type="text" class="form-control" name="mname" id="mname" style="text-transform: uppercase" minlength="1">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="age_years"><b class="text-danger">*</b>Age (in Years)</label>
                                <input type="number" class="form-control" name="age_years" id="age_years" min="0" max="150" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender"><b class="text-danger">*</b>Sex</label>
                                <select class="form-control" name="sex" id="sex" required>
                                  <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                  <option value="F" {{(old('sex',) == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
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
                                <label for="address_brgy_code">Barangay</label>
                                <select class="form-control" name="address_brgy_code" id="address_brgy_code" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="street_purok">House No., Street/Purok/Subdivision</label>
                                <input type="text" class="form-control" id="street_purok" name="street_purok" value="{{old('street_purok')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********">
                    </div>
                    <hr>
                    <div id="chief_complaint_div">
                        <div class="form-group">
                            <label for="chief_complaint"><b class="text-danger">*</b>Chief Complaint <i>(Select all that applies)</i></label>
                            <select class="form-control" name="chief_complaint[]" id="chief_complaint" multiple required>
                              <option value="PASSED OUT">Passed Out</option>
                              <option value="FRACTURE">Fracture</option>
                              <option value="INJURY/WOUND">Injury/Wounds</option>
                              <option value="FEVER">Fever</option>
                              <option value="DIZZINESS">Dizziness</option>
                              <option value="HEADACHE">Headache</option>
                              <option value="STOMACH PAIN">Stomach Pain</option>
                              <option value="VOMITING">Vomiting</option>
                              <option value="SEIZURE">Seizure</option>
                              <option value="DIFFICULTY OF BREATHING">Difficulty Of Breathing</option>
                              <option value="CHECK BP">Check Blood Pressure (BP)</option>
                              <option value="OTHERS">Others</option>
                            </select>
                          </div>
                    </div>
                    <div id="ifCheckBP" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bp1"><b class="text-danger">*</b>Systolic</label>
                                    <input type="number" class="form-control" name="bp1" id="bp1" min="1" max="300">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bp2"><b class="text-danger">*</b>Diastolic</label>
                                    <input type="number" class="form-control" name="bp2" id="bp2" min="1" max="300">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ifOthers" class="d-none">
                        <div class="form-group">
                            <label for="other_complains"><b class="text-danger">*</b>List Other Complaints</label>
                            <input type="text" class="form-control" name="other_complains" id="other_complains" style="text-transform: uppercase">
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="lastmeal_taken"><b class="text-danger">*</b>Last Meal Taken</label>
                      <select class="form-control" name="lastmeal_taken" id="lastmeal_taken" required>
                        <option value="" disabled {{(is_null(old('lastmeal_taken'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="BREAKFAST">Breakfast</option>
                        <option value="LUNCH">Lunch</option>
                        <option value="PM SNACK">PM Snack</option>
                        <option value="DINNFER">Dinner</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <input type="text" class="form-control" name="diagnosis" id="diagnosis" style="text-transform: uppercase">
                    </div>
                    <div class="form-group">
                        <label for="actions_taken">Actions Taken/Meds Given</label>
                        <textarea class="form-control" name="actions_taken" id="actions_taken" rows="3" style="text-transform: uppercase">{{old('actions_taken')}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea class="form-control" name="remarks" id="remarks" rows="2" style="text-transform: uppercase">{{old('remarks')}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Save (CTRL + S)</button>
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

    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code, #chief_complaint').select2({
        theme: 'bootstrap',
        dropdownParent: $("#chief_complaint_div"),
    });

    $('#chief_complaint').change(function (e) { 
        e.preventDefault();
        
        let selectedValues = $(this).val();

        if (selectedValues && selectedValues.includes('CHECK BP')) {
            $('#ifCheckBP').removeClass('d-none');
            $('#bp1').prop('required', true);
            $('#bp2').prop('required', true);
        } else {
            $('#ifCheckBP').addClass('d-none');
            $('#bp1').prop('required', false);
            $('#bp2').prop('required', false);
        }

        if (selectedValues && selectedValues.includes('OTHERS')) {
            $('#ifOthers').removeClass('d-none');
            $('#other_complains').prop('required', true);
        } else {
            $('#ifOthers').addClass('d-none');
            $('#other_complains').prop('required', false);
        }
    }).trigger('change');

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