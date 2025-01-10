@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>BLS/SFA Training Batch List</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newBatch">New Batch</button>
                    <a href="{{route('bls_home_masterlist')}}" class="btn btn-primary">Switch to Masterlist View</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
        </div>
    </div>
</div>

<form action="{{route('bls_storebatch')}}" method="POST">
    @csrf
    <div class="modal fade" id="newBatch" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Batch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="batch_number"><b class="text-danger">*</b>Batch Number</label>
                      <input type="text" class="form-control" name="batch_number" id="batch_number" style="text-transform: uppercase" value="{{old('batch_number')}}" pattern="[0-9]" required>
                    </div>
                    <div class="form-group">
                        <label for="batch_name"><b class="text-danger">*</b>Batch Name</label>
                        <input type="text" class="form-control" name="batch_name" id="batch_name" style="text-transform: uppercase" value="{{old('batch_name')}}" required>
                    </div>
                    <div class="form-check mb-3">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="is_refresher" id="is_refresher" value="1">
                        Is Refresher Course?
                      </label>
                    </div>
                    <div class="form-group">
                        <label for="batch_number"><b class="text-danger">*</b>Agency</label>
                        <input type="text" class="form-control" name="agency" id="agency" style="text-transform: uppercase" value="{{old('agency', 'GENERAL TRIAS CITY HEALTH OFFICE')}}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="training_date_start"><b class="text-danger">*</b>Start of Training</label>
                                <input type="date" class="form-control" name="training_date_start" id="training_date_start" value="{{old('training_date_start')}}" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="training_date_end">End of Training</label>
                                <input type="date" class="form-control" name="training_date_end" id="training_date_end" value="{{old('training_date_end')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="venue"><b class="text-danger">*</b>Venue</label>
                        <input type="text" class="form-control" name="venue" id="venue" style="text-transform: uppercase" value="{{old('venue')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="instructors_list"><b class="text-danger">*</b>List of Instructors (Separate by Slash [/])</label>
                        <input type="text" class="form-control" name="instructors_list" id="instructors_list" style="text-transform: uppercase" value="{{old('instructors_list')}}" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="prepared_by"><b class="text-danger">*</b>Prepared By</label>
                        <input type="text" class="form-control" name="prepared_by" id="prepared_by" style="text-transform: uppercase" value="{{old('prepared_by')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection