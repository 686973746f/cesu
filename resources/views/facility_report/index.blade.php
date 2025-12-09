@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div>Welcome, {{$d->facility_name}}</div>
                <div><b>CESU General Trias - Facility Reporting Tool</b></div>
            </div>
            <div class="card-body">
                <div class="alert alert-info text-center" role="alert">
                    <b class="text-danger">WARNING:</b> Please don't share your unique link to unauthorized personnel.
                </div>
                <a href="{{route('facility_report_injury_index', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">Injury Reporting Tool</a>
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addCase">Encode Case of Reportable Disease</button>
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#edcsImportModal">View Imports to EDCS-IS</button>
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#downloadCsv">Download CSV Templates</button>
                <hr>
                <a href="{{route('edcs_facility_weeklysubmission_view', $d->sys_code1)}}" class="btn btn-primary btn-lg btn-block">EDCS-IS Weekly Submission</a>
            </div>
        </div>
    </div>

    <form action="{{route('edcs_addcase_check')}}" method="GET">
        <div class="modal fade" id="addCase" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Case</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if(session('modalmsg'))
                        <div class="alert alert-{{session('modalmsgtype')}} text-center" role="alert">
                            {{ session('modalmsg') }}
                        </div>
                        @endif
                        <div class="form-group d-none">
                          <label for="facility_code"><b class="text-danger">*</b>Facility Code</label>
                          <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{old('facility_code', $d->sys_code1)}}" readonly>
                        </div>
                        <div class="form-group">
                          <label for="disease"><b class="text-danger">*</b>Select Case</label>
                          <select class="form-control" name="disease" id="disease" required>
                            <option value="" disabled {{(is_null(old('disease'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach(\App\Http\Controllers\PIDSRController::listReportableDiseasesBackEnd() as $disease)
                                <option value="{{ $disease['value'] }}" {{ (collect(old('disease'))->contains($disease['value'])) ? 'selected' : '' }}>{{ $disease['text'] }}</option>
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
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="entry_date"><b class="text-danger">*</b>Date Admitted/Seen/Consulted</label>
                            <input type="date" class="form-control" name="entry_date" id="entry_date" value="{{old('entry_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="edcsImportModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imports to EDCS-IS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @foreach(\App\Http\Controllers\PIDSRController::listReportableDiseasesBackEnd()->where('edcs_importable', true) as $disease)
                    <a href="{{route('edcs_view_exportables', [$d->sys_code1, $disease['value']])}}" class="btn btn-primary btn-block">{{$disease['text']}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('edcs_facility_download_csv', $d->sys_code1)}}" method="POST">
        @csrf
        <div class="modal fade" id="downloadCsv" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download CSV Template</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="disease"><b class="text-danger">*</b>Select Disease</label>
                            <select class="form-control" name="disease" id="disease" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(\App\Http\Controllers\PIDSRController::listReportableDiseasesBackEnd()->where('edcs_importable', true) as $disease)
                                <option value="{{$disease['value']}}">{{$disease['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                    <input type="date" class="form-control" name="startDate" id="startDate" min="2025-01-01" max="{{date('Y-m-t')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                    <input type="date" class="form-control" name="endDate" id="endDate" min="2020-01-01" max="{{date('Y-m-t')}}" value="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="convert_flat" id="convert_flat" value="Y" checked>Download as Flat File <small>(This will make the address code as readable text form)</small></label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    @if(session('openEncodeModal'))
    <script>
        $(document).ready(function(){
            $('#addCase').modal('show');
        });
    </script>
    @endif
@endsection