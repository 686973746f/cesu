@extends('layouts.app')

@section('content')
<form action="{{route('fhsis_livebirth_encode')}}" method="GET">
    <input type="hidden" class="form-control" name="year" id="year" value="{{request()->input('year')}}" required>
    <input type="hidden" class="form-control" name="month" id="month" value="{{request()->input('month')}}" required>

    <div class="container" style="font-family: Arial, Helvetica, sans-serif;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Encode Livebirths (LCR)</b></div>
                            <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#liveBirthModal">Change Encoding Period</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="registryno"><b class="text-danger">*</b>Registry No.</label>
                            <input type="text" class="form-control" name="registryno" id="registryno" value="{{old('registryno', request()->input('year').'-')}}" minlength="6" maxlength="11" required autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-block">Check</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('efhsis.livebirth_check_modal')

@endsection