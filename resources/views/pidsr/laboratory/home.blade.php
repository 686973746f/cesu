@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>
                    <div><b>Laboratory Logbook</b></div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">New Lab Result</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($list->count() != 0)
                <table class="table">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Disease</th>
                            <th>Date Swab Collected</th>
                            <th>Type</th>
                            <td>Result</td>
                            <th>Encoded by/at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center">Results is currently empty.</p>
                @endif
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div id="accordianId" role="tablist" aria-multiselectable="true">
                            <div class="card">
                                <div class="card-header" role="tab" id="section1HeaderId">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">via PIDSR/EDCS Data</a>
                                    </h5>
                                </div>
                                <div id="section1ContentId" class="collapse" role="tabpanel" aria-labelledby="section1HeaderId">
                                    <div class="card-body">
                                        Test
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" role="tab" id="section2HeaderId">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" data-parent="#accordianId" href="#section2ContentId" aria-expanded="true" aria-controls="section2ContentId">Manual Method</a>
                                    </h5>
                                </div>
                                <div id="section2ContentId" class="collapse" role="tabpanel" aria-labelledby="section2HeaderId">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="disease_tag"><b class="text-danger">*</b>Disease</label>
                                            <select class="form-control" name="disease_tag" id="disease_tag" required>
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach(App\Http\Controllers\PIDSRController::listDiseases() as $d)
                                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                                    <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="age"><b class="text-danger">*</b>Age (In Years)</label>
                                                  <input type="number" min="0" max="300" class="form-control" name="age" id="age" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="gender"><b class="text-danger">*</b>Gender</label>
                                                  <select class="form-control" name="gender" id="gender" required>
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                  </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                          <label for="date_collected"><b class="text-danger">*</b>Date Collected</label>
                                          <input type="date" class="form-control" name="date_collected" id="date_collected" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" required>
                                        </div>
                                        <div class="form-group">
                                          <label for="collector_name"><b class="text-danger">*</b>Name of Collector/Swabber</label>
                                          <input type="text" class="form-control" name="collector_name" id="collector_name" style="text-transform: uppercase;" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="specimen_type"><b class="text-danger">*</b>Specimen Type</label>
                                            <select class="form-control" name="specimen_type" id="specimen_type" required>
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach(App\Http\Controllers\PIDSRController::getEdcsSpecimenTypeList() as $d)
                                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                                @endforeach
                                            </select>
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
                                            <div class="form-group">
                                                <label for="ritm_date_received"><b class="text-danger">*</b>Date Sent to RITM</label>
                                                <input type="date" class="form-control" name="ritm_date_received" id="ritm_date_received" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="ritm_date_received"><b class="text-danger">*</b>Date Received by RITM</label>
                                                <input type="date" class="form-control" name="ritm_date_received" id="ritm_date_received" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="driver_name"><b class="text-danger">*</b>Name of Courier/Driver</label>
                                            <input type="text" class="form-control" name="driver_name" id="driver_name" style="text-transform: uppercase;" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="test_type"><b class="text-danger">*</b>Test Type</label>
                                            <select class="form-control" name="test_type" id="test_type" required>
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestConductedList() as $d)
                                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection