@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Laboratory Logbook</b></div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newGroup">New Group</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('pidsr_laboratory_groups_store')}}" method="POST">
        @csrf
        <div class="modal fade" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Specimen Logbook Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="title"><b class="text-danger">*</b>Title</label>
                          <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="disease_tag"><b class="text-danger">*</b>Disease</label>
                            <select class="form-control" name="disease_tag" id="disease_tag" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::listDiseases() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                                <option value="DIARRHEA">DIARRHEA</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="base_specimen_type"><b class="text-danger">*</b>Base Specimen Type</label>
                            <select class="form-control" name="base_specimen_type" id="base_specimen_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsSpecimenTypeList() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="base_test_type"><b class="text-danger">*</b>Base Test Type</label>
                            <select class="form-control" name="base_test_type" id="base_test_type" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(App\Http\Controllers\PIDSRController::getEdcsTestConductedList() as $d)
                                <option value="{{mb_strtoupper($d)}}">{{mb_strtoupper($d)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="base_collector_name"><b class="text-danger">*</b>Base Name of Collector/Swabber</label>
                            <input type="text" class="form-control" name="base_collector_name" id="base_collector_name" style="text-transform: uppercase;" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="sent_to_ritm"><b class="text-danger">*</b>Sent to RITM</label>
                            <select class="form-control" name="sent_to_ritm" id="sent_to_ritm" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save (CTRL + S)</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection