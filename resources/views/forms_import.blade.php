@extends('layouts.app')

@section('content')
    <form action="{{route('forms.upload')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header">Import Records and CIF</div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="">Upload Excel File to Import</label>
                      <input type="file" class="form-control-file" name="thefile" id="thefile" required>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </form>
@endsection