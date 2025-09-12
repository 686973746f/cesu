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
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="systemsent"><b class="text-danger">*</b>System Sent</label>
                          <input type="number" class="form-control" name="systemsent" id="systemsent" min="0" max="1" value="{{old('systemsent', $d->systemsent)}}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notify_email_sent"><b class="text-danger">*</b>Notify Email Sent</label>
                            <input type="number" class="form-control" name="notify_email_sent" id="notify_email_sent" min="0" max="1" value="{{old('notify_email_sent', $d->notify_email_sent)}}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edcs_customgroup">Input Custom Group</label>
                            <input type="text" class="form-control" name="edcs_customgroup" id="edcs_customgroup" value="{{old('edcs_customgroup', $d->edcs_customgroup)}}">
                        </div>
                    </div>
                </div>
                <hr>
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
                            <input type="text" class="form-control" value="{{$epi_id}}" readonly>
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
                                <option value="{{$b->id}}" {{($b->name == old('Barangay', $d->Barangay) || $b->alt_name == old('Barangay', $d->Barangay)) ? 'selected' : ''}}>{{$b->alt_name ?: $b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="subdivision_group"><b class="text-danger">*</b>Group to Subdivision/Area/Sitio/Purok</label>
                          <select class="form-control" name="subdivision_group" id="subdivision_group" required>
                          </select>
                        </div>
                        <div id="unlisted_div" class="d-none">
                            <div class="form-group">
                                <label for="subdivision_group"><b class="text-danger">*</b>Please Specify the Subdivision/Area/Sitio/Purok</label>
                                <input type="text" class="form-control" name="subdivision_group_new" id="subdivision_group_new" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="override_clustering" id="override_clustering" value="1">Override Clustering ID</label>
                        </div>
                        <div id="override_div" class="d-none">
                            <div class="form-group">
                                <label for="sys_clustering_schedule_id">Override to Clustering Schedule ID</label>
                                <input type="number" class="form-control" name="sys_clustering_schedule_id" id="sys_clustering_schedule_id" min="1" max="9999999" value="{{old('sys_clustering_schedule_id', $d->sys_clustering_schedule_id)}}">
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                            <select class="form-control" name="outcome" id="outcome" required>
                                <option value="A" {{(old('outcome', $d->Outcome) == 'A') ? 'selected' : ''}}>Alive</option>
                                <option value="D" {{(old('outcome', $d->Outcome) == 'D') ? 'selected' : ''}}>Died</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if($disease == 'MEASLES')
                <div class="form-group">
                    <label for="outcome"><b class="text-danger">*</b>Final Classification</label>
                    <select class="form-control" name="FinalClass" id="FinalClass" required>
                        <option value="" disabled {{(is_null(old('FinalClass', $d->FinalClass))) ? 'selected' : ''}}>Choose...</option>
                        <option value="LABORATORY CONFIRMED MEASLES" {{(old('FinalClass', $d->FinalClass) == 'LABORATORY CONFIRMED MEASLES') ? 'selected' : ''}}>LABORATORY CONFIRMED MEASLES</option>
                        <option value="LABORATORY CONFIRMED RUBELLA" {{(old('FinalClass', $d->FinalClass) == 'LABORATORY CONFIRMED RUBELLA') ? 'selected' : ''}}>LABORATORY CONFIRMED RUBELLA</option>
                        <option value="EPI-LINKED CONFIRMED MEASLES" {{(old('FinalClass', $d->FinalClass) == 'EPI-LINKED CONFIRMED MEASLES') ? 'selected' : ''}}>EPI-LINKED CONFIRMED MEASLES</option>
                        <option value="EPI-LINKED CONFIRMED RUBELLA" {{(old('FinalClass', $d->FinalClass) == 'EPI-LINKED CONFIRMED RUBELLA') ? 'selected' : ''}}>EPI-LINKED CONFIRMED RUBELLA</option>
                        <option value="MEASLES COMPATIBLE" {{(old('FinalClass', $d->FinalClass) == 'MEASLES COMPATIBLE') ? 'selected' : ''}}>MEASLES COMPATIBLE</option>
                        <option value="MEASLES EQUIVOCAL" {{(old('FinalClass', $d->FinalClass) == 'MEASLES EQUIVOCAL') ? 'selected' : ''}}>MEASLES EQUIVOCAL</option>
                        <option value="DISCARDED NON MEASLES/RUBELLA" {{(old('FinalClass', $d->FinalClass) == 'DISCARDED NON MEASLES/RUBELLA') ? 'selected' : ''}}>DISCARDED NON MEASLES/RUBELLA</option>
                    </select>
                </div>
                @endif
                <div>
                    <button type="button" name="changeLocation" id="changeLocation" class="btn btn-warning btn-block d-none">Change Location</button>
                    <button type="button" name="getCurrentLocation" id="getCurrentLocation" class="btn btn-secondary btn-block">
                        <div><i class="fa fa-map-marker mr-2" aria-hidden="true"></i>Tag Current Location as the Patient Location</div>
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
                <div class="alert alert-info mt-3" role="alert">
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
                <hr>
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> For the URLs, you may use Google Drive Links (Recommended) or other file sharing links.
                </div>
                <div class="form-group">
                  <label for="cif_url">Case Investigation Form URL</label>
                  <input type="text" class="form-control" name="cif_url" id="cif_url" value="{{old('cif_url', $d->cif_url)}}">
                </div>
                <div class="form-group">
                  <label for="labresult_url">Laboratory Result URL</label>
                  <input type="text" class="form-control" name="labresult_url" id="labresult_url" value="{{old('labresult_url', $d->labresult_url)}}">
                </div>
                <div class="form-group">
                  <label for="medicalchart_url">Medical Chart URL</label>
                  <input type="text" class="form-control" name="medicalchart_url" id="medicalchart_url" value="{{old('medicalchart_url', $d->medicalchart_url)}}">
                </div>
                <div class="form-group">
                  <label for="otherattachments_url">Other Attachments URL</label>
                  <input type="text" class="form-control" name="otherattachments_url" id="otherattachments_url" value="{{old('otherattachments_url', $d->otherattachments_url)}}">
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

        /*
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
        */

        $('#Barangay').change(function (e) { 
            e.preventDefault();
            var brgy_id = $(this).val();

            if (brgy_id) {
                $.ajax({
                    url: '{{ route("getSubdivisionsV2", ":id") }}'.replace(':id', brgy_id),
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#subdivision_group').empty();
                        $('#subdivision_group').append('<option value="" selected disabled>Choose...</option>');
                        $.each(data, function(key, value) {
                            $('#subdivision_group').append('<option value="'+ value +'">'+ value +'</option>');
                        });
                        $('#subdivision_group').select2({
                            theme: 'bootstrap',
                        });
                        var existingSubdivisionId = '{{ $d->subdivision_group }}'; // Assuming you pass the existing subdivision ID from the backend
                        if(existingSubdivisionId) {
                            $('#subdivision_group').val(existingSubdivisionId).trigger('change');
                        }
                        else {
                            $('#subdivision_group').val(null);
                        }
                        $('#subdivision_group').append('<option value="UNLISTED">NOT ON THE LIST (N/A)</option>');
                    }
                });
            } else {
                $('#subdivision_group').empty();
            }
        }).trigger('change');
        
        $('#subdivision_group').change(function (e) { 
            e.preventDefault();

            if($(this).val() == 'UNLISTED') {
                $('#unlisted_div').removeClass('d-none');
                $('#subdivision_group_new').prop('required', true);
            }
            else {
                $('#unlisted_div').addClass('d-none');
                $('#subdivision_group_new').prop('required', false);
            }
        });
    });

    $('#override_clustering').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#override_div').removeClass('d-none');
            $('#sys_clustering_schedule_id').prop('required', true);
        }
        else {
            $('#override_div').addClass('d-none');
            $('#sys_clustering_schedule_id').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection