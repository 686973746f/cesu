@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_facility_weeklysubmission_process', [$f->sys_code1, $year, $mw])}}" method="POST">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>Weekly Submission</b></div>
                        <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">Change Reporting Week</button></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="status"><b class="text-danger">*</b>Submission Status for MW:{{$mw}} - Year: {{$year}}</label>
                      <select class="form-control" name="status" id="status" required>
                        <option value="" disabled {{is_null(old('status')) ? 'selected' : ''}}>Choose...</option>
                        <option value="ZERO CASE" {{(old('status') == 'ZERO CASE') ? 'selected' : ''}}>ZERO CASE</option>
                        <option value="SUBMITTED" {{(old('status') == 'SUBMITTED') ? 'selected' : ''}}>SUBMITTED</option>
                      </select>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    Body
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection