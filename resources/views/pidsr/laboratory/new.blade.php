@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pidsr_laboratory_store')}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>New Laboratory Data</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if(!$manual_mode)
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Link to EDCS-IS Case ID</label>
                            <input type="text" class="form-control" name="linkto_caseid" id="linkto_caseid" value="{{$link_array['details']['edcs_caseid']}}" required readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Disease</label>
                            <input type="text" class="form-control" name="disease_tag" id="disease_tag" value="{{$disease}}" required readonly>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <label for="disease_tag"><b class="text-danger">*</b>Disease</label>
                    <select class="form-control" name="disease_tag" id="disease_tag" required>
                        <option value="" disabled selected>Choose...</option>
                        @foreach(App\Http\Controllers\PIDSRController::listDiseases() as $d)
                        <option value="{{mb_strtoupper($d)}}" {{(mb_strtoupper($d) == $disease) ? 'selected' : ''}}>{{mb_strtoupper($d)}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="mname">Middle Name <i>(If Applicable)</i></label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $mname)}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="suffix">Suffix <i>(If Applicable)</i></label>
                            <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $suffix)}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, II, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="age"><b class="text-danger">*</b>Age (In Years)</label>
                        <input type="number" min="0" max="300" class="form-control" name="age" id="age" value="{{old('age', $age)}}" required {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                    <div class="col-6">
                        @if(!$manual_mode)
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Sex</label>
                            <input type="text" class="form-control" name="gender" id="gender" value="{{$gender}}" required readonly>
                        </div>
                        @else
                        <div class="form-group">
                            <label for="gender"><b class="text-danger">*</b>Sex</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="M" {{($gender == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{($gender == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="date_collected"><b class="text-danger">*</b>Date and Time Collected</label>
                            <input type="datetime-local" class="form-control" name="date_collected" id="date_collected" min="{{date('Y-m-d 00:00:00', strtotime('-1 Year'))}}" max="{{date('Y-m-d H:i')}}" value="{{(!is_null(old('date_collected'))) ? date('Y-m-d H:i', strtotime(old('date_collected'))) : ''}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="collector_name"><b class="text-danger">*</b>Name of Collector/Swabber</label>
                            <input type="text" class="form-control" name="collector_name" id="collector_name" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="specimen_type"><b class="text-danger">*</b>Specimen Type</label>
                            <select class="form-control" name="specimen_type" id="specimen_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsSpecimenTypeList() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="test_type"><b class="text-danger">*</b>Test Type</label>
                            <select class="form-control" name="test_type" id="test_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestConductedList() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sent_to_ritm"><b class="text-danger">*</b>Sent to RITM</label>
                    <select class="form-control" name="sent_to_ritm" id="sent_to_ritm" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>
                </div>
                <div id="ritm_div" class="d-none">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="ritm_date_sent"><b class="text-danger">*</b>Date Sent to RITM</label>
                                <input type="date" class="form-control" name="ritm_date_sent" id="ritm_date_sent" value="{{old('ritm_date_sent')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="driver_name"><b class="text-danger">*</b>Name of Courier/Driver</label>
                                <input type="text" class="form-control" name="driver_name" id="driver_name" style="text-transform: uppercase;" value="{{old('driver_name')}}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="ritm_date_received">Date Received by RITM</label>
                                <input type="date" class="form-control" name="ritm_date_received" id="ritm_date_received" value="{{old('ritm_date_received')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="result"><b class="text-danger">*</b>Result</label>
                            <select class="form-control" name="result" id="result" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestLabResults() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="interpretation">Interpretation</label>
                            <input type="text" class="form-control" name="interpretation" id="interpretation" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="date_released"><b class="text-danger">*</b>Date and Time Released</label>
                            <input type="datetime-local" class="form-control" name="date_released" id="date_released" min="{{date('Y-m-d 00:00:00', strtotime('-1 Year'))}}" max="{{date('Y-m-d H:i')}}" value="{{(!is_null(old('date_released'))) ? date('Y-m-d H:i', strtotime(old('date_released'))) : ''}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" style="text-transform: uppercase;">
                </div>
                <div class="alert alert-info text-center mb-0" role="alert">
                    <h4><b class="text-danger">Note:</b> After encoding in the system, it should also be encoded in <a href="https://pidsr.doh.gov.ph/">EDCS-IS</a>.</h4>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Save (CTRL + S)</button>
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

    $('#sent_to_ritm').change(function (e) {
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#ritm_div').removeClass('d-none');
            $('#ritm_date_sent').prop('required', true);
            $('#driver_name').prop('required', true);
        }
        else {
            $('#ritm_div').addClass('d-none');
            $('#ritm_date_sent').prop('required', false);
            $('#driver_name').prop('required', false);
        }
    }).trigger('change');

    $('#result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == null || $(this).val() == 'PENDING') {
            $('#date_released').prop('disabled', true);
            $('#date_released').prop('required', false);
        }
        else {
            $('#date_released').prop('disabled', false);
            $('#date_released').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection