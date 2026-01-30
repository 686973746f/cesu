@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>{{ $type }}</b></div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPatientModal">New Patient</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printTcl">Print TCL</button>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}">
                        {{session('msg')}}
                    </div>
                @endif
    
                @if($type == 'maternal_care')
                    @include('efhsis.etcl.maternalcare_list')
                @elseif($type == 'child_care')
                    @include('efhsis.etcl.childcare_list')
                @else
                    <div class="alert alert-warning">
                        Please select a valid eTCL module from the <a href="{{route('etcl_home')}}">eTCL Home</a>.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('syndromic.newpatient_modal')

    <form action="{{route('etcl_generatetcl')}}" method="POST">
        @csrf
        <div class="modal fade" id="printTcl" tabindex="-1" role="dialog" aria-labelledby="printTclLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Print TCL</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="start_date"><b class="text-danger">*</b>Start Date</label>
                                  <input type="date" class="form-control" name="start_date" id="start_date" value="{{date('Y-m-01')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><b class="text-danger">*</b>End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="etcl_type" value="{{$type}}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Generate TCL Excel File</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#start_date').on('change', function () {
            $('#end_date').prop('min', $(this).val());
        });
    </script>
@endsection