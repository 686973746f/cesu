@extends('layouts.app')

@section('content')
<style>
    #map { height: 200px; }
</style>

<div class="container">
    @php
    if($disease == 'SARI') {
        $epi_id = $d->epi_id;
    }
    else {
        $epi_id = $d->EPIID;
    }
    @endphp
    <form action="{{(!request()->is('*barangayportal*')) ? route('pidsr_casechecker_update', [$disease, $epi_id]) : route('edcs_barangay_update_cif', [$disease, $epi_id])}}" method="POST">
        @csrf
        <input type="hidden" class="form-control" name="fromVerifier" id="fromVerifier" value="{{(request()->input('fromVerifier')) ? 1 : 0}}">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Quick Edit Case</b></div>
                    <div>
                        <div>Date Created: {{date('F d, Y h:i A', strtotime($d->created_at))}}</div>
                        <div>Date Updated: {{date('F d, Y h:i A', strtotime($d->updated_at))}}</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Disease</label>
                          <input type="text" class="form-control" value="{{$disease}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>EPI ID</label>
                            <input type="text" class="form-control" value="{{$d->EPIID}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FamilyName"><b class="text-danger">*</b>Last Name/Surname</label>
                            <input type="text" class="form-control" value="{{old('FamilyName', $d->FamilyName)}}" id="FamilyName" name="FamilyName" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FirstName"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" value="{{old('FirstName', $d->FirstName)}}" id="FirstName" name="FirstName" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" value="{{old('middle_name', $d->middle_name)}}" id="middle_name" name="middle_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" value="{{old('suffix', $d->suffix)}}" id="suffix" name="suffix">
                        </div>
                    </div>
                </div>
                
                @if (!request()->is('*barangayportal*'))
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Barangay"><b class="text-danger">*</b>Barangay</label>
                            <select class="form-control" name="Barangay" id="Barangay" required>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->id}}" {{($b->brgyName == old('Barangay', $d->Barangay)) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="system_subdivision_id"><b class="text-danger">*</b>Subdivision Geo-tagging</label>
                            <select class="form-control" name="system_subdivision_id" id="system_subdivision_id" required>
                            </select>
                        </div>
                        <div id="subdNotListedDiv" class="d-none">
                            <div class="form-group">
                                <label for="system_subdivision_name"><b class="text-danger">*</b>Subdivision Name (Manual Input)</label>
                                <input type="text" class="form-control" value="{{old('system_subdivision_name', $d->system_subdivision_name)}}" style="text-transform: uppercase;" id="system_subdivision_name" name="system_subdivision_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Streetpurok">Street/Purok</label>
                            <input type="text" class="form-control" value="{{old('Streetpurok', $d->Streetpurok)}}" style="text-transform: uppercase;" id="Streetpurok" name="Streetpurok">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edcs_contactNo">Contact Number</label>
                            <input type="text" class="form-control" id="edcs_contactNo" name="edcs_contactNo" value="{{old('edcs_contactNo', $d->edcs_contactNo)}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Barangay"><b class="text-danger">*</b>Barangay</label>
                            <select class="form-control" name="Barangay" id="Barangay" required>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->id}}" {{($b->brgyName == old('Barangay', $d->Barangay)) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Streetpurok">Street/Purok</label>
                            <input type="text" class="form-control" value="{{old('Streetpurok', $d->Streetpurok)}}" id="Streetpurok" name="Streetpurok">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edcs_contactNo">Contact Number</label>
                            <input type="text" class="form-control" id="edcs_contactNo" name="edcs_contactNo" value="{{old('edcs_contactNo', $d->edcs_contactNo)}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_coordinate_x">GPS Coordinate X (Latitude)</label>
                            <input type="text" class="form-control" value="{{old('sys_coordinate_x', $d->sys_coordinate_x)}}" pattern="\d+(\.\d+)?" id="sys_coordinate_x" name="sys_coordinate_x">
                        </div>
                    </div>
                    <div class="col-md-6">
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
                @if(!is_null($d->sys_coordinate_x))
                <hr>
                <div>
                    <div id="map"></div>
                </div>
                @endif
                @if (!request()->is('*barangayportal*'))
                <div class="alert alert-info" role="alert">
                    <h6><b class="text-danger">Note:</b></h6>
                    <ul>
                        <li>Minsan nakasulat sa Street/Purok field yung Hint sa correct na subdivision na ilalagay.</li>
                        <li>Burahin ang subdivision sa Street/Purok field pagkatapos malipat para sa cleanliness ng data.</li>
                    </ul>
                </div>
                @endif
                @if($disease == 'PERT' && !request()->is('*barangayportal*'))
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                          <label for="system_outcome"><b class="text-danger">*</b>System Outcome</label>
                          <select class="form-control" name="system_outcome" id="system_outcome" required>
                            <option value="ALIVE" {{($d->system_outcome == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                            <option value="DIED" {{($d->system_outcome == 'DIED') ? 'selected' : ''}}>Died</option>
                            <option value="RECOVERED" {{($d->system_outcome == 'RECOVERED') ? 'selected' : ''}}>Recovered</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="system_classification"><b class="text-danger">*</b>System Classification</label>
                            <select class="form-control" name="system_classification" id="system_classification" required>
                                <option value="NO SWAB" {{($d->system_classification == 'NO SWAB') ? 'selected' : ''}}>No Swab</option>
                                <option value="WAITING FOR RESULT" {{($d->system_classification == 'WAITING FOR RESULT') ? 'selected' : ''}}>Waiting for Result</option>
                                <option value="CONFIRMED" {{($d->system_classification == 'CONFIRMED') ? 'selected' : ''}}>Confirmed</option>
                                <option value="NEGATIVE" {{($d->system_classification == 'NEGATIVE') ? 'selected' : ''}}>Negative</option>
                                <option value="UNKNOWN" {{($d->system_classification == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                            </select>
                          </div>
                    </div>
                </div>
                @endif
                <hr>
                <div class="form-group">
                    <label for="brgy_remarks">Remarks from Barangay</label>
                    <textarea class="form-control" name="brgy_remarks" id="brgy_remarks" rows="3" style="text-transform: uppercase;" {{(!request()->is('*barangayportal*')) ? 'readonly' : ''}}>{{old('brgy_remarks', $d->brgy_remarks)}}</textarea>
                </div>
                <div class="form-group">
                    <label for="system_remarks">Remarks from CESU</label>
                    <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3" style="text-transform: uppercase;" {{(request()->is('*barangayportal*')) ? 'readonly' : ''}}>{{old('system_remarks', $d->system_remarks)}}</textarea>
                </div>
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

    @if(!is_null($d->sys_coordinate_x))
    L.Icon.Default.imagePath="{{asset('assets')}}/"
    
    var map = L.map('map').setView([{{$d->sys_coordinate_x}}, {{$d->sys_coordinate_y}}], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        minZoom: 12,
        maxZoom: 18,
    }).addTo(map);

    var marker = L.marker([{{$d->sys_coordinate_x}}, {{$d->sys_coordinate_y}}]).addTo(map);
    marker.bindPopup("<b>Hello world!</b><br>I am a popup.");
    @endif
    
    $(document).ready(function() {
        $('#Barangay').select2({
            theme: 'bootstrap',
        });

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

        @if(!request()->is('*barangayportal*'))
        $('#Barangay').on('change', function() {
            var brgy_id = $(this).val();
            if (brgy_id) {
                $.ajax({
                    url: '{{ route("getSubdivisions", ":id") }}'.replace(':id', brgy_id),
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#system_subdivision_id').empty();
                        $('#system_subdivision_id').append('<option value="" selected disabled>Choose...</option>');
                        $.each(data, function(key, value) {
                            $('#system_subdivision_id').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        $('#system_subdivision_id').select2({
                            theme: 'bootstrap',
                        });
                        var existingSubdivisionId = '{{ $d->system_subdivision_id }}'; // Assuming you pass the existing subdivision ID from the backend
                        if(existingSubdivisionId) {
                            $('#system_subdivision_id').val(existingSubdivisionId).trigger('change');
                        }
                        $('#system_subdivision_id').append('<option value="NOT LISTED">NOT LISTED (N/A)</option>');
                    }
                });
            } else {
                $('#system_subdivision_id').empty();
            }
        }).trigger('change');

        $('#system_subdivision_id').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'NOT LISTED') {
                $('#subdNotListedDiv').removeClass('d-none');
                $('#system_subdivision_name').prop('required', true);
            }
            else {
                $('#subdNotListedDiv').addClass('d-none');
                $('#system_subdivision_name').prop('required', false);
            }
        }).trigger('change');
        @endif
    });

</script>
@endsection