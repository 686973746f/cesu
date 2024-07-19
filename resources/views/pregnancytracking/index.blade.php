@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Pregnancy Tracking Tool</b></div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#monthlyreport1">Monthly Report</button>
                    <a href="{{route('ptracking_new')}}" class="btn btn-success">Add</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <p class="text-center">List will be added soon.</p>
        </div>
    </div>
</div>

<form action="{{route('ptracking_monthlyreport1')}}" method="POST">
    @csrf
    <div class="modal fade" id="monthlyreport1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Monthly Report</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for=""><b class="text-danger">*</b>Select Year</label>
                      <input type="number" class="form-control" name="year" id="year" min="2023" max="{{date('Y')}}" value="{{old('year', date('Y'))}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection