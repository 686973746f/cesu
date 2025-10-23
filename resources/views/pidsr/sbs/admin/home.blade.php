@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>SBDS Admin Panel</b> (Total Schools: {{$list->count()}})</div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newSchool">Add School</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Ownership Type</th>
                            <th>School Type</th>
                            <th>School ID</th>
                            <th>Barangay</th>
                            <th>City/Municipality</th>
                            <th>Name of Principal/School Head/OIC</th>
                            <th>Position</th>
                            <th>Name of DSO Focal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td class="text-center">{{$l->id}}</td>
                            <td><a href="">{{$l->name}}</a></td>
                            <td class="text-center">{{$l->ownership_type}}</td>
                            <td class="text-center">{{$l->school_type}}</td>
                            <td class="text-center">{{$l->school_id}}</td>
                            <td class="text-center">{{$l->brgy->name}}</td>
                            <td class="text-center">{{$l->brgy->city->name}}</td>
                            <td class="text-center">{{$l->schoolhead_name}}</td>
                            <td class="text-center">{{$l->schoolhead_position}}</td>
                            <td class="text-center">{{$l->focalperson_name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{route('sbs_storeschool')}}" method="POST">
        @csrf
        <div class="modal fade" id="newSchool" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add School</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name"><b class="text-danger">*</b>Name of School</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ownership_type"><span class="text-danger font-weight-bold">*</span>Ownership Type</label>
                                    <select class="form-control" name="ownership_type" id="ownership_type" required>
                                        <option value="PUBLIC" {{(old('ownership_type') == 'PUBLIC') ? 'selected' : ''}}>Public School</option>
                                        <option value="PRIVATE" {{(old('ownership_type') == 'PRIVATE') ? 'selected' : ''}}>Private School</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_type"><span class="text-danger font-weight-bold">*</span>School Type</label>
                                    <select class="form-control" name="school_type" id="school_type" required>
                                        <option value="" disabled {{(is_null(old('school_type'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="ES" {{(old('school_type') == 'ES') ? 'selected' : ''}}>Elementary School</option>
                                        <option value="JHS" {{(old('school_type') == 'JHS') ? 'selected' : ''}}>Junior High School</option>
                                        <option value="SHS" {{(old('school_type') == 'SHS') ? 'selected' : ''}}>Senior High School</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="school_id">School ID</label>
                            <input type="number" class="form-control" name="school_id" id="school_id" value="{{old('admitted_facility')}}" min="1">
                        </div>
                        <hr>
                        <div id="select2_address_div">
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
                                        <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                                        <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="schoolhead_name"><b class="text-danger">*</b>Name of Principal/School Head</label>
                            <input type="text" class="form-control" name="schoolhead_name" id="schoolhead_name" value="{{old('schoolhead_name')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="schoolhead_position"><b class="text-danger">*</b>Position/Designation of Principal/School Head</label>
                            <input type="text" class="form-control" name="schoolhead_position" id="schoolhead_position" value="{{old('schoolhead_position')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Mobile Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                        <div class="form-group">
                            <label for="contact_number_telephone">Telephone Number</label>
                            <input type="text" class="form-control" id="contact_number_telephone" name="contact_number_telephone" value="{{old('contact_number_telephone')}}">
                        </div>
                        <div class="form-group">
                            <label for="focalperson_name">Name of Focal Person (Disease Surveillance Coordinator)</label>
                            <input type="text" class="form-control" name="focalperson_name" id="focalperson_name" value="{{old('focalperson_name')}}" style="text-transform: uppercase;">
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
            theme: 'bootstrap',
            order: [[1, 'asc']],
        });

        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code').select2({
            theme: 'bootstrap',
            dropdownParent: $('#select2_address_div'),
        });

        //Default Values for Gentri
        var regionDefault = 1;
        var provinceDefault = 18;
        var cityDefault = 388;

        $('#address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', true);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_province_code').empty();
                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
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
        }).trigger('change');

        $('#address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
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
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', false);

                $('#address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
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