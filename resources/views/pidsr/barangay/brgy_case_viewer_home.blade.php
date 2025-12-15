@extends('layouts.app')

@section('content')
    <div class="container">
        <p>Today is: {{date('M. d, Y')}} - Morbidity Week: {{date('W')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Encoded Epidemic-prone Disease Dashboard (BRGY. {{session('brgyName')}}) - Year: {{$year}}</b></div>
                    <div>
                        <form action="{{route('edcs_barangay_view_logout')}}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCase">New Case</button>
                            @if(!Str::contains(request()->url(), 'facility_report'))
                            <button type="submit" class="btn btn-danger">Logout</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                @if(Str::contains(request()->url(), 'facility_report'))
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#edcsImportModal">View List of For Import to EDCS</button>
                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#downloadCsv">Download as CSV</button>
                @endif
                
                <hr>
                <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#filterModal">Filter</button>
                @include('pidsr.epdrone_body')
            </div>
        </div>
        <p class="mt-3 text-center">©2021 - {{date('Y')}} Developed and Mainted by <u>CJH</u> for General Trias CHO - CESU</p>
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
                          <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{old('facility_code', $f->sys_code1)}}" readonly>
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

    <form action="" method="GET">
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Year</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach(range(date('Y'), 2015) as $y)
                                <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
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
                    <a href="{{route('edcs_view_exportables', [$f->sys_code1, $disease['value']])}}" class="btn btn-primary btn-block">{{$disease['text']}}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('edcs_facility_download_csv', $f->sys_code1)}}" method="POST">
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
@endsection