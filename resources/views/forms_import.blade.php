@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('forms.import.init')}}" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">Import CIF Data from Excel</div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                      <label for="thefile">Browse to Excel File</label>
                      <input type="file" class="form-control-file" name="thefile" id="thefile" required>
                      <small id="fileHelpId" class="form-text text-muted">Help text</small>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </form>
    </div>
@endsection