@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Evacuation Center</b></div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#evacOptions">Options</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addHead">Link Family Head</button>
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
                                <th colspan="10">{{$d->name}}</th>
                            </tr>
                            <tr>
                                <th>No.</th>
                                <th>Name of Family Head</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Street/Purok</th>
                                <th>Barangay</th>
                                <th>Contact No.</th>
                                <th>No. of Family Members</th>
                                <th>Outcome</th>
                                <th>Created at/by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($head_list as $ind => $p)
                            <tr>
                                <td class="text-center">{{$ind+1}}</td>
                                <td><a href="{{route('disaster_viewfamilyevac', [$d->id, $p->id])}}">{{$p->familyhead->getName()}}</a></td>
                                <td class="text-center">{{$p->familyhead->getAge()}}</td>
                                <td class="text-center">{{$p->familyhead->sex}}</td>
                                <td class="text-center">{{$p->familyhead->street_purok}}</td>
                                <td class="text-center">{{$p->familyhead->brgy->name}}</td>
                                <td class="text-center">{{$p->familyhead->contact_number}}</td>
                                <td class="text-center">{{$p->getNumberOfMembers()}}</td>
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

    <form action="{{route('disaster_linkfamily', $d->id)}}" method="POST">
        @csrf
        <div class="modal fade" id="addHead" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Add Family to Evacuation Center</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        @if($available_list->count() > 0)
                        <div class="form-group">
                          <label for="familyhead_id"><b class="text-danger">*</b>Select Family Head to Add in the Evacuation Center</label>
                          <select class="form-control" name="familyhead_id" id="familyhead_id" required>
                            <option value="" disabled {{(is_null(old('familyhead_id'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach($available_list as $l)
                            <option value="{{$l->id}}">{{$l->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="alert alert-primary" role="alert">
                            <b>Note:</b> Bago makapag-link ng Family Head sa isang Evacuation Center ay kailangan muna itong i-encode sa <a href="{{route('disaster_viewfamilies')}}">Family Masterlist</a>.
                        </div>
                        <div class="form-group">
                          <label for="date_registered"><b class="text-danger">*</b>Date Registered</label>
                          <input type="datetime-local" class="form-control" name="date_registered" id="date_registered" value="{{old('date_registered', date('Y-m-d H:i'))}}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_injured"><b class="text-danger">*</b>Is Injured?</label>
                                    <select class="form-control" name="is_injured" id="is_injured" required>
                                        <option value="" disabled {{(is_null(old('is_injured'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_admitted"><b class="text-danger">*</b>Is Admitted?</label>
                                    <select class="form-control" name="is_admitted" id="is_admitted" required>
                                        <option value="" disabled {{(is_null(old('is_admitted'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="admitted_div" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_admitted"><b class="text-danger">*</b>Date Admitted</label>
                                        <input type="date" class="form-control" name="date_admitted" id="date_admitted" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_discharged">Date Discharged</label>
                                        <input type="date" class="form-control" name="date_discharged" id="date_discharged" max="{{date('Y-m-d', strtotime('+1 Day'))}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                          <label for="shelterdamage_classification"><b class="text-danger">*</b>Shelter Damage Classification</label>
                          <select class="form-control" name="shelterdamage_classification" id="shelterdamage_classification" required>
                            <option value="" disabled {{(is_null(old('shelterdamage_classification'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="PARTIALLY DAMAGED">Partially Damaged</option>
                            <option value="TOTALLY DAMAGED">Totally Damaged</option>
                          </select>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="focal_name">Name of C/DSWD Focal</label>
                          <input type="text" class="form-control" name="focal_name" id="focal_name" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                          <label for="supervisor_name">Name of C/DSWD Immediate Supervisor</label>
                          <input type="text" class="form-control" name="supervisor_name" id="supervisor_name" style="text-transform: uppercase;">
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="remarks">Remarks</label>
                          <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                        </div>
                        @else
                        <div class="alert alert-warning" role="alert">
                            <h5><b>No Family Heads and Members available to link to this Evacuation Center.</b></h5>
                            <hr>
                            <h6>To link a new evacuee/s, please encode their family data first at the <b><a href="{{route('disaster_viewfamilies')}}">Family Masterlist</a></b> Page.</h6>
                        </div>
                        @endif
                    </div>
                    @if($available_list->count() > 0)
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

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

        $("#select_family_id").select2({
			theme: "bootstrap",
		});

        $('#is_admitted').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#admitted_div').removeClass('d-none');
                $('#date_admitted').prop('required', true);
            }
            else {
                $('#admitted_div').addClass('d-none');
                $('#date_admitted').prop('required', false);
            }
        }).trigger('change');


    </script>
@endsection