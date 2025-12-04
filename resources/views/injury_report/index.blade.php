@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Injury Reporting Tool</b></div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fwcsvmodal">Upload Fireworks Injury CSV (FWRI)</button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#injurymodal">Upload Injury CSV</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

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
                      <label for="csv_file"><b class="text-danger">*</b>Select the FWRI .CSV File to upload</label>
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
@endsection