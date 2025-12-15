@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <div>{{$f->facility_name}}</div>
                    <div><b>Injury Reporting Tool</b></div>
                </div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCase">New Case</button>
                    @if($f->facility_type == 'Hospital' || $f->facility_type == 'Infirmary')
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fwcsvmodal">Upload Fireworks Injury CSV (FWRI)</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#injurymodal">Upload Injury CSV</button>
                    @else
                    <a href="{{route('fwri_index', $f->sys_code1)}}" class="btn btn-success">New Fireworks-Related Injury</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Date Added</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Address</th>
                            <th>Date of Injury</th>
                            <th>Injury Location</th>
                            <th>Involvement Type</th>
                            <th>Type of Injury</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form action="{{route('upload_fwri', $f->sys_code1)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="fwcsvmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Fireworks-Related Injury (FWRI) CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="csv_file"><b class="text-danger">*</b>Select the "tbl_kontra_paputok.csv" file to upload</label>
                      <input type="file" class="form-control-file" name="csv_file" id="csv_file" accept=".csv,text/csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('upload_injury', $f->sys_code1)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="injurymodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload ONEISS CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="csv_file"><b class="text-danger">*</b>Select the NEISS CSV file to upload</label>
                      <input type="file" class="form-control-file" name="csv_file" id="csv_file" accept=".csv,text/csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block" disabled>Coming Soon</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('injury_add_check', $f->sys_code1)}}" method="GET">
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
                    <div class="form-group">
                        <label for="consultation_date"><b class="text-danger">*</b>Date Seen/Consulted</label>
                        <input type="date" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                    </div>
                    <hr>
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
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection