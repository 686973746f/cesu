@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Evacuation Center</b></div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#evacOptions">Options</button>
                        <a href="{{route('gtsecure_newpatient', $d->id)}}" class="btn btn-success">New Patient</a>
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
                                <th colspan="9">{{$d->name}}</th>
                            </tr>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Street/Purok</th>
                                <th>Barangay</th>
                                <th>Contact No.</th>
                                <th>Outcome</th>
                                <th>Created at/by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient_list as $ind => $p)
                            <tr>
                                <td class="text-center">{{$ind+1}}</td>
                                <td><a href="{{route('gtsecure_viewpatient', $p->id)}}">{{$p->getName()}}</a></td>
                                <td class="text-center">{{$p->getAge()}}</td>
                                <td class="text-center">{{$p->sex}}</td>
                                <td class="text-center">{{$p->street_purok}}</td>
                                <td class="text-center">{{$p->brgy->name}}</td>
                                <td class="text-center">{{$p->contact_number}}</td>
                                <td class="text-center">{{$p->outcome}}</td>
                                <td class="text-center">
                                    <div>{{date('M. d, Y h:i A', strtotime($p->created_at))}}</div>
                                    <div>by {{$p->user->name}}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="POST">
        @csrf
        <div class="modal fade" id="evacOptions" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Evacuation Center Options</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="enabled"><b class="text-danger">*</b>Enabled</label>
                            <select class="form-control" name="enabled" id="enabled" required>
                              <option value="Y" {{(old('enabled', $d->has_water) == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('enabled', $d->has_water) == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name"><b class="text-danger">*</b>Evacuation Center Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}"  style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}"  style="text-transform: uppercase;">
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
                        </div>
                        <div class="row">
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
                            <label for="street_purok">Street/Purok</label>
                            <input type="text" class="form-control" name="street_purok" id="street_purok" value="{{old('street_purok', $d->street_purok)}}"  style="text-transform: uppercase;">
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_electricity"><b class="text-danger">*</b>Has Electricity?</label>
                                    <select class="form-control" name="has_electricity" id="has_electricity" required>
                                      <option value="Y" {{(old('has_electricity', $d->has_electricity) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('has_electricity', $d->has_electricity) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_water"><b class="text-danger">*</b>Has Water?</label>
                                    <select class="form-control" name="has_water" id="has_water" required>
                                      <option value="Y" {{(old('has_water', $d->has_water) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('has_water', $d->has_water) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_communication"><b class="text-danger">*</b>Has Communication?</label>
                                    <select class="form-control" name="has_communication" id="has_communication" required>
                                      <option value="Y" {{(old('has_communication', $d->has_communication) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('has_communication', $d->has_communication) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="has_internet"><b class="text-danger">*</b>Has Internet?</label>
                                    <select class="form-control" name="has_internet" id="has_internet" required>
                                      <option value="Y" {{(old('has_internet', $d->has_internet) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('has_internet', $d->has_internet) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rcho_functional"><b class="text-danger">*</b>R/CHO Functional?</label>
                                    <select class="form-control" name="rcho_functional" id="rcho_functional" required>
                                      <option value="Y" {{(old('rcho_functional', $d->rcho_functional) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('rcho_functional', $d->rcho_functional) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bhs_functional"><b class="text-danger">*</b>BHS Functional?</label>
                                    <select class="form-control" name="bhs_functional" id="bhs_functional" required>
                                      <option value="Y" {{(old('bhs_functional', $d->bhs_functional) == 'Y') ? 'selected' : ''}}>Yes</option>
                                      <option value="N" {{(old('bhs_functional', $d->bhs_functional) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="remarks">Remarks</label>
                          <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $d->remarks)}}</textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="status"><b class="text-danger">*</b>Status</label>
                            <select class="form-control" name="status" id="status" required>
                              <option value="ACTIVE" {{(old('status', $d->has_water) == 'ACTIVE') ? 'selected' : ''}}>Active</option>
                              <option value="DONE" {{(old('status', $d->has_water) == 'DONE') ? 'selected' : ''}}>Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code').select2({
            theme: 'bootstrap',
            dropdownParent: $('#evacOptions'),
        });

         //Default Values for Gentri
        var regionDefault = {{$d->brgy->city->province->region->id}};
        var provinceDefault = {{$d->brgy->city->province->id}};
        var cityDefault = {{$d->brgy->city->id}};
        var brgyDefault = {{$d->brgy->id}}

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
        if (brgyDefault) {
            setTimeout(function() {
                $('#address_brgy_code').val(brgyDefault).trigger('change');
            }, 1500); // Slight delay to ensure city is loaded
        }
    </script>
@endsection