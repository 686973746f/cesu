@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pidsr_laboratory_delete', $d->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="text-right mb-3">
            <button type="submit" class="btn btn-danger" onclick="return confirm('You will delete this Specimen Data. Click OK to Confirm.')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete</button>
        </div>
    </form>

    <form action="{{route('pidsr_laboratory_update', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Edit Specimen Data</b></div>
                    <div>
                        <a href="{{route('pidsr_laboratory_print', $d->id)}}" class="btn btn-primary">Print</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                    <label for="disease_tag"><b class="text-danger">*</b>Disease</label>
                    <select class="form-control" name="disease_tag" id="disease_tag" required>
                        <option value="" disabled selected>Choose...</option>
                        @foreach(App\Http\Controllers\PIDSRController::listDiseases() as $dd)
                        <option value="{{mb_strtoupper($dd)}}" {{(mb_strtoupper($dd) == $d->disease_tag) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                        @endforeach
                        <option value="DIARRHEA" {{($d->disease_tag == 'DIARRHEA') ? 'selected' : ''}}>DIARRHEA</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $d->lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $d->fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="mname">Middle Name <i>(If Applicable)</i></label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $d->mname)}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="suffix">Suffix <i>(If Applicable)</i></label>
                            <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $d->suffix)}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, II, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="age"><b class="text-danger">*</b>Age (In Years)</label>
                        <input type="number" min="0" max="300" class="form-control" name="age" id="age" value="{{old('age', $d->age)}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="gender"><b class="text-danger">*</b>Sex</label>
                            <select class="form-control" name="gender" id="gender" required>
                                <option value="M" {{($d->gender == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{($d->gender == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="date_collected"><b class="text-danger">*</b>Date and Time Collected</label>
                            <input type="datetime-local" class="form-control" name="date_collected" id="date_collected" min="{{date('Y-m-d 00:00:00', strtotime('-1 Year'))}}" max="{{date('Y-m-d H:i')}}" value="{{date('Y-m-d H:i', strtotime(old('date_collected', $d->date_collected)))}}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="collector_name"><b class="text-danger">*</b>Name of Collector/Swabber</label>
                            <input type="text" class="form-control" name="collector_name" id="collector_name" style="text-transform: uppercase;" value="{{$d->collector_name}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="specimen_type"><b class="text-danger">*</b>Specimen Type</label>
                            <select class="form-control" name="specimen_type" id="specimen_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsSpecimenTypeList() as $dd)
                                <option value="{{mb_strtoupper($dd)}}" {{($d->specimen_type == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="test_type"><b class="text-danger">*</b>Test Type</label>
                            <select class="form-control" name="test_type" id="test_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestConductedList() as $dd)
                                <option value="{{mb_strtoupper($dd)}}" {{($d->test_type == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sent_to_ritm"><b class="text-danger">*</b>Sent to RITM</label>
                    <select class="form-control" name="sent_to_ritm" id="sent_to_ritm" required>
                        <option value="Y" {{($d->sent_to_ritm == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{($d->sent_to_ritm == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div id="ritm_div" class="d-none">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="ritm_date_sent"><b class="text-danger">*</b>Date Sent to RITM</label>
                                <input type="date" class="form-control" name="ritm_date_sent" id="ritm_date_sent" value="{{old('ritm_date_sent', $d->ritm_date_sent)}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="driver_name"><b class="text-danger">*</b>Name of Courier/Driver</label>
                                <input type="text" class="form-control" name="driver_name" id="driver_name" style="text-transform: uppercase;" value="{{old('driver_name', $d->driver_name)}}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="ritm_date_received">Date Received by RITM</label>
                                <input type="date" class="form-control" name="ritm_date_received" id="ritm_date_received" value="{{old('ritm_date_received', $d->ritm_date_received)}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
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
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestLabResults() as $dd)
                                <option value="{{mb_strtoupper($dd)}}" {{($d->result == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="interpretation">Interpretation</label>
                            <input type="text" class="form-control" name="interpretation" id="interpretation" value="{{old('interpretation', $d->interpretation)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="date_released"><b class="text-danger">*</b>Date and Time Released</label>
                            <input type="datetime-local" class="form-control" name="date_released" id="date_released" min="{{date('Y-m-d 00:00:00', strtotime('-1 Year'))}}" max="{{date('Y-m-d H:i')}}" value="{{date('Y-m-d H:i', strtotime(old('date_released', $d->date_released)))}}">
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
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Update (CTRL + S)</button>
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