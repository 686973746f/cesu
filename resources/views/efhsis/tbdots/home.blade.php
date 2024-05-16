@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header"><b>FHSIS TB-DOTS</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#importTool">Import Excel File</button>
                        <hr>
                        <a href="" class="btn btn-primary btn-block">Encoding Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('fhsis_tbdots_import')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="importTool" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Excel Tool</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Upload Excel file from ITIS</label>
                          <input type="file" class="form-control-file" name="itis_file" id="itis_file" required>
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