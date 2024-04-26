@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Specimen Linelist</b></div>
                    <div>
                        @if($d->is_finished == 'Y')
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Case is already Closed.">
                            <button class="btn btn-success" style="pointer-events: none;" type="button" disabled>Add Patient</button>
                        </span>
                        @else
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPatient">Add Patient</button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editGroup">Settings</button>
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
                <div class="row">
                    <div class="col-4">

                    </div>
                    <div class="col-4">
                        <h5><b></b></h5>
                        <h5></h5>
                    </div>
                    <div class="col-4">

                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <h5><b>For Case:</b></h5>
                        <h5>{{$d->disease_tag}}</h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Title:</b></h5>
                        <h5><u>{{$d->title}}</u></h5>
                    </div>
                    <div class="col-4">
                        <h5><b>Date Created/by:</b></h5>
                        <h5>{{date('M d, Y h:i A', strtotime($d->created_at))}} by {{$d->user->name}}</h5>
                        @if(!is_null($d->updated_by))
                        <h5><b>Updated at/by:</b></h5>
                        <h5>{{date('M d, Y h:i A', strtotime($d->updated_at))}} by {{$d->getUpdatedBy->name}}</h5>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-3">
                        <h5><b>Date Started:</b></h5>
                        <h5>{{date('m/d/Y', strtotime($d->case_open_date))}}</h5>
                    </div>
                    <div class="col-3">
                        <h5><b>Case Finished?:</b></h5>
                        <h5>{{$d->is_finished}}</h5>
                    </div>
                    <div class="col-3">
                        <h5><b>Date Finished</b></h5>
                        <h5>{{($d->is_finished == 'Y') ? date('m/d/Y', strtotime($d->case_close_date)) : 'N/A'}}</h5>
                        @if($d->is_finished == 'Y')
                        <h5><b>Closed by:</b></h5>
                        <h5>{{$d->getClosedBy->name}}</h5>
                        @endif
                    </div>
                    <div class="col-3">
                        <h5><b>Sent to RITM:</b></h5>
                        <h5>{{$d->sent_to_ritm}}</h5>
                    </div>
                </div>
                @if($d->sent_to_ritm == 'Y' && !is_null($d->ritm_date_sent))
                <div class="row">
                    <div class="col-6">
                        <h5><b>Date Sent to RITM:</b></h5>
                        <h5>{{date('m/d/Y', strtotime($d->ritm_date_sent))}}</h5>
                        <h5><b>Name of Driver:</b></h5>
                        <h5>{{$d->driver_name}}</h5>
                    </div>
                    <div class="col-6">
                        <h5><b>Date Received by RITM:</b></h5>
                        <h5>{{(!is_null($d->ritm_date_received)) ? date('m/d/Y', strtotime($d->ritm_date_received)) : 'N/A'}}</h5>
                        <h5><b>Name of Receiver:</b></h5>
                        <h5>{{(!is_null($d->ritm_received_by)) ? $d->ritm_received_by : 'N/A'}}</h5>
                    </div>
                </div>
                @endif
                <hr>
                <div>
                    <h5><b>Remarks:</b> {{(!is_null($d->remarks)) ? $d->remarks : 'N/A'}}</h5>
                </div>
                <table class="table table-bordered table-striped mt-5">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>
                                <div>Name/</div>
                                <div>EDCS Case ID</div>
                            </th>
                            <th>Age/Sex</th>
                            <th>
                                <div>Specimen Type/</div>
                                <div>Test Type</div>
                            </th>
                            <th>Date Collected</th>
                            <th>Result</th>
                            <th>Date Released</th>
                            <th>Remarks</th>
                            <th>Encoded at/by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fetch_list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td>
                                <div><a href="{{route('pidsr_laboratory_group_patient_view', [$d->id, $l->id])}}">{{$l->getName()}}</a></div>
                                <div>{{(!is_null($l->for_case_id)) ? $l->for_case_id : ''}}</div>
                            </td>
                            <td class="text-center">{{$l->age}} / {{$l->gender}}</td>
                            <td class="text-center">
                                <div>{{$l->specimen_type}}/</div>
                                <div>{{$l->test_type}}</div>
                            </td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->date_collected))}}</td>
                            <td class="text-center">{{$l->result}}</td>
                            <td class="text-center">{{(!is_null($l->date_released) && $l->result != 'PENDING') ? date('m/d/Y', strtotime($l->date_released)) : 'N/A'}}</td>
                            <td class="text-center">{{(!is_null($l->remarks)) ? $l->remarks : 'N/A'}}</td>
                            <td class="text-center">
                                <div>{{date('m/d/Y h:i A', strtotime($l->created_at))}}</div>
                                <div>by - {{$l->user->name}}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <form action="{{route('pidsr_laboratory_groups_update', $d->id)}}" method="POST">
        @csrf
        <div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Settings</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title"><b class="text-danger">*</b>Case Title</label>
                            <input type="text" class="form-control" name="title" id="title" value="{{old('title', $d->title)}}" required>
                        </div>
                        <div class="form-group">
                            <label for="disease_tag"><b class="text-danger">*</b>Case Type</label>
                            <select class="form-control" name="disease_tag" id="disease_tag" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::listDiseases() as $dd)
                                <option value="{{mb_strtoupper($dd)}}" {{(old('disease_tag', $d->disease_tag) == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                @endforeach
                                <option value="DIARRHEA" {{(old('disease_tag', $d->disease_tag) == 'DIARRHEA') ? 'selected' : ''}}>DIARRHEA</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="is_finished"><b class="text-danger">*</b>Case Status</label>
                                    <select class="form-control" name="is_finished" id="is_finished" required>
                                      <option value="N" {{($d->is_finished == 'N') ? 'selected' : ''}}>Open</option>
                                      <option value="Y" {{($d->is_finished == 'Y') ? 'selected' : ''}}>Closed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="case_open_date"><b class="text-danger">*</b>Date Started</label>
                                    <input type="date" class="form-control" name="case_open_date" id="case_open_date" value="{{old('case_open_date', $d->case_open_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="case_close_date"><b class="text-danger">*</b>Date Closed</label>
                                    <input type="date" class="form-control" name="case_close_date" id="case_close_date" value="{{old('case_close_date', $d->case_close_date)}}" max="{{date('Y-m-d', strtotime('+1 Day'))}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="base_specimen_type"><b class="text-danger">*</b>Base Specimen Type</label>
                                    <select class="form-control" name="base_specimen_type" id="base_specimen_type" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach(App\Http\Controllers\PIDSRController::getEdcsSpecimenTypeList() as $dd)
                                        <option value="{{mb_strtoupper($dd)}}" {{(old('base_specimen_type', $d->base_specimen_type) == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="base_test_type"><b class="text-danger">*</b>Base Test Type</label>
                                    <select class="form-control" name="base_test_type" id="base_test_type" required>
                                        <option value="" disabled selected>Choose...</option>
                                        @foreach(App\Http\Controllers\PIDSRController::getEdcsTestConductedList() as $dd)
                                        <option value="{{mb_strtoupper($dd)}}" {{(old('base_test_type', $d->base_test_type) == mb_strtoupper($dd)) ? 'selected' : ''}}>{{mb_strtoupper($dd)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="base_collector_name"><b class="text-danger">*</b>Base Name of Collector/Swabber</label>
                            <input type="text" class="form-control" name="base_collector_name" id="base_collector_name" value="{{old('base_collector_name', $d->base_collector_name)}}" style="text-transform: uppercase;" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="sent_to_ritm"><b class="text-danger">*</b>Specimen/s will be sent to RITM?</label>
                            <select class="form-control" name="sent_to_ritm" id="sent_to_ritm" required>
                                <option value="Y" {{(old('sent_to_ritm', $d->sent_to_ritm) == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('sent_to_ritm', $d->sent_to_ritm) == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="ritm_div" class="d-none">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="ritm_date_sent"><b class="text-danger">*</b>Date Sent to RITM</label>
                                        <input type="date" class="form-control" name="ritm_date_sent" id="ritm_date_sent" value="{{old('ritm_date_sent', $d->ritm_date_sent)}}" max="{{date('Y-m-d', strtotime('+1 Day'))}}">
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
                                        <input type="date" class="form-control" name="ritm_date_received" id="ritm_date_received" value="{{old('ritm_date_received', $d->ritm_date_received)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control" name="remarks" id="remarks" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
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

        $('#is_finished').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#case_close_date').prop('required', true);
                $('#case_close_date').prop('disabled', false);
            }
            else {
                $('#case_close_date').prop('required', false);
                $('#case_close_date').prop('disabled', true);
            }
        }).trigger('change');
    </script>

    @include('pidsr.laboratory.addpatient_modal')
@endsection