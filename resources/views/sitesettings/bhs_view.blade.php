@extends('layouts.app')

@section('content')
    <form action="{{route('settings_bhs_update', $d->id)}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>Viewing {{$d->name}}</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="name"><b class="text-danger">*</b>Name of BHS</label>
                              <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" value="{{old('name', $d->name)}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label for="brgy_id"><b class="text-danger">*</b>Link to Barangay ID</label>
                              <select class="form-control" name="brgy_id" id="brgy_id" required>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->id}}" {{($b->id == $d->brgy_id) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="assigned_personnel_name"><b class="text-danger">*</b>Name of Assigned Personnel</label>
                                <input type="text" class="form-control" name="assigned_personnel_name" id="assigned_personnel_name" style="text-transform: uppercase;" value="{{old('assigned_personnel_name', $d->assigned_personnel_name)}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="assigned_personnel_position"><b class="text-danger">*</b>Position/Designation of Assigned Personnel</label>
                                <input type="text" class="form-control" name="assigned_personnel_position" id="assigned_personnel_position" style="text-transform: uppercase;" value="{{old('assigned_personnel_position', $d->assigned_personnel_position)}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="assigned_personnel_contact_number">Contact Number</label>
                                <input type="text" class="form-control" name="assigned_personnel_contact_number" id="assigned_personnel_contact_number" style="text-transform: uppercase;" value="{{old('assigned_personnel_contact_number', $d->assigned_personnel_contact_number)}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sys_code1"><b class="text-danger">*</b>System Code</label>
                                <input type="text" class="form-control" name="sys_code1" id="sys_code1" style="text-transform: uppercase;" value="{{old('sys_code1', $d->sys_code1)}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sys_coordinate_x">GPS Coordinate X (Latitude)</label>
                                <input type="text" class="form-control" value="{{old('sys_coordinate_x', $d->sys_coordinate_x)}}" pattern="\d+(\.\d+)?" id="sys_coordinate_x" name="sys_coordinate_x">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sys_coordinate_y">GPS Coordinate Y (Longitude)</label>
                                <input type="text" class="form-control" value="{{old('sys_coordinate_y', $d->sys_coordinate_y)}}" pattern="\d+(\.\d+)?" id="sys_coordinate_y" name="sys_coordinate_y">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" name="changeLocation" id="changeLocation" class="btn btn-warning btn-block d-none">Change Location</button>
                        <button type="button" name="getCurrentLocation" id="getCurrentLocation" class="btn btn-primary btn-block">
                            <div>Tag Current Location as the Patient Location</div>
                            <div><small>(Requires location permission)</small></div>
                        </button>
                        <button type="button" name="cancelCurrentLocation" id="cancelCurrentLocation" class="btn btn-warning btn-block d-none">Cancel</button>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Update</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#brgy_id').select2({
            theme: 'bootstrap',
        });

        if($('#sys_coordinate_x').val()) {
            $('#sys_coordinate_x').prop('readonly', true);
            $('#sys_coordinate_y').prop('readonly', true);

            $('#changeLocation').removeClass('d-none');
            $('#getCurrentLocation').addClass('d-none');
        }
        
        $('#changeLocation').click(function (e) { 
            e.preventDefault();
            
            if(confirm('Changing location will remove the existing values of X and Y coordinates. Click OK to proceed.')) {
                $('#changeLocation').addClass('d-none');
                $('#getCurrentLocation').removeClass('d-none');

                $('#sys_coordinate_x').prop('readonly', false);
                $('#sys_coordinate_y').prop('readonly', false);

                $('#sys_coordinate_x').val('');
                $('#sys_coordinate_y').val('');
            }
            else {

            }
        });
        
        $('#getCurrentLocation').click(function (e) { 
            e.preventDefault();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                $('#sys_coordinate_x').val(latitude);
                $('#sys_coordinate_y').val(longitude);

                $('#sys_coordinate_x').prop('readonly', true);
                $('#sys_coordinate_y').prop('readonly', true);

                $('#cancelCurrentLocation').removeClass('d-none');
            }, function(error) {
                alert("Error occurred. Error code: " + error.code);
            });
            }
            else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        $('#cancelCurrentLocation').click(function (e) { 
            e.preventDefault();
            
            $('#sys_coordinate_x').val('');
            $('#sys_coordinate_y').val('');

            $('#sys_coordinate_x').prop('readonly', false);
            $('#sys_coordinate_y').prop('readonly', false);

            $('#cancelCurrentLocation').addClass('d-none');
        });

        $(document).ready(function () {
            $('#sys_coordinate_x').on('input', function() {
                // Check if field1 has a value
                
                if ($(this).val().trim() !== '') {
                    // Make field2 a required field
                    $('#sys_coordinate_y').prop('required', true);
                } else {
                    // Remove required attribute from field2
                    $('#sys_coordinate_y').prop('required', false);
                }
            });
        });
    </script>
@endsection