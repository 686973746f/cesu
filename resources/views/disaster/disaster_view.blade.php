@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>View Disaster</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newEvacuationCenter">Add Evacuation Center</button>
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
                        <th>Evacuation Center</th>
                        <th>Barangay</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_evac as $ind => $l)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td>{{$l->name}}</td>
                        <td class="text-center">{{$l->brgy->name}}</td>
                        <td class="text-center">
                            <a href="{{route('gtsecure_evacuationcenter_view', $l->id)}}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{route('gtsecure_evacuationcenter_store', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="newEvacuationCenter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Evacuation Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name"><b class="text-danger">*</b>Evacuation Center Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}"  style="text-transform: uppercase;" required>
                    </div>
                    <div class="form-group">
                        <label for="name"><b class="text-danger">*</b>Date Started</label>
                      <input type="date" class="form-control" name="date_start" id="date_start" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('date_start', date('Y-m-d'))}}" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="address_region_code"><b class="text-danger">*</b>Region</label>
                        <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                          @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                          <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="form-group">
                        <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                        <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                        </select>
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
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code').select2({
        theme: 'bootstrap',
        dropdownParent: $('#newEvacuationCenter'),
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